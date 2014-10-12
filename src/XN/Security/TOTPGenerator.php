<?php
namespace XN\Security;

use XN\UtilityBundle\TOTPAuthenticationListener;

class TOTPGenerator {
    public static function genSecret($length = 16){
        if( $length < 16 || $length % 8 != 0 )
            $length = 16;

        for(;$length > 0; $length--)
            $secret .= array_search(mt_rand(0, 31), TOTPAuthenticationListener::$lut);

        return $secret;
    }
}
