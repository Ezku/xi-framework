<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.xi-framework.com>.
 */

/**
 * This dispatching strategy is three-staged, consisting of the validation
 * stage, the dispatch stage and the display stage.
 *
 * In the validation stage, controller validation methods are called to check
 * for the validity of the incoming request.
 *
 * In the dispatch stage, an action method or an action error method will be
 * called on the controller depending on whether the validation succeeded or
 * not.
 *
 * In the display stage, a view corresponding to the action and the status
 * returned from the action or action error method will be displayed.
 *
 * @category    Xi
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_Action
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Controller_Action_Dispatcher extends Xi_Controller_Action_Dispatcher_Abstract
{
    public function init()
    {
        $this->setStages(array('notifyPreDispatch',
                               'validate',
                               'dispatch',
                               'view',
                               'render',
                               'notifyPostDispatch'));
    }

    public function preStage()
    {
        /**
         * Halt if request was forwarded
         */
        if (!$this->getRequest()->isDispatched()) {
            $this->_halt();
        }
    }

    /**
     * Notify the controller of the pre dispatch state
     *
     * @return void
     */
    public function executeNotifyPreDispatch()
    {
        $this->_controller->preDispatch();
    }

    /**
     * Get validator methods based on action name. Call all available methods
     * and return false if any of them fail.
     *
     * @return boolean valid
     */
    public function executeValidate()
    {
        $controller = $this->getController();
        $action     = $this->getAction();
        $valid      = true;
        foreach ((array) $this->getValidatorMethod($action) as $method) {
            if (method_exists($controller, $method)) {
                $args  = (array) $this->getValidationFunctionArguments($valid, $action);
                $valid = $valid && call_user_func_array(array($controller, $method), $args);
            }
        }
        return $valid;
    }

    /**
     * Given validity status and an action name, retrieve the arguments for a
     * validation function call
     *
     * @param boolean valid
     * @param string action name
     * @return array
     */
    public function getValidationFunctionArguments($valid, $action)
    {
        return array($action);
    }

    /**
     * Dispatch to action or action error method depending on whether validation
     * succeeded
     *
     * @param boolean validation successful?
     * @return string status
     */
    public function executeDispatch($valid)
    {
        $action = $this->getAction();

        $methods = $valid
                   ? $this->getActionMethod($action)
                   : $this->getActionErrorMethod($action);

        $status = null;
        $status = Xi_Class::tryMethods($this->_controller,
                                       $methods,
                                       (array) $this->getActionFunctionArguments($valid, $action));

        /**
         * If validation failed, the default status is false
         */
        if (null === $status && !$valid) {
            $status = false;
        }

        return $status;
    }

    /**
     * Given validity status and an action name, retrieve the arguments for an
     * action function call
     *
     * @param boolean valid
     * @param string action name
     * @return array
     */
    public function getActionFunctionArguments($valid, $action)
    {
        $controller = $this->_controller;
        return array($controller->getRequest(), $controller->getModel());
    }

    /**
     * Execute a method in the view component using the action name and status
     *
     * @param null|boolean|string status
     * @return null|string view status
     */
    public function executeView($status)
    {
        $view         = $this->getController()->getView();
        $statusString = $view->statusToString($status);

        $action = $this->getAction();
        $method = $this->getViewMethod($action, $statusString);

        $viewStatus = Xi_Class::tryMethods($view,
                                           $method,
                                           (array) $this->getViewFunctionArguments($status, $action),
                                           false);

        if (null === $viewStatus) {
            return $statusString;
        }
        return $view->statusToString($viewStatus);
    }

    /**
     * Given an action status and an action name, retrieve the arguments for a
     * view function  call
     *
     * @param null|string status
     * @param string action name
     * @return array
     */
    public function getViewFunctionArguments($status, $action)
    {
        return array($status);
    }

    /**
     * Render the view based on its status
     *
     * @param null|string status
     * @return void
     */
    public function executeRender($viewStatus)
    {
        $view         = $this->getController()->getView();
        $action       = $this->getAction();
        $view->render($action, $viewStatus);
    }

    /**
     * Notify the controller of the post dispatch state
     *
     * @return void
     */
    public function executeNotifyPostDispatch()
    {
        $this->_controller->postDispatch();
    }

    /**
     * Get lowercased request method name
     *
     * @param boolean uppercase the first character; defaults to true
     * @return string
     */
    protected function _getFormattedRequestMethod($ucfirst = true)
    {
        $method = $this->getRequest()->getMethod();
        $method = strtolower($method);
        return $ucfirst ? ucfirst($method) : $method;
    }

    /**
     * Get validator methods based on an action name
     *
     * @param string action name
     * @return array methods to call to validate action
     */
    public function getValidatorMethod($actionName)
    {
        return array('validate',
                     $actionName . 'Validate',
                     $actionName . 'Validate' . $this->_getFormattedRequestMethod());
    }

    /**
     * Get action methods based on action name
     *
     * @param string action name
     * @return array action method name
     */
    public function getActionMethod($actionName)
    {
        return array($actionName . 'Action' . $this->_getFormattedRequestMethod(),
                     $actionName . 'Action',
                     'actionNotFound');
    }

    /**
     * Get action error methods based on action name
     *
     * @param string action name
     * @return array error handler method name
     */
    public function getActionErrorMethod($actionName)
    {
        return array($actionName . 'ActionError' . $this->_getFormattedRequestMethod(),
                     $actionName . 'ActionError',
                     'actionNotFound');
    }

    /**
     * Get view method based on action name and status
     *
     * @param string action name
     * @param string status
     * @return array view display method name
     */
    public function getViewMethod($actionName, $status)
    {
        return array('display' . ucfirst($actionName) . ucfirst($status),
                     'display' . ucfirst($actionName),
                     'display');
    }
}

