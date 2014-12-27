<?php
namespace XN\Common;

class VariableBag
{
    private $data;
	private $lazy;

    public function __construct(array $data = [ ])
	{
        $this->data = $data;
		$this->lazy = [ ];
    }

    public function get($key)
	{
		if (isset($this->lazy[$key])) {
			$this->data[$key] = call_user_func($this->lazy[$key]);
			unset($this->lazy[$key]);
		}

        if (!isset($this->data[$key]))
            throw new \LogicException(sprintf('The variable "%s" must be defined.', $key));

        return $this->data[$key];
    }

    public function set($key, $value)
	{
        $this->data[$key] = $value;
		unset($this->lazy[$key]);
        return $this;
    }

	public function setLazy($key, $lazyValue, $service = null, $method = null)
	{
		if (isset($service)) {
			$container = $lazyValue;
			if (isset($method))
				$lazyValue = function () use ($container, $service, $method) {
					return call_user_func($container->get($service), $method);
				};
			else
				$lazyValue = function () use ($container, $service) {
					return $container->get($service);
				};
		} elseif (isset($method))
			$lazyValue = [ $lazyValue, $method ];

		if (!is_callable($lazyValue))
			throw new \LogicException('A "lazy value" must be a callable.');

		$this->lazy[$key] = $lazyValue;
		unset($this->data[$key]);
		return $this;
	}

    public function has($key)
	{
        return isset($this->data[$key]) || isset($this->lazy[$key]);
    }

	public function remove($key)
	{
		unset($this->data[$key]);
		unset($this->lazy[$key]);
		return $this;
	}

	public function toArray()
	{
		if (!empty($this->lazy)) {
			foreach ($this->lazy as $key => $lazyValue)
				$this->data[$key] = call_user_func($lazyValue);
			$this->lazy = [ ];
		}
		return $this->data;
	}
}
