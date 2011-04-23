<?php
interface Xi_Acl_Builder_Privilege_Operation_Interface
{
    /**
     * Add privilege to Zend_Acl instance
     *
     * @param Zend_Acl $acl
     * @param string $role
     * @param string $resource
     * @param string $privilege
     * @param Zend_Acl_Assert_Interface $assert
     */
    public function addPrivilege($acl, $role, $resource, $privilege, Zend_Acl_Assert_Interface $assert = null);
}
