<?php
/**
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Factory_Parser_Callback implements Xi_Factory_Parser_Interface
{
    public function fromConfig($config)
    {
        return new Xi_Factory_Callback($config);
    }

    public function isValidConfig($config)
    {
        return isset($config['callback']);
    }
}
