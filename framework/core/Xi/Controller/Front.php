<?php
Xi_Loader::loadClass('Zend_Controller_Front');

/**
 * @category    Xi
 * @package     Xi_Controller
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Controller_Front extends Zend_Controller_Front
{
    /**
     * Singleton instance
     *
     * @return Xi_Controller_Front
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * Dispatch an HTTP request to a controller/action.
     *
     * @param Zend_Controller_Request_Abstract|null $request
     * @param Zend_Controller_Response_Abstract|null $response
     * @return void|Zend_Controller_Response_Abstract Returns response object if returnResponse() is true
     */
    public function dispatch(Zend_Controller_Request_Abstract $request = null, Zend_Controller_Response_Abstract $response = null)
    {
        /**
         * Instantiate default request object (HTTP version) if none provided
         */
        if (null !== $request) {
            $this->setRequest($request);
        } else {
            $request = $this->getRequest();
        }

        /**
         * Instantiate default response object (HTTP version) if none provided
         */
        if (null !== $response) {
            $this->setResponse($response);
        } else {
            $response = $this->getResponse();
        }

        /**
         * Register request and response objects with plugin broker
         */
        $this->_plugins
             ->setRequest($request)
             ->setResponse($response);

        /**
         * Initialize router
         */
        $router = $this->getRouter();
        $router->setParams($this->getParams());

        /**
         * Initialize dispatcher
         */
        $dispatcher = $this->getDispatcher();
        $dispatcher->setParams($this->getParams())
                   ->setResponse($response);

        // Begin dispatch
        try {
            /**
             * Route request to controller/action, if a router is provided
             */

            /**
            * Notify plugins of router startup
            */
            $this->_plugins->routeStartup($request);

            $router->route($request);

            /**
            * Notify plugins of router completion
            */
            $this->_plugins->routeShutdown($request);

            /**
             * Notify plugins of dispatch loop startup
             */
            $this->_plugins->dispatchLoopStartup($request);

            /**
             *  Attempt to dispatch the controller/action. If the $request
             *  indicates that it needs to be dispatched, move to the next
             *  action in the request.
             */
            do {
                $request->setDispatched(true);

                /**
                 * Notify plugins of dispatch startup
                 */
                $this->_plugins->preDispatch($request);

                /**
                 * Skip requested action if preDispatch() has reset it
                 */
                if (!$request->isDispatched()) {
                    continue;
                }

                /**
                 * Dispatch request
                 */
                try {
                    $dispatcher->dispatch($request, $response);
                } catch (Exception $e) {
                    $response->setException($e);
                }

                /**
                 * Notify plugins of dispatch completion
                 */
                $this->_plugins->postDispatch($request);
            } while (!$request->isDispatched());
        } catch (Exception $e) {
            $response->setException($e);
        }

        /**
         * Notify plugins of dispatch loop completion
         */
        try {
            $this->_plugins->dispatchLoopShutdown();
        } catch (Exception $e) {
            $response->setException($e);
        }

        $response->sendResponse();
    }

    /**
     * Return the dispatcher object.
     *
     * @return Zend_Controller_Dispatcher_Inteface
     */
    public function getDispatcher()
    {
        if (!$this->_dispatcher instanceof Zend_Controller_Dispatcher_Interface) {
            $this->setDispatcher(new Zend_Controller_Dispatcher_Standard($this->getParams()));
        }

        return $this->_dispatcher;
    }

    /**
     * Return the router object.
     *
     * @return null|Zend_Controller_Router_Interface
     */
    public function getRouter()
    {
        if (null === $this->_router) {
            $this->setRouter(new Zend_Controller_Router_Rewrite);
        }

        return $this->_router;
    }

    /**
     * Return the request object.
     *
     * @return null|Zend_Controller_Request_Abstract
     */
    public function getRequest()
    {
        if (null === $this->_request) {
            $this->setRequest(new Xi_Controller_Request);
        }
        return $this->_request;
    }

    /**
     * Return the response object.
     *
     * @return null|Zend_Controller_Response_Abstract
     */
    public function getResponse()
    {
        if ((null === $this->_response)) {
            $this->setResponse(new Xi_Controller_Response);
        }
        return $this->_response;
    }

    /**
     * Set whether exceptions encounted in the dispatch loop should be thrown
     * or caught and trapped in the response object
     *
     * @param boolean $flag Defaults to true
     * @return boolean|Zend_Controller_Front Used as a setter, returns object; as a getter, returns boolean
     */
    public function throwExceptions($flag = null)
    {
        if (null === $flag) {
            return false;
        }
        return $this;
    }

    /**
     * Set whether {@link dispatch()} should return the response without first
     * rendering output. By default, output is rendered and dispatch() returns
     * nothing.
     *
     * @param boolean $flag
     * @return boolean|Zend_Controller_Front Used as a setter, returns object; as a getter, returns boolean
     */
    public function returnResponse($flag = null)
    {
        if (null === $flag) {
            return false;
        }
        return $this;
    }
}
