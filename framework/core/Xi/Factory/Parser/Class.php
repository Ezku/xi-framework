<?php
/**
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Factory_Parser_Class implements Xi_Factory_Parser_Interface
{
    public function fromConfig($config)
    {
        return new Xi_Factory_Class($config);
    }

    public function isValidConfig($config)
    {
        return isset($config['class']) && is_string($config['class']);
    }
}
