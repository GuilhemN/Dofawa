<?php
namespace XN\UtilityBundle;

use Symfony\Component\HttpFoundation\Session\Session;

class ActionLogger
{
    const PRESERVED_ACTIONS = 20;

    private $session;

    private $actions;

    public function __construct(Session $session) {
        $this->session = $session;
        if($this->session->has('last_actions'))
            $this->actions = $this->session->get('last_actions');
        else
            $this->actions = [];
    }

    public function set($key, array $context = array()) {
        $this->actions[$key] = [ 'time' => time(), 'context' => $context ];
        $this->sort();
        if (count($this->actions) > self::PRESERVED_ACTIONS)
            array_splice($this->actions, 0, count($this->actions) - self::PRESERVED_ACTIONS);

        $this->session->set('last_actions', $this->actions);
        return $this;
    }

    private function sort() {
        uasort($this->actions, function($a, $b){
            if($a['time'] == $b['time'])
                return 0;
            return $a['time'] > $b['time'] ? -1 : 1;
        });
    }
}
