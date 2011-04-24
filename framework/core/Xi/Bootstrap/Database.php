<?php
Xi_Loader::loadClass('Xi_Scheduler_Job');

/**
 * @category    Xi
 * @package     Xi_Bootstrap
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Bootstrap_Database extends Xi_Scheduler_Job
{
    public function notifyAdd(Xi_Scheduler $scheduler)
    {
        $locator = $scheduler->getRegistry();
        $locator->config->database = $locator->config->load('database');
    }
}

