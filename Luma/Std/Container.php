<?php

namespace Luma\Std;

use \ArrayAccess;
use \Iterator;

class Container implements ArrayAccess, Iterator
{
    protected $container = array();

    public function __construct(array $container)
    {
        foreach ($container as $offset => $value) {
            $this->__set($offset, $value);
        }
    }

    public function __get($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    public function __set($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = is_array($value) ? new static($value) : $value;
        } else {
            $this->container[$offset] = is_array($value) ? new static($value) : $value;
        }
    }
    
    public function get($offset)
    {
        return $this->__get($offset);
    }

    public function set($offset, $value)
    {
        $this->__set($offset, $value);
    }

    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);
    }
    
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }
    
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    public function rewind()
    {
        reset($this->container);
    }
    
    public function valid()
    {
        return isset($this->key());
    }

    public function key()
    {
        return key($this->container);
    }
    
    public function next()
    {
        next($this->container);
    }

    public function current()
    {
        return current($this->container);
    }
    
    public function toArray()
    {
        $containerArray = array();

        foreach ($this as $key => $value) {
            if ($value instanceof static) {
                $containerArray = $value->toArray();
            } else {
                $containerArray = $value;
            }
        }
        return $containerArray;
    }
    
    public function mergeWith($container)
    {
        return static::merge($this, $container);
    }

    public static function merge(Traversable $container1, Traversable $container2)
    {
        foreach ($container2 as $key => $value) {
            if (
                (is_array($value) || $value instanceof static)
                && array_key_exists($key, $container1)
                && (is_array($container1[$key]) || $container1[$key] instanceof static)
            ) {
                $container1[$key] = static::merge($container1[$key], $value);
            } else {
                $container1[$key] = $value;
            }
        }
        return $container1;
    }
}
