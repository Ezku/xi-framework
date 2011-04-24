<?php
Xi_Loader::loadClass('Xi_Scheduler_Job');

/**
 * @category    Xi
 * @package     Xi_Bootstrap
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Bootstrap_Settings extends Xi_Scheduler_Job
{
    public function notifyAdd(Xi_Scheduler $scheduler)
    {
        $locator = $scheduler->getRegistry();
        $locator->config->settings = $locator->config->load('settings');
    }

    public function run(Xi_Scheduler $scheduler)
    {
        $locator = $scheduler->getRegistry();
        $settings = $locator->config->settings;

        /**
         * Error reporting
         */
        if (isset($settings->errorReporting)) {
            error_reporting($settings->errorReporting);
        }

        /**
         * Error handler
         */
        if (isset($settings->errorHandler)) {
            $handler = $settings->errorHandler;
            if (is_object($handler)) {
                set_error_handler($handler->toArray());
            } else {
                set_error_handler($handler);
            }
        }

        /**
         * Exception handler
         */
        if (isset($settings->exceptionHandler)) {
            $handler = $settings->exceptionHandler;
            if (is_object($handler)) {
                set_exception_handler($handler->toArray());
            } else {
                set_exception_handler($handler);
            }
        }
    }
}

