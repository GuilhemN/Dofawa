<?php
namespace XN\Security;

use XN\UtilityBundle\TOTPAuthenticationListener;

class TOTPGenerator {
    public static function genSecret($randomString = null){
        // Decode a random string into binary
        $seed = $randomString ? $randomString : $this->generateRandomString();
        $secretkey = TOTPAuthenticationListener::base32_decode($seed);

        // Use the algorithm to generate the totp code
        return $secretkey;
    }
    public function generateRandomString($length = 32)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}
