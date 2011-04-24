<?php
/**
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Factory_Parser_Registry implements Xi_Factory_Parser_Interface
{
    public function fromConfig($config)
    {
        return new Xi_Factory_Registry($config);
    }

    public function isValidConfig($config)
    {
        return isset($config['registry']);
    }
}
