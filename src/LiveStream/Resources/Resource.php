<?php

namespace LiveStream\Resources;

use BadMethodCallException;
use stdClass;

class Resource
{
    /**
     * Resource Payload
     *
     * @var object
     */
    protected $data;

    /**
     * Class Constructor.
     *
     * @param boolean $init
     */
    public function __construct(bool $init = true)
    {
        if ($init) $this->data = new stdClass();
    }

    /**
     * Factory Method.
     *
     * @param  object $object
     * @return \LiveStream\Resources\Resource
     */
    public static function fromObject(object $object): Resource
    {
        if ($object == null) return null;

        $instance = new static(false);
        $instance->data = $object;
        return $instance;
    }

    /**
     * Magic Setter Method
     *
     * @param  string $key
     * @param  mixed  $value
     * @return void
     */
    public function __set(string $key, $value): void
    {
        $this->data->$key = $value;
    }

    /**
     * Magic Getter Method
     *
     * @param  string $key
     * @return void
     */
    public function __get(string $key)
    {
        return $this->data->$key ?? null;
    }

    /**
     * Magic Method Isset.
     *
     * @param  string  $key
     * @return boolean
     */
    public function __isset(string $key)
    {
        return isset($this->data->$key);
    }

    /**
     * Stictly for Setters and Getter.
     *
     * @param string $name
     * @param array $arguments
     * @return void
     */
    public function __call(string $name, array $arguments)
    {
        if (substr($name, 0, 3) == 'get') {
            return $this->get_called_value($name);
        } elseif (substr($name, 0, 3) == 'set') {
            $this->set_called_value($name, $arguments[0]);
            return $this; // Method Chaining.
        }

        throw new BadMethodCallException("Function '$name' does not exist.");
    }

    /**
     * Get called value from __call by function name.
     *
     * @param  string $name
     * @param  string $function
     * @return void
     */
    private function get_called_value(string $function)
    {
        return $this->data->{lcfirst(substr($function, 3, strlen($function) - 3))} ?? null;
    }

    /**
     * Undocumented function
     *
     * @param string $function
     * @param [type] $value
     * @return void
     */
    private function set_called_value(string $function, $value): void
    {
        $this->data->{lcfirst(substr($function, 3, strlen($function) - 3))} = $value;
    }
}
