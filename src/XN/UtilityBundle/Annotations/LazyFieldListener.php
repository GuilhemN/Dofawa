<?php
namespace XN\UtilityBundle\Annotations;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\Cache;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

use XN\Annotations\LazyField;

class LazyFieldListener
{
    private $ca;
    private $re;

    public function __construct(Cache $ca, Reader $re){
        $this->ca = $ca;
        $this->re = $re;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $ent = $args->getEntity();
        $class = $em->getClassMetadata(get_class($ent))->getName();

        if ($lazyFieldsString = $this->ca->fetch('xn-lazy-fields/' . $class)) {
            $lazyFields = unserialize($lazyFieldsString);
        } else {
            //Properties
            if ($propertiesString = $this->ca->fetch('xn-class-properties/' . $class)) {
                $properties = unserialize($propertiesString);
            } else {
                $reflect = new \ReflectionClass($ent);
                $properties = array_map(function($v){
                    return $v->getName();
                }, $reflect->getProperties());
                $this->ca->save('xn-class-properties/' . $class, serialize($properties));
            }
            var_dump($properties);
            die();
            //Lazy Fields
            $lazyFields = [];
            foreach($properties as $property){
                $annotation = $this->re->getPropertyAnnotation(new \ReflectionProperty($ent, $property), 'XN\Annotations\LazyField');
                if($annotation)
                    $lazyFields[$property] = $annotation;
            }
            $this->ca->save('xn-lazy-fields/' . $class, serialize($properties));
        }

        foreach($lazyFields as $k => $v) {
            $classMethod = !empty($v->classMethod) ? $v->classMethod : 'getClass';
            $valueMethod = !empty($v->valueMethod) ? $v->valueMethod : 'getClassId';
            $setter = !empty($v->setter) ? $v->setter : 'set' . ucfirst($k);

            $table = call_user_func([ $ent, $classMethod ]);
            $id = call_user_func([ $ent, $valueMethod ]);
            if (method_exists($em, 'getReference'))
                $result = $em->getReference($table, $id);
            else
                $result = $em->find($table, $id);

            call_user_func($ent, $setter);
        }
    }
}
