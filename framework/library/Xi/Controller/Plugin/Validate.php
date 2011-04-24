<?php
/**
 * Only provide contextual events to the inner plugin if the request is valid
 * 
 * @category    Xi
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_Plugin
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Controller_Plugin_Validate extends Xi_Controller_Plugin_Outer implements Xi_Validate_Aggregate
{
    /**
     * @var Zend_Validate_Interface
     */
    protected $_validator;
    
    /**
     * @param Zend_Controller_Plugin_Abstract $plugin
     * @param Zend_Validate_Interface $validator
     * @return void
     */
    public function __construct(Zend_Controller_Plugin_Abstract $plugin, Zend_Validate_Interface $validator)
    {
        parent::__construct($plugin);
        $this->_validator = $validator;
    }
    
    /**
     * @return Zend_Validate_Interface $validator
     */
    public function getValidator()
    {
        return $this->_validator;
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function dispatchLoopStartup($request)
    {
        if (!$this->isValid($request)) {
            return;
        }
        return parent::dispatchLoopStartup($request);
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function postDispatch($request)
    {
        if (!$this->isValid($request)) {
            return;
        }
        return parent::postDispatch($request);
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function preDispatch($request)
    {
        if (!$this->isValid($request)) {
            return;
        }
        return parent::preDispatch($request);
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function routeShutdown($request)
    {
        if (!$this->isValid($request)) {
            return;
        }
        return parent::routeShutdown($request);
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function routeStartup($request)
    {
        if (!$this->isValid($request)) {
            return;
        }
        return parent::routeStartup($request);
    }
    
}
