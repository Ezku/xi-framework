<?php
/**
 * @category    Xi
 * @package     Xi_Environment
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Environment
{
    const ENV_DEVELOPMENT = 'dev';
    const ENV_PRODUCTION  = 'prod';
    const ENV_TESTING     = 'test';

    /**
     * Current environment
     *
     * @var string
     */
    protected static $_environment = self::ENV_DEVELOPMENT;

    /**
     * Set environment
     *
     * @param string new environment
     * @return string environment
     */
    public static function set($new)
    {
        return self::$_environment = $new;
    }

    /**
     * Get environment
     *
     * @return string
     */
    public static function get()
    {
        return self::$_environment;
    }

    /**
     * Check whether environment is set to one of the arguments provided
     *
     * @param string environment
     * ...
     * @return boolean true if current environment matches any parameter
     */
    public static function is($env)
    {
        if (func_num_args() > 1) {
            foreach (func_get_args() as $arg) {
                if (self::$_environment === $arg) {
                    return true;
                }
            }
            return false;
        }
        return self::$_environment == $env;
    }

    /**
     * @return string
     */
    public static function getFrameworkDirectory()
    {
        return dirname(dirname(dirname(__FILE__)));
    }
}

