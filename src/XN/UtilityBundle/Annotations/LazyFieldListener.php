<?php
namespace XN\UtilityBundle\Annotations;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\Cache;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
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
        $lazyFields = $this->getLazyFields($ent, $em);

        foreach($lazyFields as $k => $v) {
            $classMethod = $v->getClassMethod();
            $valueMethod = $v->getValueMethod();
            $setter = 'set' . ucfirst($k);

            $table = call_user_func([ $ent, $classMethod ]);
            $id = call_user_func([ $ent, $valueMethod ]);

            if($table === null or $id === null)
                continue;
            if (method_exists($em, 'getReference'))
                $result = $em->getReference($table, $id);
            else
                $result = $em->find($table, $id);

            call_user_func([ $ent, $setter ], $result);
        }
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $mds = array();
        $updates = array_filter($uow->getScheduledEntityUpdates(), function ($ent) use ($uow, $em) {
            $lazyFields = $this->getLazyFields($ent, $em);
            if(empty($lazyFields))
                return false;
            $chgst = $uow->getEntityChangeSet($ent);
            foreach($lazyFields as $k => $v)
                if(isset($chgst[$k]))
                    return true;
        });
        var_dump($updates);
        foreach ($updates as $ent) {
            $lazyFields = $this->getLazyFields($ent, $em);
            foreach($lazyFields as $k => $v){
                $e = call_user_func([$ent, 'get' . ucfirst($k)]);
                $classSetter = $v->getSetterClassMethod();
                $valueSetter = $v->getSetterValueMethod();
                if($e instanceof IdentifiableInterface){
                    call_user_func([ $ent, $classSetter], $em->getClassMetadata(get_class($e))->getName());
                    call_user_func([ $ent, $valueSetter], $e->getId());
                }
                else{
                    call_user_func([ $ent, $classSetter], null);
                    call_user_func([ $ent, $valueSetter], null);
                }
            }
            $clazz = get_class($ent);
            if (isset($mds[$clazz]))
            $md = $mds[$clazz];
            else {
                $md = $em->getClassMetadata($clazz);
                $mds[$clazz] = $md;
            }
            $uow->recomputeSingleEntityChangeSet($md, $ent);
        }
    }

    private function getLazyFields($ent, $em) {
        $class = $em->getClassMetadata(get_class($ent))->getName();
        if ($lazyFieldsString = $this->ca->fetch(md5('xn-lazy-fields/' . $class))) {
            $lazyFields = unserialize($lazyFieldsString);
        } else {
            //Properties
            if ($propertiesString = $this->ca->fetch(md5('xn-class-properties/' . $class))) {
                $properties = unserialize($propertiesString);
            } else {
                $reflect = new \ReflectionClass($ent);
                $properties = array_map(function($v){
                    return $v->getName();
                }, $reflect->getProperties());
                $this->ca->save(md5('xn-class-properties/' . $class), serialize($properties));
            }

            //Lazy Fields
            $lazyFields = [];
            foreach($properties as $property){
                $annotation = $this->re->getPropertyAnnotation(new \ReflectionProperty($ent, $property), 'XN\Annotations\LazyField');
                if($annotation)
                    $lazyFields[$property] = $annotation;
            }
            $this->ca->save(md5('xn-lazy-fields/' . $class), serialize($lazyFields));
        }
        return $lazyFields;
    }
}
