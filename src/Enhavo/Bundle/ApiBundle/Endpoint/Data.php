<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 19:02
 */

namespace Enhavo\Bundle\ApiBundle\Endpoint;

class Data implements \IteratorAggregate, \Countable, \ArrayAccess
{
    /**
     * Parameter storage.
     */
    protected $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Returns the parameter keys.
     *
     * @return array An array of parameter keys
     */
    public function keys()
    {
        return array_keys($this->data);
    }

    /**
     * Replaces the current parameters by a new set.
     */
    public function replace(array $parameters = [])
    {
        $this->data = $parameters;
    }

    /**
     * Adds parameters.
     */
    public function add(array $parameters = [])
    {
        $this->data = array_replace($this->data, $parameters);
    }

    /**
     * Returns a parameter by name.
     *
     * @param string $key     The key
     * @param mixed  $default The default value if the parameter key does not exist
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return \array_key_exists($key, $this->data) ? $this->data[$key] : $default;
    }

    /**
     * Sets a parameter by name.
     *
     * @param string $key   The key
     * @param mixed  $value The value
     */
    public function set($key, $value)
    {
        if (!is_array($value) && !is_scalar($value)) {
            throw new \Exception(sprintf('Data value must be of type array or scalar. "%s" given.', gettype($value)));
        }

        $this->data[$key] = $value;
    }

    /**
     * Returns true if the parameter is defined.
     *
     * @param string $key The key
     *
     * @return bool true if the parameter exists, false otherwise
     */
    public function has($key)
    {
        return \array_key_exists($key, $this->data);
    }

    /**
     * Removes a parameter.
     *
     * @param string $key The key
     */
    public function remove($key)
    {
        unset($this->data[$key]);
    }

    public function normalize(): array
    {
        return $this->data;
    }

    /**
     * Returns an iterator for parameters.
     *
     * @return \ArrayIterator An \ArrayIterator instance
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * Returns the number of parameters.
     *
     * @return int The number of parameters
     */
    public function count()
    {
        return \count($this->data);
    }

    public function offsetSet($offset, $value)
    {
        if (!is_array($value) && !is_scalar($value)) {
            throw new \Exception(sprintf('Data value must be of type array or scalar. "%s" given.', gettype($value)));
        }

        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }
}
