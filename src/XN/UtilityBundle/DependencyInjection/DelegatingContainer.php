<?php

namespace XN\UtilityBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception as Exceptions;

class DelegatingContainer extends Container
{
    public function has($id)
    {
        if (parent::has($id)) {
            return true;
        }

        if (false !== ($pos = strpos($id, '\\'))) {
            // Proceed with a delegate container
            if ($this->has($delegateId = substr($id, 0, $pos))
                && (!method_exists($delegate = $this->get($delegateId), 'has')
                    || $delegate->has(substr($id, $pos + 1)))) {
                return true;
            }
        }

        return false;
    }

    public function get($id, $invalidBehavior = self::EXCEPTION_ON_INVALID_REFERENCE)
    {
        // Attempt to retrieve the service by checking first aliases then
        // available services. Service IDs are case insensitive, however since
        // this method can be called thousands of times during a request, avoid
        // calling strtolower() unless necessary.
        foreach (array(false, true) as $strtolower) {
            if ($strtolower) {
                $id = strtolower($id);
            }
            if ('service_container' === $id) {
                return $this;
            }
            if (isset($this->aliases[$id])) {
                $id = $this->aliases[$id];
            }
            // Re-use shared service instance if it exists.
            if (isset($this->services[$id]) || array_key_exists($id, $this->services)) {
                return $this->services[$id];
            }
        }

        if (isset($this->loading[$id])) {
            throw new Exceptions\ServiceCircularReferenceException($id, array_keys($this->loading));
        }

        if (isset($this->methodMap[$id])) {
            $method = $this->methodMap[$id];
        } elseif (method_exists($this, $method = 'get'.strtr($id, array('_' => '', '.' => '_', '\\' => '_')).'Service')) {
            // $method is set to the right value, proceed
        } elseif (false !== ($pos = strpos($id, '\\'))) {
            // Proceed with a delegate container
            return $this->get(substr($id, 0, $pos), $invalidBehavior)->get(substr($id, $pos + 1), $invalidBehavior);
        } else {
            if (self::EXCEPTION_ON_INVALID_REFERENCE === $invalidBehavior) {
                if (!$id) {
                    throw new Exceptions\ServiceNotFoundException($id);
                }

                $alternatives = array();
                foreach (array_keys($this->services) as $key) {
                    $lev = levenshtein($id, $key);
                    if ($lev <= strlen($id) / 3 || false !== strpos($key, $id)) {
                        $alternatives[] = $key;
                    }
                }

                throw new Exceptions\ServiceNotFoundException($id, null, null, $alternatives);
            }

            return;
        }

        $this->loading[$id] = true;

        try {
            $service = $this->$method();
        } catch (\Exception $e) {
            unset($this->loading[$id]);

            if (array_key_exists($id, $this->services)) {
                unset($this->services[$id]);
            }

            if ($e instanceof Exceptions\InactiveScopeException && self::EXCEPTION_ON_INVALID_REFERENCE !== $invalidBehavior) {
                return;
            }

            throw $e;
        }

        unset($this->loading[$id]);

        return $service;
    }
}
