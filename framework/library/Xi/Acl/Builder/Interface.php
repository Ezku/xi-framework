<?php
/**
 * @category    Xi
 * @package     Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
interface Xi_Acl_Builder_Interface
{
    /**
     * Set the Acl template object
     * 
     * @param Zend_Acl $acl
     * @return Xi_Acl_Builder_Abstract
     */
    public function setAcl(Zend_Acl $acl);
    
    /**
     * Retrieve the Acl template object
     *
     * @return Zend_Acl
     */
    public function getAcl();
    
    /**
     * Build an Acl object according to configuration
     * 
     * @param Zend_Config $config
     * @return Zend_Acl
     */
    public function build(Zend_Config $config);
}
