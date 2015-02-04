<?php

namespace Dof\Bundle\GraphicsBundle;

class EntityLookTransforms
{
    private function __construct() { }

    public static function locatePC(EntityLook $look)
    {
        if ($look->getBone() == 1 || $look->getBone() == 2)
            return $look;
        foreach ($look->getSubEntities() as $se) {
            foreach ($se as $subent) {
                $result = self::locatePC($subent);
                if ($result !== null)
                    return $result;
            }
        }
        return null;
    }

    public static function locateAnimalFromPC(EntityLook $look)
    {
        if ($look->getClassId() === 2 && $look->getIndex() === 0)
            return $look->getParent();
        return $look->getSubEntity(1, 0);
    }
}
