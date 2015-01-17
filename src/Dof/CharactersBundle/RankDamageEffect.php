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
    private $type;

    private $di;

    public function __construct(SpellRankEffect $effect){
        $this->effect = $effect;
        $this->di = $this->effect->getContainer();
    }

    public function applyCharateristics(array $characteristics){
        $row = self::getDamageMap()[$this->effect->getEffectTemplate()->getId()];
        $this->param1 = $this->calcParam(1, $row, $characteristics);
        $this->param2 = $this->calcParam(2, $row, $characteristics);
    }

    private function calcParam($param, $row, array $characteristics){
        $this->setType($row[0]);
        $caract = 0; $bonus = 0;
        foreach($row[1] as $v)
            if(($c = $characteristics[$v]) > 0)
                $caract += $c;
        foreach($row[2] as $v)
            if(($b = $characteristics[$v]) > 0)
                $bonus += $b;
        return floor($this->effect->{ 'getParam' . $param }() * (100 + $caract) / 100 + $bonus);
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
        if (implode(',', $this->effect->getRawTriggers()) != 'I'){
            if($full)
                $triggers = '(' . implode(', ', $this->effect->getRawTriggers()) . ') ';
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

    public function setType($type){
        $this->type = $type;
        return $this;
    }

    public function getType(){
        return $this->type;
    }

    public function isHidden(){
        return $this->effect->isHidden();
    }

    public static function getDamageMap(){
        static $map = null;
        if ($map === null)
        // 0 = damage
        // 1 = steal
        // 2 = heal
        $map = [
            91 => [ 1, [ "chance", "power" ], [ "waterDamage", "damage" ] ],
            92 => [ 1, [ "strength", "power" ], [ "earthDamage", "damage" ] ],
            93 => [ 1, [ "agility", "power" ], [ "airDamage", "damage" ] ],
            94 => [ 1, [ "intelligence", "power" ], [ "fireDamage", "damage" ] ],
            95 => [ 1, [ "strength", "power" ], [ "neutralDamage", "damage" ] ],
            96 => [ 0, [ "chance", "power" ], [ "waterDamage", "damage" ] ],
            97 => [ 0, [ "strength", "power" ], [ "earthDamage", "damage" ] ],
            98 => [ 0, [ "agility", "power" ], [ "airDamage", "damage" ] ],
            99 => [ 0, [ "intelligence", "power" ], [ "fireDamage", "damage" ] ],
            100 => [ 0, [ "strength", "power" ], [ "neutralDamage", "damage" ] ],
            108 => [ 2, [ "intelligence" ], [ "heals" ] ],
            646 => [ 2, [ "intelligence" ], [ "heals" ] ]
        ];
        return $map;
    }
}
