<?php
/**
 * @category    Xi
 * @package     Xi_Controller
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Controller_Component
{
    /**
     * @var Xi_Controller_Action
     */
    protected $_controller;

    /**
     * Engine object
     *
     * @var object
     */
    protected $_engine;

    /**
     * Location of engine object in the locator
     *
     * @var string
     */
    protected $_engineLocation = '';

    /**
     * @param Xi_Controller_Action
     * @return void
     */
    public function __construct(Xi_Controller_Action $controller)
    {
        $this->setController($controller);
        $this->init();
    }

    /**
     * Template method called on construction
     *
     * @return void
     */
    public function init()
    {}

    /**
     * Set controller object.
     *
     * @param Xi_Controller_Action
     * @return Xi_Controller_Component
     */
    public function setController($controller)
    {
        $this->_controller = $controller;
        return $this;
    }

    /**
     * Get controller object.
     *
     * @return null|Xi_Controller_Action
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     * Set engine object
     *
     * @param object
     * @return Xi_Controller_Component
     */
    public function setEngine($engine)
    {
        $this->_engine = $engine;
        return $this;
    }

    /**
     * Retrieve engine object. Create the engine if not set.
     *
     * @return object
     */
    public function getEngine()
    {
        if (null === $this->_engine) {
            $this->_engine = $this->_createEngine();
        }
        return $this->_engine;
    }

    /**
     * Create engine object. Uses the locator to fetch an engine.
     *
     * @return object
     */
    protected function _createEngine()
    {
        if (empty($this->_engineLocation)) {
            throw new Xi_Controller_Exception('Engine location not set; unable to retrieve engine object.');
        }
        return Zend_Registry::getInstance()->getResource($this->_engineLocation, $this->_getEngineCreationArguments());
    }

    /**
     * Format engine creation arguments
     *
     * @return null|array
     */
    protected function _getEngineCreationArguments()
    {
        return null;
    }
    
    public function getRequest()
    {
        return $this->_controller->getRequest();
    }

    /**
     * Magic method
     *
     * @param string property name
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->getEngine()->$name);
    }

    /**
     * Magic method
     *
     * @param string property name
     * @return void
     */
    public function __unset($name)
    {
        unset($this->getEngine()->$name);
    }

    /**
     * Magic method
     *
     * @param string property name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->getEngine()->$name;
    }

    /**
     * Magic method
     *
     * @param string property name
     * @param mixed property value
     * @return mixed
     */
    public function __set($name, $value)
    {
        return $this->getEngine()->$name = $value;
    }

    /**
     * Magic method
     *
     * @param string method name
     * @param array arguments
     * @return mixed
     */
    public function __call($name, $args)
    {
        return call_user_func_array(array($this->getEngine(), $name), $args);
    }
}
