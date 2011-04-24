<?php
/**
 * Describes an object that can provide a Zend_Config instance
 * 
 * @category    Xi
 * @package     Xi_Config
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
interface Xi_Config_Aggregate
{
    /**
     * @return Zend_Config
     */
    public function getConfig();
}
