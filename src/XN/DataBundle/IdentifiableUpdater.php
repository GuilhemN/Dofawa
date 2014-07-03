<?php

namespace XN\DataBundle;

use Doctrine\ORM\Event\LifecycleEventArgs;

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
		$ent = $args->getObject();
		if ($ent instanceof IdentifiableInterface && $ent->getId() === null) {
			$id = $this->idgen->generate();
			if (count($id) == 0)
				throw new \Exception('Problème de génération d\'IDs. Le service "seqgend" est-il opérationnel ?');
			$ent->setId($id[0]);
		}
	}
}
