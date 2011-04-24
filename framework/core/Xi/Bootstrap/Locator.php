<?php
Xi_Loader::loadClass('Xi_Scheduler_Job');

/**
 * Sets up Locator contents
 *
 * @category    Xi
 * @package     Xi_Bootstrap
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Bootstrap_Locator extends Xi_Scheduler_Job
{
    public function notifyAdd(Xi_Scheduler $scheduler)
    {
        $locator = $scheduler->getRegistry();

        $classes = $locator->config->load('classes');
        $parser  = new Xi_Factory_Parser;
        $parsed  = $parser->filter($classes);
        $locator->setResources($parsed);
    }
}
