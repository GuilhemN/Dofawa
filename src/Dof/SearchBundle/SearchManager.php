<?php
namespace Dof\SearchBundle;

use Dof\SearchBundle\Intent\IntentInterface;

class SearchManager
{
    CONST KEY = 'DQCNE665II22LYW3SA7TTZ7GWR7NXLBI';

    private $indents;

    public function __construct() {
        $this->indents = [];
    }

    public function addIntent($intent, IntentInterface $service) {
        $this->indents[$intent] = $service;
    }

    public function process($string) {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Authorization: Bearer " . self::KEY . "\r\n" .
                    "Accept: application/vnd.wit.20140401+json\r\n" .
                    "Accept: application/json\r\n"
                ]
            ]);
        $answer = json_decode(
            file_get_contents('https://api.wit.ai/message?q=' . urlencode($string), false, $context)
        , true);
        $intent = $answer['outcomes'][0]['intent'];

        if(!isset($this->indents[$intent]))
            return $this->notUnderstood();

        return $this->indents[$intent]->process($answer['outcomes'][0]['entities'], $intent);
    }

    public function notUnderstood() {

    }
}
