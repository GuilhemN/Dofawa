<?php
namespace Dof\CharactersBundle;

use Symfony\Component\Translation\TranslatorInterface;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

use XN\Persistence\IdentifiableInterface;
use XN\Common\ServiceWithContainer;

class EffectParamLoader extends ServiceWithContainer
{

    /**
     * @var boolean
     *
     * Sometimes you may want to disable automatic parameter loading,
     * for example when importing data
     */
    private $enabled;

    public function postLoad(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $ent = $args->getEntity();
        if ($ent instanceof EffectInterface) {
            $ent->setContainer($this->di);
            if ($this->enabled) {
                // Docs say associations are not loaded when postLoad is called
                // Code seems to say that they actually are ...
                // So this may or may not work
                $tpl = $ent->getEffectTemplate();
                $param1 = $ent->getParam1();
                if ($param1 instanceof IdentifiableInterface)
                    $param1 = $param1->getId();
                $param2 = $ent->getParam2();
                if ($param2 instanceof IdentifiableInterface)
                    $param2 = $param2->getId();
                $param3 = $ent->getParam3();
                if ($param3 instanceof IdentifiableInterface)
                    $param3 = $param3->getId();
                $ent->getFragments()->clear();
                foreach ($tpl->getRelations() as $rel) {
                    if ($rel->getColumn1() === null && $rel->getColumn2() === null && $rel->getColumn3() === null)
						continue;
                    $isReplacement =
                        $rel->getColumn1() === 'id' && $rel->getColumn2() === null && $rel->getColumn3() === null ||
                        $rel->getColumn1() === null && $rel->getColumn2() === 'id' && $rel->getColumn3() === null ||
                        $rel->getColumn1() === null && $rel->getColumn2() === null && $rel->getColumn3() === 'id';
                    if (!$isReplacement && !$rel->isFragment())
						continue;
					$repo = $em->getRepository($rel->getTargetEntity());
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
					if ($rel->isFragment() && $target)
						$ent->addFragment($target);
                }
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
