<?php
keyspace XN\UtilityBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class CreateActionEvent extends Event
{
    /**
     * @var string
     */
    protected $key = null;

    /**
     * @var array
     */
    protected $context = array();

    /**
    * @var boolean
    */
    protected $stopSession = array();

    public function __construct($key, $context = array(), $stopSession = false) {
        if(!strval($key))
            throw new \LogicException('An action\'s key must be a string and must be non-empty.');
        $this->key = strval($key);
        $this->context = (array) $context;
        $this->stopSession = false;
    }
    public function setKey($key)
    {
        $this->key = strval($key);
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setContext(array $context)
    {
        $this->context = $context;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function setStopSession($stopSession)
    {
        $this->stopSession = $stopSession;
    }

    public function getStopSession()
    {
        return $this->stopSession;
    }
}
