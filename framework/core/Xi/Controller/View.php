<?php
/**
 * @category    Xi
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_View
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Controller_View extends Xi_Controller_Component
{
    protected $_engineLocation = 'view.engine';
    protected $_useLateRendering = true;

    protected function _getEngineCreationArguments()
    {
        return array($this->getRequest()->getModuleName());
    }

    /**
     * Retrieve model object from the controller
     *
     * @return Xi_Controller_Model
     */
    public function getModel()
    {
        return $this->_controller->getModel();
    }

    /**
     * Retrieve ViewRenderer from the controller
     *
     * @return Zend_Controller_Action_Helper_ViewRenderer
     */
    public function getViewRenderer()
    {
        return $this->_controller->getHelperBroker()->getHelper('ViewRenderer');
    }

    /**
     * Provided an action name and its status, use the ViewRenderer to
     * render a script.
     *
     * Called by the ActionDispatcher after a view method has been called. If
     * {@link $_useLateRendering} is enabled, sets the ViewRenderer to render
     * the script on postDispatch. Otherwise renders the script immediately.
     *
     * @param string action name
     * @param string action status
     * @return void
     */
    public function render($actionName, $status = null)
    {
        $scriptAction = $this->getScriptAction($actionName, $status);

        $viewRenderer = $this->getViewRenderer();
        if ($this->_useLateRendering) {
            $viewRenderer->setScriptAction($scriptAction);
        } else {
            $viewRenderer->render($scriptAction);
        }
    }

    /**
     * Provided an action name and its status, retrieve a script action name
     *
     * @param string action name
     * @param string action status
     * @return string
     */
    public function getScriptAction($actionName, $status = null)
    {
        $paths = $this->_controller->getPaths()->view;

        if (null === $status) {
            return $paths->scriptAction(array('action' => $actionName));
        }
        return $paths->scriptActionWithStatus(array('action' => $actionName, 'status' => $status));
    }

    /**
     * Get status value as a string
     *
     *  ''    => null
     *  true  => 'success'
     *  false => 'failure'
     *
     * @param string|boolean|null
     * @return string|null
     * @throws Xi_Controller_View_Exception on unsupported status type
     */
    public function statusToString($status)
    {
        if (!is_string($status)) {
            if (null === $status) {
                return null;
            } elseif (true == $status) {
                return 'success';
            } elseif (false == $status) {
                return 'failure';
            } else {
                throw new Xi_Controller_View_Exception('Unsupported action status type: ' . gettype($status));
            }
        }

        return strlen($status) ? $status : null;
    }
}