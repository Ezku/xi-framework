<?php
Xi_Loader::loadClass('Xi_Scheduler_Job');

/**
 * @category    Xi
 * @package     Xi_Bootstrap
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Bootstrap_Controllers extends Xi_Scheduler_Job
{
    public function notifyAdd(Xi_Scheduler $scheduler)
    {
        $locator = $scheduler->getRegistry();
        $locator->config->params = $locator->config->load('params');
        $locator->config->routes = $locator->config->load('routes', $namespace = false);
    }

    public function run(Xi_Scheduler $scheduler)
    {
        $locator = $scheduler->getRegistry();

        /**
         * Action helpers
         */
        foreach ($locator->controller->action->helpers as $helper) {
            Zend_Controller_Action_HelperBroker::addHelper($helper);
        }
        Zend_Controller_Action_HelperBroker::addPrefix('Xi_Controller_Action_Helper');
        Zend_Controller_Action_HelperBroker::addPrefix($locator->config->paths->appName . '_Controller_Action_Helper');
    }
}

