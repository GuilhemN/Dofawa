<?php

namespace Dof\Bundle\ItemBundle\Criteria;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\ObjectManager;
use Dof\Common\PseudoRepositoriesTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use XN\Common\ServiceWithContainer;
use XN\Persistence\IdentifiableInterface;

class CriteriaLoader
{
    use PseudoRepositoriesTrait;

    /**
     * @var bool
     *
     * Sometimes you may want to disable automatic parameter loading,
     * for example when importing data
     */
    private $enabled;
    private $container;
    private $authorizationChecker;
    private $objectManager;

    private $criteriaTemplates = array();

    public function __construct(ContainerInterface $container, AuthorizationCheckerInterface $authorizationChecker, ObjectManager $objectManager)
    {
        $this->container = $container;
        $this->authorizationChecker = $authorizationChecker;
        $this->objectManager = $objectManager;

        $this->enabled = true;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $ent = $args->getEntity();
        if ($ent instanceof ParsedCriteriaInterface) {
            if ($this->enabled) {
                // Docs say associations are not loaded when postLoad is called
                // Code seems to say that they actually are ...
                // So this may or may not work
                $criteria = $ent->getParsedCriteria();
                $ent->setTreatedCriteria(!empty($criteria) ? $this->transform($criteria) : null);
            }
        }
    }

    public function transform(Criterion $ent)
    {
        if ($ent instanceof SimpleCriterion) {
            $em = $this->objectManager;
            $ent->setContainer($this->container);
            $params = $ent->getParams();
            $tpl = $this->getCriterionTemplate($ent->getCharacteristic(), $ent->getOperator(), $params[0]);
            if ($tpl === null or (!$tpl->getVisible() && !$this->authorizationChecker->isGranted('ROLE_ITEM_XRAY'))) {
                $ent->setVisible(false);

                return;
            }
            $ent->setVisible(true);
            $ent->setCriterionTemplate($tpl);
            $param1 = isset($params[0]) ? $params[0] : 0;
            if ($param1 instanceof IdentifiableInterface) {
                $param1 = $param1->getId();
            }
            $param2 = isset($params[1]) ? $params[1] : 0;
            if ($param2 instanceof IdentifiableInterface) {
                $param2 = $param2->getId();
            }
            $param3 = isset($params[2]) ? $params[2] : 0;
            if ($param3 instanceof IdentifiableInterface) {
                $param3 = $param3->getId();
            }

            foreach ($tpl->getRelations() as $rel) {
                if ($rel->getColumn1() === null && $rel->getColumn2() === null && $rel->getColumn3() === null) {
                    continue;
                }
                $isReplacement =
                $rel->getColumn1() === 'id' && $rel->getColumn2() === null && $rel->getColumn3() === null ||
                $rel->getColumn1() === null && $rel->getColumn2() === 'id' && $rel->getColumn3() === null ||
                $rel->getColumn1() === null && $rel->getColumn2() === null && $rel->getColumn3() === 'id';
                if (!$isReplacement) {
                    continue;
                }
                $type = $rel->getTargetEntity();
                if (strpos($type, ':') === false) {
                    $repo = $this->getPseudoRepository($type);
                } else {
                    $repo = $em->getRepository($type);
                }
                if ($isReplacement) {
                    if ($rel->getColumn1() !== null) {
                        if (!is_numeric($param1) && $rel->getColumn1() == 'id') {
                            $target = $repo->findOneBySlug($param1);
                        } else {
                            $target = $repo->find($param1);
                        }
                        if ($target) {
                            $ent->setParam1($target);
                        }
                    } elseif ($rel->getColumn2() !== null) {
                        if (!is_numeric($param2) && $rel->getColumn2() == 'id') {
                            $target = $repo->findOneBySlug($param2);
                        } else {
                            $target = $repo->find($param2);
                        }
                        if ($target) {
                            $ent->setParam2($target);
                        }
                    } else {
                        if (!is_numeric($param3) && $rel->getColumn3() == 'id') {
                            $target = $repo->findOneBySlug($param3);
                        } else {
                            $target = $repo->find($param3);
                        }
                        if ($target) {
                            $ent->setParam3($target);
                        }
                    }
                } else {
                    $criteria = [];
                    if ($rel->getColumn1() !== null) {
                        $criteria[$rel->getColumn1()] = $param1;
                    }
                    if ($rel->getColumn2() !== null) {
                        $criteria[$rel->getColumn2()] = $param2;
                    }
                    if ($rel->getColumn3() !== null) {
                        $criteria[$rel->getColumn3()] = $param3;
                    }
                    $target = $repo->findOneBy($criteria);
                }
            }
        } elseif ($ent !== null) {
            foreach ($ent->getCriteria() as $c) {
                $this->transform($c);
            }
        }

        return $ent;
    }

    protected function getCriterionTemplate($characteristic, $operator, $value, $searchInDb = true)
    {
        if (isset($this->criteriaTemplates[$characteristic])) {
            $c = &$this->criteriaTemplates[$characteristic];
            if (isset($c[$operator][$value])) {
                return $c[$operator][$value];
            } elseif (isset($c[$operator][null])) {
                return $c[$operator][null];
            } elseif (isset($c[null][null])) {
                return $c[null][null];
            } else {
                return;
            }
        } elseif ($searchInDb) {
            $em = $this->objectManager;
            $repo = $em->getRepository('DofItemBundle:CriterionTemplate');
            $cTpls = $repo->findByCharacteristic($characteristic);
            $cTpls2 = array();
            if (is_array($cTpls)) {
                foreach ($cTpls as $cTpl) {
                    if ($cTpl->getCharacteristic() == $characteristic) {
                        $cTpls2[$cTpl->getOperator()][$cTpl->getValue()] = $cTpl;
                    }
                }
            }

            $this->criteriaTemplates[$characteristic] = $cTpls2;

            return $this->getCriterionTemplate($characteristic, $operator, $value, false);
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
