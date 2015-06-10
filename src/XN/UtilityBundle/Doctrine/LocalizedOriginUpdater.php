<?php

namespace XN\UtilityBundle\Doctrine;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use XN\L10n\LocalizedOriginInterface;
use XN\Metadata\MajorColumnsListenerTrait;

class LocalizedOriginUpdater
{
    use MajorColumnsListenerTrait;

  /**
   * @var ContainerInterface
   */
  private $di;

    public function __construct(ContainerInterface $di)
    {
        $this->di = $di;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $locale = $this->di->get('translator')->getLocale();

        $ent = $args->getEntity();
        if ($ent instanceof LocalizedOriginInterface) {
            $ent->setUpdatedOnLocale($locale);
            if ($ent->getCreatedOnLocale() === null) {
                $ent->setCreatedOnLocale($ent->getUpdatedOnLocale());
            }
        }
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $locale = $this->di->get('translator')->getLocale();

        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $mds = array();
        $updates = array_filter($uow->getScheduledEntityUpdates(), function ($ent) use ($uow, $locale) {
      return $ent instanceof LocalizedOriginInterface && $ent->getUpdatedOnLocale() !== $locale && self::hasMajorChanges($ent, $uow->getEntityChangeSet($ent));
    });
        foreach ($updates as $ent) {
            $ent->setUpdatedOnLocale($locale);
            $clazz = get_class($ent);
            if (isset($mds[$clazz])) {
                $md = $mds[$clazz];
            } else {
                $md = $em->getClassMetadata($clazz);
                $mds[$clazz] = $md;
            }
            $uow->recomputeSingleEntityChangeSet($md, $ent);
        }
    }
}
