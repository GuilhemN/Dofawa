<?php
namespace XN\Security;

use XN\UtilityBundle\TOTPAuthenticationListener;

class TOTPGenerator {
    public function genSecret($length = 16){
        if( $length < 16 || $length % 8 != 0 )
            $length = 16;

        while($length){
            $secret .= array_search(mt_rand(0, 31), TOTPAuthenticationListener::$lut);
        }
    }
}
