<?php

namespace XN\Security;

class TOTPGenerator
{
    public static function genSecret($length = 16)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
}
