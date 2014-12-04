<?php
namespace Dof\BuildBundle;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use XN\Persistence\IdentifiableInterface;
use XN\Common\ServiceWithContainer;

use Dof\BuildBundle\Entity\Stuff;

class StuffLookLoader extends ServiceWithContainer
{

    /**
    * @var boolean
    *
    * Sometimes you may want to disable automatic parameter loading,
    * for example when importing data
    */
    private $enabled;

    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);
        $this->enabled = true;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $ent = $args->getEntity();
        if ($ent instanceof Stuff) {
            if ($this->enabled) {
                $faces = $em->getRepository('DofCharactersBundle:Face');

                $face = $faces->findOneBy(array('breed' => $ent->getCharacter()->getBreed(), 'label' => $ent->getFaceLabel(), 'gender' => $ent->getLook()->getGender()));
                $ent->getLook()->setFace($face);
            }
        }
    }

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }
}
