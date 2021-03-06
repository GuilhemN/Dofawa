<?php

namespace XN\Common;

// http://fr.php.net/manual/fr/function.base64-encode.php#103849
class UrlSafeBase64
{
    private function __construct()
    {
    }

    public static function encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    public static function decode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}
