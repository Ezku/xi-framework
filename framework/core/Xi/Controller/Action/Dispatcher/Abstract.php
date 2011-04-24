<?php
/**
 * Defines a Strategy for dispatching an action on a controller
 *
 * @category    Xi
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_Action
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
abstract class Xi_Controller_Action_Dispatcher_Abstract
{
    /**
     * @var array list of dispatching stages
     */
    protected $_stages = array();

    /**
     * @var Xi_Controller_Action
     */
    protected $_controller;

    /**
     * @var array
     */
    protected $_methods = array();

    /**
     * If halt is true, hasNext() will return false
     *
     * @var boolean
     */
    protected $_halt = false;

    /**
     * Dispatcher status (return value from last stage) during {@link
     * dispatch()}
     *
     * @var mixed
     */
    protected $_status = null;

    /**
     * @param Xi_Controller_Action
     * @return void
     */
    public function __construct(Xi_Controller_Action $controller)
    {
        $this->_controller = $controller;
        $this->_methods    = array_flip(get_class_methods($this));

        $this->init();
    }

    /**
     * Template method ran on dispatcher construction
     *
     * @return void
     */
    public function init()
    {
    }

    /**
     * Run dispatcher: execute all stages
     *
     * @return mixed status
     */
    public function dispatch()
    {
        $this->firstStage();

        $this->preDispatch();
        while ($this->hasStage()) {
            $stage  = $this->getStage();
            $this->runStage($stage);
            $this->nextStage();
        }
        $this->postDispatch();

        return $this->getStatus();
    }

    /**
     * Execute stage.
     *
     * @param string stage name
     * @return null|mixed stage status
     */
    public function runStage($stage)
    {
        $status = $this->getStatus();

        /**
         * Skip execution if halted
         */
        if ($this->_halt) {
            return $status;
        }

        $method = $this->getStageMethod($stage);

        if (!isset($this->_methods[$method])) {
            $message = sprintf('Method %s could not be found for stage %s', $method, $stage);
            throw new Xi_Controller_Action_Dispatcher_Exception($message);
        }

        $this->preStage();

        /**
         * Skip stage execution if halted in pre-stage
         */
        if (!$this->_halt) {
            $status = $this->$method($status);
            $this->setStatus($status);
        }

        $this->postStage();

        return $status;
    }

    /**
     * Get method name for stage
     *
     * @param string stage name
     * @return string
     */
    public function getStageMethod($stage)
    {
        $method = 'execute' . ucfirst($stage);
        return $method;
    }

    /**
     * Template method ran before any stage is run
     *
     * @return void
     */
    public function preDispatch()
    {}

    /**
     * Template method ran after all stages
     *
     * @return void
     */
    public function postDispatch()
    {}

    /**
     * Template method ran before execution of a stage
     *
     * @return void
     */
    public function preStage()
    {
    }

    /**
     * Template method ran after execution of a stage
     *
     * @return void
     */
    public function postStage()
    {
    }

    /**
     * @param string
     * @return Xi_Controller_Action_Dispatcher_Abstract
     */
    public function setStatus($status)
    {
        $this->_status = $status;
        return $this;
    }

    /**
     * Set next() not to provide any more stages
     *
     * @return Xi_Controller_Action_Dispatcher_Abstract
     */
    public function halt()
    {
        $this->_halt = true;
        return $this;
    }

    /**
     * @return boolean
     */
    public function hasStage()
    {
        if ($this->_halt) {
            return false;
        }
        return (boolean) current($this->_stages);
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * Proceed to the next stage
     *
     * @return Xi_Controller_Action_Dispatcher_Abstract
     */
    public function nextStage()
    {
        if ($this->hasStage()) {
            next($this->_stages);
        }
        return $this;
    }

    /**
     * Go to the first stage
     *
     * @return Xi_Controller_Action_Dispatcher_Abstract
     */
    public function firstStage()
    {
        reset($this->_stages);
        $this->setStatus(null);
        return $this;
    }

    /**
     * @param array stages
     * @return Xi_Controller_Action_Dispatcher_Abstract
     */
    public function setStages(array $stages)
    {
        $this->_stages = $stages;
        reset($this->_stages);
        $this->_halt = false;
        return $this;
    }

    /**
     * @return array stages
     */
    public function getStages()
    {
        return $this->_stages;
    }

    /**
     * Get current stage or false if no stages left
     *
     * @return false|string
     */
    public function getStage()
    {
        return current($this->_stages);
    }

    /**
     * @param Xi_Controller_Action
     * @return Xi_Controller_Action_Dispatcher_Abstract
     */
    public function setController(Xi_Controller_Action $controller)
    {
        $this->_controller = $controller;
        return $this;
    }

    /**
     * @return string action name
     */
    public function getAction()
    {
        return $this->_controller->getRequest()->getActionName();
    }

    /**
     * @return Xi_Controller_Action
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     * Get request object associated with controller
     *
     * @return Xi_Controller_Request
     */
    public function getRequest()
    {
        return $this->_controller->getRequest();
    }

    /**
     * Get helper broker object associated with controller
     *
     * @return Zend_Controller_Action_HelperBroker
     */
    public function getHelperBroker()
    {
        return $this->_controller->getHelperBroker();
    }
}

