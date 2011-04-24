<?php
/**
 * @category    Xi
 * @package     Xi_Scheduler
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Scheduler
{
    protected $_jobs = array();

    public function addJob(Xi_Scheduler_Job_Interface $job)
    {
        $job->notifyAdd($this);
        $this->_jobs[] = $job;
    }

    public function run()
    {
        foreach ($this->_jobs as $job) {
            $job->run($this);
        }
    }
}