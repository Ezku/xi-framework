<?php
/**
 * @category    Xi
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_Dispatcher
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Controller_Dispatcher_Factory extends Xi_Factory
{
    public function init()
    {
        $this->actAs('singleton');
    }

    public function create($args)
    {
        $dispatcher = Xi_Class::create('Zend_Controller_Dispatcher_Standard', $args);
        $enabledModules = $this->_locator->config->params->enabledModules;

        foreach ($enabledModules as $moduleName) {
            $dir = $this->_locator->config->paths->module->controllerDir(array('moduleName' => $moduleName));
            $dispatcher->addControllerDirectory($dir, $moduleName);
        }

        return $dispatcher;
    }

    public function mapCreationArguments($args)
    {
        return array($args);
    }
}
