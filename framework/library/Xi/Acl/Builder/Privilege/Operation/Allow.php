<?php
class Xi_Acl_Builder_Privilege_Operation_Allow implements Xi_Acl_Builder_Privilege_Operation_Interface
{
    /**
     * @param Zend_Acl $acl
     * @param string $role
     * @param string $resource
     * @param string $privilege
     * @param Zend_Acl_Assert_Interface $assert
     */
    public function addPrivilege($acl, $role, $resource, $privilege, Zend_Acl_Assert_Interface $assert = null)
    {
        $acl->allow($role, $resource, $privilege, $assert);
    }
}
