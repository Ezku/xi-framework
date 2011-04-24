<?php
Xi_Loader::loadClass('Xi_Scheduler');

/**
 * @category    Xi
 * @package     Xi_Scheduler
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Scheduler_Shutdown extends Xi_Scheduler
{
    protected static $_instance;

    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function addJob(Xi_Scheduler_Job_Interface $job)
    {
        parent::addJob($job);
        register_shutdown_function(array($job, 'run'), $this);
    }
}
