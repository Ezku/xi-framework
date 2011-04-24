<?php
/**
 * Basic Xi_Acl_Action_Interface implementation
 * 
 * @category    Xi
 * @package     Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Acl_Action implements Xi_Acl_Action_Interface 
{
    /**
     * @var string
     */
    protected $_role;
    
    /**
     * @var string
     */
    protected $_resource;
    
    /**
     * @var string
     */
    protected $_privilege;
    
    /**
     * @var array
     */
    protected $_params;
    
    /**
     * @param string $privilege
     * @param string $resource
     * @param string $role
     * @param array $params
     */
    public function __construct($privilege = null, $resource = null, $role = null, $params = array())
    {
        if (null !== $role) {
            $this->setRole($role);
        }
        
        if (null !== $resource) {
            $this->setResource($resource);
        }
        
        if (null !== $privilege) {
            $this->setPrivilege($privilege);
        }
        
        if (!empty($params)) {
            $this->setParams($params);
        }
    }
    
	/**
	 * Create Acl action based on request parameters
	 * 
	 * @param Zend_Controller_Request_Abstract $request
	 * @return Xi_Acl_Action_Interface
	 */
    public static function createFromRequest(Zend_Controller_Request_Abstract $request)
    {
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        
        return new self($action, $module . '.' . $controller, null, $request->getParams());
    }
    
    /**
     * @param string $role
     * @return Xi_Acl_Action
     */
    public function setRole($role)
    {
        $this->_role = $role;
        return $this;
    }
    
    /**
     * @param string $resource
     * @return Xi_Acl_Action
     */
    public function setResource($resource)
    {
        $this->_resource = $resource;
        return $this;
    }
    
    /**
     * @param string $privilege
     * @return Xi_Acl_Action
     */
    public function setPrivilege($privilege)
    {
        $this->_privilege = $privilege;
        return $this;
    }
    
    /**
     * @param array $params
     * @return Xi_Acl_Action
     */
    public function setParams($params)
    {
        $this->_params = $params;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getRole()
    {
        return $this->_role;
    }
    
    /**
     * @return string|null
     */
    public function getResource()
    {
        return $this->_resource;
    }
    
    /**
     * @return string|null
     */
    public function getPrivilege()
    {
        return $this->_privilege;
    }
    
    /**
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }
    
    /**
     * Check whether action is valid against an Acl
     *
     * @param Zend_Acl $acl
     * @return boolean
     */
    public function isAllowed(Zend_Acl $acl)
    {
        return $acl->isAllowed($this->getRole(), $this->getResource(), $this->getPrivilege(), $this->getParams());
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getRole() . '.' . $this->getPrivilege() . '.' . $this->getResource();
    }
}
