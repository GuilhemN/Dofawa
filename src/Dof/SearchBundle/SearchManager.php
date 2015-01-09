<?php
namespace Dof\SearchBundle;

use Dof\SearchBundle\Intent\IntentInterface;

class SearchManager
{
    private $key;
    private $intents;

    public function __construct($key) {
        $this->key = $key;
        $this->intents = [];
        die(print_r($key, true));
    }

    public function addIntent(string $intent, IntentInterface $service) {
        $this->intents[$intent] = $service;
    }

    public function process($string) {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Authorization: Bearer " . $this->key . "\r\n" .
                    "Accept: application/vnd.wit.20140401+json\r\n"
                ]
            ]);
        $answer = json_decode(
            file_get_contents('https://api.wit.ai/message?q=' . urlencode($string), false, $context)
        , true);
        $intent = $answer['outcomes'][0]['intent'];

        if(!isset($this->indents[$intent]))
            return $this->notUnderstood();

        return $this->intents[$intent]->process($answer['outcomes'][0]['entities'], $intent);
    }

    public function notUnderstood() {

    }
}
