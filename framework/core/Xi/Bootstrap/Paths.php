<?php
Xi_Loader::loadClass('Xi_Scheduler_Job');

/**
 * @category    Xi
 * @package     Xi_Bootstrap
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Bootstrap_Paths extends Xi_Scheduler_Job
{
    public function run(Xi_Scheduler $scheduler)
    {
        $locator = $scheduler->getRegistry();
        $paths   = $locator->config->paths;

        foreach ($paths->app->include as $prefix => $dir) {
            Xi_Loader::addDirectory($dir, $prefix);
        }

        $modules = $locator->config->params->enabledModules;
        foreach ($modules as $module) {
            foreach ($paths->module->include(array('moduleName' => $module)) as $prefix => $dir) {
                Xi_Loader::addDirectory($dir, $prefix);
            }
        }
    }
}

