<?php
/**
 * Provides basic redirection functionality
 * 
 * @category    Xi
 * @package     Xi_Controller
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Controller_Request_Redirection implements Xi_Controller_Request_Redirection_Interface
{
    /**
     * Target module name or null if no change
     *
     * @var string|null
     */
    protected $_module;
    
    /**
     * Target controller name or null if no change
     *
     * @var string|null
     */
    protected $_controller;
    
    /**
     * Target action name or null if no change
     *
     * @var string|null
     */
    protected $_action;
    
    /**
     * Provide default values for target action, controller and module
     * 
     * @param string $action
     * @param string $controller
     * @param string $module
     */
    public function __construct($action = null, $controller = null, $module = null)
    {
        if (null !== $action) {
            $this->setAction($action);
        }
        if (null !== $controller) {
            $this->setController($controller);
        }
        if (null !== $module) {
            $this->setModule($module);
        }
    }
    
    /**
     * Create a redirection based on values in a request
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return Xi_Controller_Request_Redirection
     */
    public static function createFromRequest(Zend_Controller_Request_Abstract $request)
    {
        return new self($request->getActionName(), $request->getControllerName(), $request->getModuleName());
    }
    
    public function setModule($module)
    {
        $this->_module = $module;
        return $this;
    }
    
    public function setController($controller)
    {
        $this->_controller = $controller;
        return $this;
    }
    
    public function setAction($action)
    {
        $this->_action = $action;
        return $this;
    }
    
    public function getModule()
    {
        return $this->_module;
    }
    
    public function getController()
    {
        return $this->_controller;
    }
    
    public function getAction()
    {
        return $this->_action;
    }
    
    /**
     * Set request's module, controller and action to the values provided, and
     * set the request as not dispatched if any of them differ from the
     * existing values.
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function apply(Zend_Controller_Request_Abstract $request)
    {
        $changed = false;
        
        if (($action = $this->getAction()) && ($action != $request->getActionName())) {
            $request->setActionName($action);
            $changed = true;
        }
        
        if (($controller = $this->getController()) && ($controller != $request->getControllerName())) {
            $request->setControllerName($controller);
            $changed = true;
        }
        
        if (($module = $this->getModule()) && ($module != $request->getModuleName())) {
            $request->setModuleName($module);
            $changed = true;
        }
        
        if ($changed) {
            $request->setDispatched(false);
        }
    }
}
