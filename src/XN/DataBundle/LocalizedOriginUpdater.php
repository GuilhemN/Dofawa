<?php

namespace XN\DataBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;

class LocalizedOriginUpdater
{
  use MajorColumnsListenerTrait;

  /**
   * @var ContainerInterface
   */
  private $di;
  private $rs;

  public function __construct(ContainerInterface $di, RequestStack $rs)
  {
    $this->di = $di;
    $this->rs = $rs;
  }

  public function prePersist(LifecycleEventArgs $args)
  {
    $locale = $this->di->get('translator')->getLocale();

    $ent = $args->getEntity();
    if ($ent instanceof LocalizedOriginInterface) {
      $ent->setUpdatedLocale($locale);
      if ($ent->getCreatedLocale() === null)
        $ent->setCreatedLocale($ent->getUpdatedLocale());
    }
  }

  public function onFlush(OnFlushEventArgs $args)
  {
    $locale = $this->di->get('translator')->getLocale();

    $em = $args->getEntityManager();
    $uow = $em->getUnitOfWork();
    $mds = array();
    $updates = array_filter($uow->getScheduledEntityUpdates(), function ($ent) use ($uow, $locale) {
      return $ent instanceof LocalizedOriginInterface && $ent->getUpdatedLocale() !== $locale && self::hasMajorChanges($ent, $uow->getEntityChangeSet($ent));
    });
    foreach ($updates as $ent) {
      $ent->setUpdatedLocale($locale);
      $clazz = get_class($ent);
      if (isset($mds[$clazz]))
        $md = $mds[$clazz];
      else {
        $md = $em->getClassMetadata($clazz);
        $mds[$clazz] = $md;
      }
      $uow->computeChangeSet($md, $ent);
    }
  }
}
