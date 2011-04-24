<?php
/**
 * Interprets configurations with explicit factory class declarations.
 *
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Factory_Parser_Factory implements Xi_Factory_Parser_Interface
{
    public function isValidConfig($config)
    {
        if (!isset($config['factory']) || !is_string($config['factory'])) {
            return false;
        }
        if (class_exists($config['factory'])) {
            return true;
        }
        throw new Xi_Factory_Exception('Class '.$config['factory'].' does not exist and cannot be used as a factory');
    }

    public function fromConfig($config)
    {
        $factory = $config['factory'];
        $factory = new $factory($config);
        if (!$factory instanceof Xi_Factory_Interface) {
            throw new Xi_Factory_Exception('Class '.$config['factory'].' is not an instance of Xi_Factory_Interface');
        }
        return $factory;
    }
}

