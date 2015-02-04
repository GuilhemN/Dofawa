<?php

namespace Dof\Bundle\GraphicsBundle;

use XN\Common\Inflector;

use Dof\Bundle\ItemBundle\Entity\ItemTemplate;
use Dof\Bundle\ItemBundle\Entity\ItemType;

class LivingItem
{
    private $template;
    private $type;
    private $level;

    public function __construct(ItemTemplate $template, ItemType $type, $level)
    {
        $this->template = $template;
        $this->type = $type;
        $this->level = $level;
    }

    public function setTemplate(ItemTemplate $template)
    {
        $this->template = $template;
        return $this;
    }
    public function getTemplate()
    {
        return $this->template;
    }

    public function setType(ItemType $type)
    {
        $this->type = $type;
        return $this;
    }
    public function getType()
    {
        return $this->type;
    }

    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }
    public function getLevel()
    {
        return $this->level;
    }

    public function getName($locale)
    {
        return $this->template->getName($locale) . ' ' . $this->level;
    }

    public function getSkin()
    {
        return self::getBaseSkin($this->template->getId()) + $this->level;
    }

    public function __toString()
    {
        return $this->getName('fr');
    }

    public function getSlug()
    {
        return Inflector::slugify($this->__toString());
    }

    public function isEquipment() { return true; }
    public function isSkinned() { return true; }
    public function isWeapon() { return false; }
    public function isAnimal() { return false; }
    public function isPet() { return false; }
    public function isMount() { return false; }
    public function isUseable() { return false; }

    public static function getBaseSkins()
    {
        static $baseSkins = null;
        if ($baseSkins === null)
            $baseSkins = [ 9234 => 1135, 9233 => 1115, 12424 => 1489, 12425 => 1469, 13213 => 1707, 13211 => 1687 ];
        return $baseSkins;
    }
    public static function getTypes()
    {
        static $types = null;
        if ($types === null)
            $types = [ 9234 => 16, 9233 => 17, 12424 => 16, 12425 => 17, 13213 => 16, 13211 => 17 ];
        return $types;
    }
    private static function getBaseSkin($template)
    {
        $baseSkins = self::getBaseSkins();
        if (isset($baseSkins[$template]))
            return $baseSkins[$template];
        return null;
    }
}
