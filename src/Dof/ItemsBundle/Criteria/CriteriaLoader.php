<?php
namespace Dof\ItemsBundle\Criteria;

use XN\Common\ServiceWithContainer;
use XN\Persistence\IdentifiableInterface;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Dof\ItemsBundle\Criteria\Criterion;
use Dof\ItemsBundle\Criteria\SimpleCriterion;

use Dof\Common\PseudoRepositoriesTrait;

class CriteriaLoader extends ServiceWithContainer
{
    use PseudoRepositoriesTrait;

    /**
    * @var boolean
    *
    * Sometimes you may want to disable automatic parameter loading,
    * for example when importing data
    */
    private $enabled;

    private $criteriaTemplates = array();

    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);
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

    public function transform(Criterion $ent){
        if($ent instanceOf SimpleCriterion){
            $em = $this->getEntityManager();
            $ent->setContainer($this->di);
            $tpl = $this->getCriterionTemplate($ent->getCharacteristic(), $ent->getOperator());
            if($tpl === null) {
                $ent->setVisible(false);
                return;
            }
            $ent->setVisible(true);
            $ent->setCriterionTemplate($tpl);
            $params = $ent->getParams();
            $param1 = isset($params[0]) ? $params[0] : 0;
            if ($param1 instanceof IdentifiableInterface)
                $param1 = $param1->getId();
            $param2 = isset($params[1]) ? $params[1] : 0;
            if ($param2 instanceof IdentifiableInterface)
                $param2 = $param2->getId();
            $param3 = isset($params[2]) ? $params[2] : 0;
            if ($param3 instanceof IdentifiableInterface)
                $param3 = $param3->getId();

            foreach ($tpl->getRelations() as $rel) {
                if ($rel->getColumn1() === null && $rel->getColumn2() === null && $rel->getColumn3() === null)
                    continue;
                $isReplacement =
                $rel->getColumn1() === 'id' && $rel->getColumn2() === null && $rel->getColumn3() === null ||
                $rel->getColumn1() === null && $rel->getColumn2() === 'id' && $rel->getColumn3() === null ||
                $rel->getColumn1() === null && $rel->getColumn2() === null && $rel->getColumn3() === 'id';
                if (!$isReplacement)
                    continue;
                $type = $rel->getTargetEntity();
                if (strpos($type, ':') === false)
                    $repo = $this->getPseudoRepository($type);
                else
                    $repo = $em->getRepository($type);
                if ($isReplacement) {
                    if ($rel->getColumn1() !== null) {
                        $target = $repo->find($param1);
                        if ($target)
                        $ent->setParam1($target);
                    } elseif ($rel->getColumn2() !== null) {
                        $target = $repo->find($param2);
                        if ($target)
                            $ent->setParam2($target);
                    } else {
                        $target = $repo->find($param3);
                        if ($target)
                            $ent->setParam3($target);
                    }
                } else {
                    $criteria = [ ];
                    if ($rel->getColumn1() !== null)
                        $criteria[$rel->getColumn1()] = $param1;
                    if ($rel->getColumn2() !== null)
                        $criteria[$rel->getColumn2()] = $param2;
                    if ($rel->getColumn3() !== null)
                        $criteria[$rel->getColumn3()] = $param3;
                    $target = $repo->findOneBy($criteria);
                }
            }
        }
        elseif($ent !== null)
            foreach($ent->getCriteria() as $c)
                $this->transform($c);

        return $ent;
    }

    protected function getCriterionTemplate($characteristic, $operator, $searchInDb = true) {
        if(isset($this->criteriaTemplates[$characteristic])){
            foreach($this->criteriaTemplates[$characteristic] as $tpl)
                if($tpl->getOperator() === $operator)
                    return $tpl;
                elseif($tpl->getOperator() === null)
                    $nullOperator = $tpl;
            return isset($nullOperator) ? $nullOperator : null;
        }
        elseif($searchInDb) {
            $em = $this->getEntityManager();
            $repo = $em->getRepository('DofItemsBundle:CriterionTemplate');
            $cTpls = $repo->findByCharacteristic($characteristic);
            $cTpls2 = array();
            if(is_array($cTpls))
            foreach($cTpls as $cTpl){
                $cTpls2[$cTpl->getCharacteristic()] = array();
                $c = $cTpls2[$cTpl->getCharacteristic()];
                $c[$cTpl->getOperator()] = $cTpl;
            }
            array_merge($this->criteriaTemplates, $cTpls2);
            return $this->getCriterionTemplate($characteristic, $operator, false);
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
