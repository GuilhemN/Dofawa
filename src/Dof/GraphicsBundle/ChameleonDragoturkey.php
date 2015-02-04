<?php

namespace Dof\GraphicsBundle;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Doctrine\ORM\EntityManager;

use XN\Common\Inflector;
use Dof\ItemBundle\AnimalColorizationType;
use Dof\ItemBundle\ItemSlot;

class ChameleonDragoturkey
{
    /**
     * @var Translator
     */
    private $translator;

    private $em;
    private $type;

    public function __construct(Translator $translator, EntityManager $em)
    {
        $this->translator = $translator;
        $this->em = $em;
    }
    public static function getId(){
      static $id = null;
      if ($id === null)
          $id = 'chameleon';
      return $id;
    }
    public function getName($locale = null)
    {
        if($locale === null)
            $locale = $this->translator->getLocale();

        $locale = (array) $locale;

        if(is_array($locale))
            foreach($locale as $l)
                return $this->translator->transChoice('dragoturkey.chameleon', 1, [ ], 'type_item', $l);
    }

    public function getType(){
      if (empty($this->type))
          $this->type = $this->em->getRepository('DofItemBundle:ItemType')->findBySlot(ItemSlot::MOUNT)[0];

      return $this->type;
    }

    public function getBone()
    {
        return 639;
    }

    public function getSize()
    {
        return 120;
    }

    public function getColorizationType()
    {
        return AnimalColorizationType::SHIFTED_CHAMELEON;
    }

    public function isEquipment() { return true; }
    public function isSkinned() { return false; }
    public function isWeapon() { return false; }
    public function isAnimal() { return true; }
    public function isPet() { return false; }
    public function isMount() { return true; }
    public function isUseable() { return false; }

    public function getSkins()
    {
        return [ ];
    }

    public function getColors()
    {
        return [ ];
    }

    public function __toString()
    {
        return $this->getName('fr');
    }

    public function getSlug()
    {
        return Inflector::slugify($this->__toString());
    }
}
