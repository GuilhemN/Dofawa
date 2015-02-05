<?php
namespace Dof\Common;

class HTMLToBBCodeConverter
{
    public static function process ($text) {
        $replaces = [
            '<h[1-7].*>(.*)</h[1-7]>' => '[b]$1[/b]',
            '</p><p' => "</p>\n<p",
            '<p[^>]*>' => "\n",
            '</p>' => '',
            '<br(.*)>' => "\n",
            '<b>' => '[b]',
            '<i>' => '[i]',
            '<u>' => '[u]',
            '</b>' => '[/b]',
            '</i>' => '[/i]',
            '</u>' => '[/u]',
            '<em>' => '[b]',
            '</em>' => '[/b]',
            '<strong>' => '[b]',
            '</strong>' => '[/b]',
            '<cite>' => '[i]',
            '</cite>' => '[/i]',
            '<font color="?([^\"]*)"?>(.*)</font>' => '[color=$1]$2[/color]',
            '<link.*>' => '',
            '<li.*>(.*)</li>' => '[*]$1',
            '<ul.*>' => '[list]',
            '</ul>' => '[/list]',
            '<div.*>' => '',
            '</div>' => '',
            '<td.*>' => '',
            '<tr.*>' => '',
            '<img.+src="(.*)".*>' => '[img]$1[/img]',
            '<a.+href="(.*)".*>(.*)</a>' => '[url=$1]$2[/url]',
            '<span.*>(.*)</span>' => '$1'
        ];

        $keys = array_keys($replaces);
        $values = array_values($replaces);

        $keys = array_map(function ($k) {
            return '#' . $k . '#Uis';
        }, $keys);

        return preg_replace($keys, $values, htmlspecialchars_decode($text));
    }
}
