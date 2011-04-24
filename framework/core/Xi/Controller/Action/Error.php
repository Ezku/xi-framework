<?php
/**
 * @category    Xi
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_Action
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Controller_Action_Error extends Xi_Controller_Action
{
    public function errorAction()
    {
        $errors       = $this->_getParam('error_handler');
        $viewRenderer = $this->_helper->getHelper('ViewRenderer');
        $request      = $this->getRequest();

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
                $viewRenderer->setScriptAction('controllerNotFound');
                $request->setActionName('controllerNotFound');
                return $this->controllerNotFoundAction();
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                $viewRenderer->setScriptAction('actionNotFound');
                $request->setActionName('actionNotFound');
                return $this->actionNotFoundAction();
            default:
                $viewRenderer->setScriptAction('unknown');
                $request->setActionName('unknown');
                return $this->unknownAction();
        }
    }
    
    public function forbiddenAction()
    {
        $this->getResponse()->setHttpResponseCode(403);
    }

    public function controllerNotFoundAction()
    {
        $this->getResponse()->setHttpResponseCode(404);
    }

    public function actionNotFoundAction()
    {
        $this->getResponse()->setHttpResponseCode(404);
    }

    public function unknownAction()
    {
        $this->getResponse()->setHttpResponseCode(500);
    }
}

