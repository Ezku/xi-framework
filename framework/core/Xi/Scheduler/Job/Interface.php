<?php
/**
 * @category    Xi
 * @package     Xi_Scheduler
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
interface Xi_Scheduler_Job_Interface
{
    public function notifyAdd(Xi_Scheduler $scheduler);
    public function run(Xi_Scheduler $scheduler);
}

