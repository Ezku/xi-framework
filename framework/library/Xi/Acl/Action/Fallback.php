<?php
/**
 * Assumes that a resource prefixed with another resource and an inheritance
 * separator character inherits said resource. If an unknown resource is
 * encountered, will fall back to the parent resource until a resource is found
 * or there is no parent resource, in which case a null value will be used.
 * 
 * @category    Xi
 * @package     Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Acl_Action_Fallback extends Xi_Acl_Action
{
    /**
     * Separator character for Acl resources
     */
    const INHERITANCE_SEPARATOR = '.';
    
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
     * Check whether action is valid against an Acl
     *
     * @param Zend_Acl $acl
     * @return boolean
     */
    public function isAllowed(Zend_Acl $acl)
    {
        $resource = $this->getResource();
        while (!$acl->has($resource)) {
            $pos = strrpos($resource, self::INHERITANCE_SEPARATOR);
            if (!$pos) {
                $resource = null;
                break;
            }
            $resource = substr($resource, 0, $pos);
        }
        return $acl->isAllowed($this->getRole(), $resource, $this->getPrivilege(), $this->getParams());
    }
}