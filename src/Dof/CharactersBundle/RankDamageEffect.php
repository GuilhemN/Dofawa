<?php
namespace Dof\CharactersBundle;

use Dof\CharactersBundle\Entity\SpellRankEffect;
use Dof\ItemsBundle\EffectListHelper;

use Dof\CharactersBundle\EffectInterface;
use Dof\CharactersBundle\EffectTrait;

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
        $this->param1 = $this->effect->getParam1() * (100 + $characteristics[$row[2]]) / 100 + $characteristics[$row[3]];
        $this->param2 = $this->effect->getParam2() * (100 + $characteristics[$row[2]]) / 100 + $characteristics[$row[3]];
    }

    public function getDescription($locale = 'fr', $full = false)
    {
        $translator = $this->di->get('translator');
        $desc = $this->getEffectTemplate()->expandDescription([
            '1' => $this->param1,
            '2' => $this->param2,
            '3' => $this->effect->getParam3()
        ], $locale);
        if($full){
            array_unshift($desc, [ '[' . $this->effect->getEffectTemplate()->getId() . '] ', GameTemplateString::COMES_FROM_TEMPLATE ]);
            $desc[] = [ ' (' . implode(', ', $this->effect->targets) . ' sur ' . $this->effect->areaOfEffect . ')', GameTemplateString::COMES_FROM_TEMPLATE ];
        }
        if ($this->effect->duration)
        $desc[] = [ ' (' . $translator->transChoice('duration', $this->effect->duration, ['%count%' => $this->effect->duration], 'spell') . ')', GameTemplateString::COMES_FROM_TEMPLATE ];
        if ($this->effect->delay)
        $desc[] = [ ' (dans ' . $this->effect->delay . ' tours)', GameTemplateString::COMES_FROM_TEMPLATE ];
        if (implode(',', $this->effect->triggers) != 'I'){
            if($full)
            $triggers = '(' . implode(', ', $this->effect->triggers) . ') ';
            else
            $triggers = '';
            array_unshift($desc, [ 'Déclenché ' . $triggers . ': ', GameTemplateString::COMES_FROM_TEMPLATE ]);
        }
        return $desc;
    }

    public function getFragments(){
        return $this->effect->getFragments();
    }
}
