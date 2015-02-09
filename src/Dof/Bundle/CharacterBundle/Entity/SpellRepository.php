<?php

namespace Dof\Bundle\CharacterBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SpellRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SpellRepository extends EntityRepository
{
	public function findBySpellEffectParam($param)
	{
		return $this->findByObjectEffectParam('DofCharacterBundle:Spell', $param);
	}
	public function findByMonsterEffectParam($param)
	{
		return $this->findByObjectEffectParam('DofMonsterBundle:Monster', $param);
	}
	public function findByStateEffectParam($param)
	{
		return $this->findByObjectEffectParam('DofCharacterBundle:State', $param);
	}

	public function findByEffectTemplate($effectTemplate)
	{
		return $this->createQueryBuilder('s')
			->where(<<<'EOS'
s.id IN (
	SELECT IDENTITY(sr.spell)
	FROM DofCharacterBundle:SpellRank sr
	WHERE sr.id IN (
		SELECT IDENTITY(sre.spellRank)
		FROM DofCharacterBundle:SpellRankEffect sre
		WHERE sre.effectTemplate = :effectTemplate
	)
)
EOS
)
			->getQuery()
			->setParameter('effectTemplate', is_object($effectTemplate) ? $effectTemplate->getId() : $effectTemplate)
			->getResult();
	}

	public function findByObjectEffectParam($relationType, $param)
	{
		return $this->createQueryBuilder('s')
			->where(<<<'EOS'
s.id IN (
	SELECT IDENTITY(sr.spell)
	FROM DofCharacterBundle:SpellRank sr
	WHERE sr.id IN (
		SELECT IDENTITY(sre.spellRank)
		FROM DofCharacterBundle:SpellRankEffect sre
		JOIN sre.effectTemplate et
		JOIN et.relations etr WITH etr.targetEntity = :relationType AND (etr.column1 = 'id' OR etr.column2 = 'id' OR etr.column3 = 'id')
		WHERE etr.column1 = 'id' AND sre.rawParam1 = :param OR etr.column2 = 'id' AND sre.rawParam2 = :param OR etr.column3 = 'id' AND sre.rawParam3 = :param
	)
)
EOS
)
			->getQuery()
			->setParameter('relationType', $relationType)
			->setParameter('param', is_object($param) ? $param->getId() : $param)
			->getResult();
	}
}