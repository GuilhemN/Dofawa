<?php
namespace Dof\CharactersBundle;

use Symfony\Component\Translation\TranslatorInterface;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

use XN\Persistence\IdentifiableInterface;

class EffectParamLoader
{
    /**
     * @var TranslatorInterface
     *
     * Translator for injecting into effect entities, for stringification
     */
    private $translator;

    /**
     * @var boolean
     *
     * Sometimes you may want to disable automatic parameter loading,
     * for example when importing data
     */
    private $enabled;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->enabled = true;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $ent = $args->getEntity();
        if ($ent instanceof EffectInterface) {
            $ent->setTranslator($this->translator);
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
                    $isValid = $rel->getColumn1() !== null || $rel->getColumn2() !== null || $rel->getColumn3() !== null;
                    $isReplacement =
                        $rel->getColumn1() === 'id' && $rel->getColumn2() === null && $rel->getColumn3() === null ||
                        $rel->getColumn1() === null && $rel->getColumn2() === 'id' && $rel->getColumn3() === null ||
                        $rel->getColumn1() === null && $rel->getColumn2() === null && $rel->getColumn3() === 'id';
                    $isFragment = $rel->isFragment();
                    if ($isValid && ($isReplacement || $isFragment)) {
                        $repo = $em->getRepository($rel->getTargetEntity());
                        if ($isReplacement) {
                            if ($rel->getColumn1() !== null) {
                                $target = $repo->find($param1);
                                $ent->setParam1($target);
                            } elseif ($rel->getColumn2() !== null) {
                                $target = $repo->find($param2);
                                $ent->setParam2($target);
                            } else {
                                $target = $repo->find($param3);
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
                        if ($isFragment)
                            $ent->addFragment($target);
                    }
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
