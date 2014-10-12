<?php
namespace XN\Security;

use XN\UtilityBundle\TOTPAuthenticationListener;

class TOTPGenerator {
    public static function genSecret(){
        $length = 16;

        while($length--)
            $secret .= mt_rand(0, 31);

        return $secret;
    }
}
