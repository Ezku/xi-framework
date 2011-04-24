<?php
/**
 * Validates a Zend_Controller_Request_Abstract object based on module,
 * controller, action and/or other parameters
 * 
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Validate_Request extends Xi_Validate_Abstract
{
    /**
     * @var null|Zend_Validate_Interface
     */
    protected $_module;
    
    /**
     * @var null|Zend_Validate_Interface
     */
    protected $_controller;
    
    /**
     * @var null|Zend_Validate_Interface
     */
    protected $_action;
    
    /**
     * @var array<Zend_Validate_Interface>
     */
    protected $_params = array();
    
    /**
     * @param array|string|Zend_Validate_Interface $module
     * @param array|string|Zend_Validate_Interface $controller
     * @param array|string|Zend_Validate_Interface $action
     * @param array<array|string|Zend_Validate_Interface> $params
     * @return void
     */
    public function __construct($module = null, $controller = null, $action = null, $params = array())
    {
        if (null !== $module) {
            $this->_module = $this->conditionToValidator($module);
        }
        if (null !== $controller) {
            $this->_controller = $this->conditionToValidator($controller);
        }
        if (null !== $action) {
            $this->_action = $this->conditionToValidator($action);
        }
        
        foreach ($params as &$param) {
            if (!$param instanceof Zend_Validate_Interface) {
                $param = new Zend_Validate_Identical($param);
            }
        }
        $this->_params = $params;
    }
    
    /**
     * @param string|array|Zend_Validate_Interface $condition
     * @return Zend_Validate_Interface
     */
    public function conditionToValidator($condition)
    {
        if ($condition instanceof Zend_Validate_Interface) {
            return $condition;
        }
        
        if (!is_array($condition)) {
            return new Zend_Validate_Identical($condition);
        }
        
        foreach ($condition as &$option) {
            $option = $this->conditionToValidator($option);
        }
        return new Xi_Validate_Or($condition);
    }
    
    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return boolean
     */
    public function isValid($request)
    {
        if (!$request instanceof Zend_Controller_Request_Abstract) {
            return false;
        }
        
        if (null !== $this->_module && !($this->_module->isValid($request->getModuleName()))) {
            return false;
        }
        
        if (null !== $this->_controller && !($this->_controller->isValid($request->getControllerName()))) {
            return false;
        }
        
        if (null !== $this->_action && !($this->_action->isValid($request->getActionName()))) {
            return false;
        }
        
        foreach ($this->_params as $key => $param) {
            if (!$param->isValid($request->getParam($key))) {
                return false;
            }
        }
        
        return true;
    }
}