<?php
/**
 * @category    Xi
 * @package     Xi_Acl
 * @subpackage  Xi_Acl_Assert
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Acl_Assert_Validate extends Xi_Validate_Outer implements Zend_Acl_Assert_Interface
{
    /**
     * Delegates the assertion to a validator
     * 
     * @param Zend_Acl $acl
     * @param Zend_Acl_Role_Interface $role
     * @param Zend_Acl_Resource_Interface $resource
     * @param string $privilege
     * @return boolean
     */
    public function assert(Zend_Acl $acl, Zend_Acl_Role_Interface $role = null, Zend_Acl_Resource_Interface $resource = null, $privilege = null)
    {
        return $this->isValid(array(
            'acl' => $acl,
            'role' => $role,
            'resource' => $resource,
            'privilege' => $privilege
        ));
    }
}
