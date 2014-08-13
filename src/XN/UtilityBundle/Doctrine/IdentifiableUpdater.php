<?php

namespace XN\UtilityBundle\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;

use XN\Persistence\IdentifiableInterface;
use XN\Persistence\IdentifierGeneratorInterface;

class IdentifiableUpdater
{
	/**
	 * @var IdentifierGeneratorInterface
	 */
	private $idgen;

	public function __construct(IdentifierGeneratorInterface $idgen)
	{
		$this->idgen = $idgen;
	}

	public function prePersist(LifecycleEventArgs $args)
	{
		$ent = $args->getEntity();
		if ($ent instanceof IdentifiableInterface && $ent->getId() === null) {
			$id = $this->idgen->generate();
			if (count($id) == 0)
				throw new \Exception('Problème de génération d\'IDs. Le service "seqgend" est-il opérationnel ?');
			$ent->setId($id[0]);
		}
	}
}
