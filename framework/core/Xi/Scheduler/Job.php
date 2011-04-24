<?php
Xi_Loader::loadInterface('Xi_Scheduler_Job_Interface');

/**
 * @category    Xi
 * @package     Xi_Scheduler
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Scheduler_Job implements Xi_Scheduler_Job_Interface
{
    public function notifyAdd(Xi_Scheduler $scheduler)
    {}

    public function run(Xi_Scheduler $scheduler)
    {}
}
