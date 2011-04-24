<?php
/**
 * Describes an object containing information on an Acl action and able to
 * check an Acl object for whether the action can be performed
 * 
 * @category    Xi
 * @package     Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
interface Xi_Acl_Action_Interface
{
    /**
     * @param string|null $role
     * @return Xi_Acl_Action_Interface
     */
    public function setRole($role);
    
    /**
     * @param string|null $resource
     * @return Xi_Acl_Action_Interface
     */
    public function setResource($resource);
    
    /**
     * @param string|null $privilege
     * @return Xi_Acl_Action_Interface
     */
    public function setPrivilege($privilege);
    
    /**
     * @param array $params
     * @return Xi_Acl_Action_Interface
     */
    public function setParams($params);
    
    /**
     * @return string|null
     */
    public function getRole();
    
    /**
     * @return string|null
     */
    public function getResource();
    
    /**
     * @return string|null
     */
    public function getPrivilege();
    
    /**
     * @return array
     */
    public function getParams();
    
    /**
     * Check whether action is valid against an Acl
     *
     * @param Zend_Acl $acl
     * @return boolean
     */
    public function isAllowed(Zend_Acl $acl);
}
