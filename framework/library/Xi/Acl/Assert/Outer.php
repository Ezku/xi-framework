<?php
/**
 * @category    Xi
 * @package     Xi_Acl
 * @subpackage  Xi_Acl_Assert
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Acl_Assert_Outer implements Zend_Acl_Assert_Interface
{
    /**
     * @var Zend_Acl_Assert_Interface
     */
    protected $_assert;
    
    /**
     * @param Zend_Acl_Assert_Interface
     * @return void
     */
    public function __construct($assert)
    {
        $this->_assert = $assert;
    }
    
    /**
     * @return Zend_Acl_Assert_Interface
     */
    public function getAssert()
    {
        return $this->_assert;
    }
    
    /**
     * Delegate to inner assertion
     * 
     * @param Zend_Acl $acl
     * @param Zend_Acl_Role_Interface $role
     * @param Zend_Acl_Resource_Interface $resource
     * @param string $privilege
     * @return boolean
     */
    public function assert(Zend_Acl $acl, Zend_Acl_Role_Interface $role = null, Zend_Acl_Resource_Interface $resource = null, $privilege = null)
    {
        return $this->getAssert()->assert($acl, $role, $resource, $privilege);
    }
}
