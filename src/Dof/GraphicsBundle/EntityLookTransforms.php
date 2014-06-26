<?php

namespace Dof\GraphicsBundle;

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
}
