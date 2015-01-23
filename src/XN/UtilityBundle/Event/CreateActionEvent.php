<?php
namespace XN\UtilityBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class CreateActionEvent extends Event
{
    /**
     * @var string
     */
    protected $name = null;

    /**
     * @var array
     */
    protected $context = array();

    /**
    * @var boolean
    */
    protected $stopSession = array();

    public function __construct($name, $context = array(), $stopSession = false) {
        if(!strval($name))
            throw new \LogicException('An action\'s name must be a string and must be non-empty.');
        $this->name = strval($name);
        $this->context = (array) $context;
        $this->stopSession = false;
    }
    public function setName($name)
    {
        $this->name = strval($name);
    }

    public function getName()
    {
        return $this->name;
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
