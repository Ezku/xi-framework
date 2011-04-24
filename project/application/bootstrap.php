<?php
/**
 * This is an application-specific bootstrap file. Its main uses are to set up a
 * registry object and load and apply configuration settings.
 *
 * @category    Xi_App
 * @package     Xi_Bootstrap
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */

/**
 * Set up include path
 */
$root = dirname(dirname(dirname(__FILE__)));
set_include_path(
    $root . DIRECTORY_SEPARATOR . 'project' . DIRECTORY_SEPARATOR . 'lib' . PATH_SEPARATOR .
    $root . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'core' . PATH_SEPARATOR .
    $root . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'library' . PATH_SEPARATOR .
    $root . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'vendor' . PATH_SEPARATOR .
    $root . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'Doctrine' . PATH_SEPARATOR
);

/**
 * Set up autoloading
 */
require_once 'Xi/Loader.php';
spl_autoload_register(array('Xi_Loader', 'autoload'));

/**
 * Load bootstrap settings
 */
$settings_dist = include 'settings.dist.php';
$settings_user = @include 'settings.php';

$merge = new Xi_Array_Operation_Merge($settings_dist, is_array($settings_user) ? $settings_user : array());
$settings = $merge->execute();
unset($merge);

/**
 * If cache debugging is enabled, purge all cache files on startup
 */
if ($settings['cache']['debug']) {
    foreach (new DirectoryIterator($settings['dir']['cache']) as $file) {
        if ($file->isFile() && (0 !== strpos($file->getFilename(), '.'))) {
            unlink($file->getPathname());
        }
    }
}

if ($settings['cache']['class']['enabled']) {
    /**
     * Set up class definition cache
     */
    $classCache = new Xi_Storage_File_Expiring($settings['cache']['class']['path'], $settings['cache']['class']['timeToLive']);

    Xi_Scheduler_Shutdown::getInstance()
                         ->addJob(new Xi_Compiler_Job($settings['cache']['class']['namespaces'], $classCache));

    require $settings['cache']['class']['path'];
}

if ($settings['cache']['startup']['enabled']) {
    $startupCache = new Xi_Storage_File_Expiring($settings['cache']['startup']['path']);
}

if (isset($startupCache) && !$startupCache->isEmpty()) {
    $startup = $startupCache->read();
} else {
    /**
     * Set up configuration factory.
     */

    $applicationConfig  = new Xi_Config_Reader_Factory($settings['config']['application']);
    $projectConfig      = new Xi_Config_Reader_Factory($settings['config']['project']);
    $frameworkConfig    = new Xi_Config_Reader_Factory($settings['config']['framework']);

    $applicationConfig->actAs(new Xi_Factory_Behaviour_Composite_Defaults($projectConfig));
    $applicationConfig->actAs(new Xi_Factory_Behaviour_Composite_Defaults($frameworkConfig));
    $applicationConfig->actAs('config');

    /**
     * Wrap factory in a cache if required
     */
    if ($settings['cache']['config']['enabled']) {
        $configCache  = new Xi_Storage_File_Expiring($settings['cache']['config']['path']);
        $applicationConfig->actAs(new Xi_Factory_Behaviour_Persistent($applicationConfig, $configCache));
    }

    $locator = new Xi_Locator_Namespaced;
    $locator->config = array('load'      => $applicationConfig,
                             'bootstrap' => $settings);

    /**
     * Add startup scripts to scheduler
     */
    $startup = new Xi_Scheduler_Startup($locator);
    foreach ($settings['startupJobs'] as $job) {
        if (is_string($job)) {
            $job = new $job;
        }
        $startup->addJob($job);
    }

    if (isset($startupCache)) {
        $startupCache->write($startup);
    }
}

/**
 * Run startup scripts, initialize Zend_Registry
 */
$startup->run();
Zend_Registry::setInstance($startup->getRegistry());
