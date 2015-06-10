<?php

namespace XN\UtilityBundle\Doctrine;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Event\OnFlushEventArgs;
use XN\Metadata\MajorColumnsListenerTrait;
use XN\Persistence\IdentifiableInterface;
use XN\L10n\LocalizedNameInterface;
use XN\UtilityBundle\Entity\NameIndex;

class IndexableNamesListener
{
    use MajorColumnsListenerTrait;

    /**
     * @var ContainerInterface
     */
    private $di;

    /**
     * @var Reader
     */
    private $re;

    public function __construct(ContainerInterface $di, Reader $re)
    {
        $this->re = $re;
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $indexRepo = $em->getRepository('XNUtilityBundle:NameIndex');
        $uow = $em->getUnitOfWork();
        $mds = array();
        $majors = [];
        foreach ($this->di->get('locales') as $k) {
            $majors[] = 'name'.ucfirst($k);
        }
        $updates = array_filter($uow->getScheduledEntityUpdates(), function ($ent) use ($uow, $majors) {
            return $ent instanceof IdentifiableInterface &&
                $ent instanceof LocalizedNameInterface &&
                $this->re->getClassAnnotation($ent, 'XN\Annotations\IndexableNames') &&
                $this->hasMajorChangesWithColumns($ent, $uow->getEntityChangeSet($ent), $majors);
        });
        foreach ($updates as $ent) {
            $class = $em->getClassMetadata(get_class($ent))->getName();
            $index = $indexRepo->findOneBy(['class' => $class, 'id' => $ent->getId()]);
            if ($index === null) {
                $index = new NameIndex();
                $index->setEntity;
            }
            $ent->setUpdater($user);
            $clazz = get_class($ent);
            if (isset($mds[$clazz])) {
                $md = $mds[$clazz];
            } else {
                $md = $class;
                $mds[$clazz] = $md;
            }
            $uow->recomputeSingleEntityChangeSet($md, $ent);
        }
    }
}
