<?php
/**
 * Decorates a controller plugin
 * 
 * @category    Xi
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_Plugin
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Controller_Plugin_Outer extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zend_Controller_Plugin_Abstract
     */
    protected $_plugin;
    
    /**
     * @param Zend_Controller_Plugin_Abstract $plugin
     * @return void
     */
    public function __construct(Zend_Controller_Plugin_Abstract $plugin)
    {
        $this->_plugin = $plugin;
    }
    
    /**
     * @return Zend_Controller_Plugin_Abstract
     */
    public function getPlugin()
    {
        return $this->_plugin;
    }

    /**
     * @return void
     */
    public function dispatchLoopShutdown()
    {
        return $this->getPlugin()->dispatchLoopShutdown();
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function dispatchLoopStartup($request)
    {
        return $this->getPlugin()->dispatchLoopStartup($request);
    }

    /**
     * @return Zend_Controller_Request_Abstract
     */
    public function getRequest()
    {
        return $this->getPlugin()->getRequest();
    }

    /**
     * @return Zend_Controller_Response_Abstract
     */
    public function getResponse()
    {
        return $this->getPlugin()->getResponse();
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function postDispatch($request)
    {
        return $this->getPlugin()->postDispatch($request);
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function preDispatch($request)
    {
        return $this->getPlugin()->preDispatch($request);
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function routeShutdown($request)
    {
        return $this->getPlugin()->routeShutdown($request);
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function routeStartup($request)
    {
        return $this->getPlugin()->routeStartup($request);
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return Zend_Controller_Plugin_Abstract
     */
    public function setRequest($request)
    {
        $this->getPlugin()->setRequest($request);
        return $this;
    }

    /**
     * @param Zend_Controller_Response_Abstract $response
     * @return Zend_Controller_Plugin_Abstract
     */
    public function setResponse($response)
    {
        $this->getPlugin()->setResponse($response);
        return $this;
    }

}
