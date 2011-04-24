<?php
/**
 * @category    Xi
 * @package     Xi_User
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_User_View_Helper_IsAllowed extends Xi_User_View_Helper_Abstract
{
    /**
     * Get request object
     *
     * @return Zend_Controller_Request_Abstract
     */
    public function getRequest()
    {
        return Zend_Controller_Front::getInstance()->getRequest();
    }
    
    /**
     * Check whether user is allowed to perform action. Action, controller and
     * module default to current request values if not provided.
     *
     * @param string $action
     * @param string $controller
     * @param string $module
     * @param array $params
     * @return boolean
     */
    public function isAllowed($action = null, $controller = null, $module = null, $params = array())
    {
        $request = $this->getRequest(); 
        
        if (null === $module) {
            $module = $request->getModuleName();
        }
        if (null === $controller) {
            $controller = $request->getControllerName();
        }
        if (null === $action) {
            $action = $request->getActionName();
        }
        
        return $this->getUser()->isAllowed($this->getAclAction($action, $controller, $module, $params));
    }
    
    /**
     * Get Xi_Acl_Action_Interface object based on action, controller and
     * module names
     * 
     * @param string $action
     * @param string $controller
     * @param string $module
     * @param array $params
     * @return Xi_Acl_Action_Interface
     */
    public function getAclAction($action, $controller, $module, $params)
    {
        return new Xi_Acl_Action_Fallback($action, $module . '.' . $controller, null, $params);
    }
}
