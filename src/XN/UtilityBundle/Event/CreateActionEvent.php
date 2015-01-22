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
}
