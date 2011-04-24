<?php
Xi_Loader::loadClass('Xi_Scheduler_Job');

/**
 * @category    Xi
 * @package     Xi_Bootstrap
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Bootstrap_Doctrine extends Xi_Scheduler_Job
{
    public function run(Xi_Scheduler $scheduler)
    {
        spl_autoload_register(array('Doctrine', 'autoload'));
        Doctrine_Manager::getInstance()
                        ->setAttribute(Doctrine::ATTR_MODEL_LOADING, Doctrine::MODEL_LOADING_CONSERVATIVE);
        
        $registry = $scheduler->getRegistry();
        Doctrine::loadModels($registry->config->paths->doctrine->models);
        
        // Instantiate a connection
        $registry->doctrine->connection();
    }
}

