<?php
Xi_Loader::loadClass('Zend_Controller_Action');

/**
 * @category    Xi
 * @package     Xi_Controller
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Controller_Action extends Zend_Controller_Action
{
    /**
     * @var Xi_Controller_View
     */
    protected $_view;

    /**
     * @var Xi_Controller_Model
     */
    protected $_model;

    /**
     * @var Xi_Inflector
     */
    protected $_paths;

    /**
     * @var Xi_Controller_Action_Dispatcher_Abstract
     */
    protected $_actionDispatcher;

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @param Zend_Controller_Response_Abstract $response
     * @param array $invokeArgs Any additional invocation arguments
     * @return void
     */
    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        $this->setRequest($request)
             ->setResponse($response)
             ->_setInvokeArgs($invokeArgs);

        $this->_helper = Zend_Registry::getInstance()->controller->action->helperbroker($this);

        static $created = array();
        $class = get_class($this);
        if (!isset($created[$class])) {
            $this->firstInit();
            $created[$class] = true;
        }

        $this->init();
    }

    /**
     * Template method called before init() on first initialization of class
     *
     * @return void
     */
    public function firstInit()
    {}

    /**
     * Dispatch the requested action.
     *
     * @param string action method name
     * @return void
     */
    public function dispatch($actionMethod)
    {
        $this->_helper->notifyPreDispatch();

        $dispatcher = $this->getActionDispatcher();
        $dispatcher->dispatch();

        $this->_helper->notifyPostDispatch();
    }

    /**
     * Called on dispatching an action if the requested action method is not
     * found.
     *
     * @return void
     */
    public function actionNotFound()
    {
        $request = $this->getRequest();
        $message = sprintf('Action %s not found in Controller %s / Module %s', $request->getActionName(),
                                                                               $request->getControllerName(),
                                                                               $request->getModuleName());
        throw new Zend_Controller_Action_Exception($message);
    }

    /**
     * @return Zend_Controller_Action_HelperBroker
     */
    public function getHelperBroker()
    {
        return $this->_helper;
    }

    /**
     * @param Xi_Inflector
     * @return Xi_Controller_Action
     */
    public function setPaths($paths)
    {
        $this->_paths = $paths;
        return $this;
    }

    /**
     * @return Xi_Inflector
     */
    public function getPaths()
    {
        if (null === $this->_paths) {
            $this->_paths = $this->_getDefaultPaths();
        }
        return $this->_paths;
    }

    /**
     * @return Xi_Inflector
     */
    public function _getDefaultPaths()
    {
        $paths  = Zend_Registry::getInstance()->config->paths;
        $params = array('moduleName' => $this->getRequest()->getModuleName());
        return $paths->module($params);
    }

    /**
     * Set action dispatcher object
     *
     * @see dispatch()
     * @param Xi_Controller_Action_Dispatcher_Abstract
     * @return Xi_Controller_Action
     */
    public function setActionDispatcher(Xi_Controller_Action_Dispatcher_Abstract $dispatcher)
    {
        $this->_actionDispatcher = $dispatcher;
        return $this;
    }

    /**
     * Retrieve action dispatcher object. If not set, one will be retrieved from
     * {@link _getDefaultActionDispatcher()}.
     *
     * @return Xi_Controller_Action_Dispatcher_Abstract
     */
    public function getActionDispatcher()
    {
        if (null === $this->_actionDispatcher) {
            $this->_actionDispatcher = $this->_getDefaultActionDispatcher();
        }
        return $this->_actionDispatcher;
    }

    /**
     * Get default action dispatcher object
     *
     * @return Xi_Controller_Action_Dispatcher_Abstract
     */
    protected function _getDefaultActionDispatcher()
    {
        return Zend_Registry::getInstance()->controller->action->dispatcher($this);
    }

    /**
     * Get model object. If not set, one will be retrieved from {@link
     * _getDefaultModel()}.
     *
     * @return null|Xi_Controller_Model
     */
    public function getModel()
    {
        if (null === $this->_model) {
            $this->_model = $this->_getDefaultModel();
        }
        return $this->_model;
    }

    /**
     * @param Xi_Controller_Model
     * @return Xi_Controller_Action
     */
    public function setModel($model)
    {
        $this->_model = $model;
        return $this;
    }

    /**
     * Get default model object
     *
     * @return Xi_Controller_Model
     */
    protected function _getDefaultModel()
    {
        return Zend_Registry::getInstance()->controller->action->model($this);
    }

    /**
     * Get view object. If not set, one will be retrieved from {@link
     * _getDefaultView()}.
     *
     * @return null|Xi_Controller_View
     */
    public function getView()
    {
        if (null === $this->_view) {
            $this->_view = $this->_getDefaultView();
        }
        return $this->_view;
    }

    /**
     * @param Xi_Controller_View
     * @return Xi_Controller_Action
     */
    public function setView($view)
    {
        $this->_view = $view;
        return $this;
    }

    /**
     * Get default view object
     *
     * @return Xi_Controller_View
     */
    protected function _getDefaultView()
    {
        return Zend_Registry::getInstance()->controller->action->view($this);
    }

    /**
     * Redirect function access to the helper broker
     *
     * @param string method name
     * @param array arguments
     * @return mixed
     * @throws Xi_Controller_Action_Exception
     */
    public function __call($method, $args)
    {
        try {
            return call_user_func_array(array($this->_helper, $method), $args);
        } catch (Zend_Controller_Action_Exception $e) {
            $message = sprintf('Method %s was not found in %s and could not be directed to %s', $method, get_class($this), get_class($this->_helper));
            throw new Xi_Controller_Action_Exception($message);
        }
    }

    /**
     * When accessing an undefined property, try to find a helper with the same
     * name
     *
     * @param string property name
     * @return Zend_Controller_Action_Helper_Abstract
     */
    public function __get($name)
    {
        return $this->_helper->getHelper($name);
    }
}

