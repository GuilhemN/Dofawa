<?php
namespace Dof\CharactersBundle;

use Dof\CharactersBundle\Entity\SpellRankEffect;
use Dof\ItemsBundle\EffectListHelper;

use Dof\CharactersBundle\EffectInterface;
use Dof\CharactersBundle\EffectTrait;

use Dof\Common\GameTemplateString;

class RankDamageEffect implements EffectInterface
{
    use EffectTrait;

    private $effect;

    private $param1;
    private $param2;

    private $di;

    public function __construct(SpellRankEffect $effect){
        $this->effect = $effect;
        $this->di = $this->effect->getContainer();
    }

    public function applyCharateristics(array $characteristics){
        $row = EffectListHelper::getDamageMap()[$this->effect->getEffectTemplate()->getId()];
        $this->param1 = floor($this->effect->getParam1() * (100 + $characteristics[$row[2]]) / 100 + $characteristics[$row[3]]);
        $this->param2 = floor($this->effect->getParam2() * (100 + $characteristics[$row[2]]) / 100 + $characteristics[$row[3]]);
    }

    public function getDescription($locale = 'fr', $full = false)
    {
        $translator = $this->di->get('translator');
        $desc = $this->effect->getEffectTemplate()->expandDescription([
            '1' => $this->param1,
            '2' => $this->param2,
            '3' => $this->effect->getParam3()
        ], $locale);
        if($full){
            array_unshift($desc, [ '[' . $this->effect->getEffectTemplate()->getId() . '] ', GameTemplateString::COMES_FROM_TEMPLATE ]);
            $desc[] = [ ' (' . implode(', ', $this->effect->getTargets()) . ' sur ' . $this->effect->getAreaOfEffect() . ')', GameTemplateString::COMES_FROM_TEMPLATE ];
        }
        if ($this->effect->getDuration())
        $desc[] = [ ' (' . $translator->transChoice('duration', $this->effect->getDuration(), ['%count%' => $this->effect->getDuration()], 'spell') . ')', GameTemplateString::COMES_FROM_TEMPLATE ];
        if ($this->effect->getDelay())
        $desc[] = [ ' (dans ' . $this->effect->getDelay() . ' tours)', GameTemplateString::COMES_FROM_TEMPLATE ];
        if (implode(',', $this->effect->getTriggers()) != 'I'){
            if($full)
            $triggers = '(' . implode(', ', $this->effect->getTriggers()) . ') ';
            else
            $triggers = '';
            array_unshift($desc, [ 'Déclenché ' . $triggers . ': ', GameTemplateString::COMES_FROM_TEMPLATE ]);
        }
        return $desc;
    }

    public function getFragments(){
        return $this->effect->getFragments();
    }

    public function getAreaOfEffect(){
        return $this->effect->getAreaOfEffect();
    }

    public function isCritical(){
        return $this->effect->isCritical();
    }
}
