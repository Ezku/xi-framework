<?php
/**
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
interface Xi_Factory_Parser_Interface
{
    /**
     * Create new factory from configuration data
     *
     * @param Zend_Config
     * @return Xi_Factory_Interface
     */
    public function fromConfig($config);

    /**
     * Check whether configuration data is valid for factory instantiation
     *
     * @param Zend_Config
     * @return boolean
     */
    public function isValidConfig($config);
}
