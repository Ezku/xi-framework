<?php
/**
 * @category    Xi
 * @package     Xi_Class
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Class
{
    /**
     * Given an object and a list of method names, return the value of the first
     * method that can be found on the object
     *
     * @param object
     * @param array methods to try
     * @param boolean throw exception on failure
     * @return mixed method return value
     * @throws Xi_Exception
     */
    public static function tryMethods($object, array $methods, array $args = array(), $throwException = true)
    {
        foreach ((array) $methods as $method) {
            if (method_exists($object, $method)) {
                return call_user_func_array(array($object, $method), $args);
            }
        }

        if ($throwException) {
            $message = 'None of the methods [' . implode(', ', $methods) . '] were found in ' . get_class($object);
            throw new Xi_Class_Exception($message);
        }
    }

    /**
     * Create a new instance of a class.
     *
     * @param string class name
     * @param array constructor arguments, optional
     * @return object
     * @throws Xi_Exception if class could not be loaded
     */
    public static function create($class, array $args = array())
    {
        if (!is_string($class)) {
            throw new Xi_Class_Exception('Class name must be a string.');
        }

        if (!class_exists($class)) {
            throw new Xi_Class_Exception('Cannot instantiate class ' . $class . ' because it is not defined');
        }

        /**
         * An arbitrary amount of constructor arguments can be achieved using
         * reflection, but it's slower by an order of magnitude. Manually handle
         * instantiation for up to three arguments.
         */
        switch (count($args)) {
            case 0:
                return new $class;
            case 1:
                list($one) = $args;
                return new $class($one);
            case 2:
                list($one, $two) = $args;
                return new $class($one, $two);
            case 3:
                list($one, $two, $three) = $args;
                return new $class($one, $two, $three);
            default:
                return call_user_func_array(array(new ReflectionClass($class),'newInstance'),
                                            $args);
        }
    }

    /**
     * Get the class or the type of a variable, whichever applies
     *
     * @param mixed
     * @return string
     */
    public static function getType($var)
    {
        return is_object($var) ? get_class($var) : gettype($var);
    }
    
    /**
     * Check whether a class implements an interface
     * 
     * @param string $class
     * @param string $interface
     * @return boolean
     */
    public static function implementsInterface($class, $interface)
    {
        $reflection = new ReflectionClass($class);
        return $reflection->implementsInterface($interface);
    }
}

