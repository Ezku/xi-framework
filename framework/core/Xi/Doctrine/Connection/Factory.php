<?php
/**
 * Factory for a Doctrine database connection
 *
 * @category    Xi
 * @package     Xi_Doctrine
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Doctrine_Connection_Factory extends Xi_Factory
{
    public function create($connectionName = null, $setCurrent = null)
    {
        if (null === $connectionName) {
            $connectionName = 'default';
        }
        if (null === $setCurrent) {
            $setCurrent = true;
        }

        $manager = Doctrine_Manager::getInstance();

        if (!$manager->contains($connectionName)) {
            $config = $this->getConfig();
            if (!isset($config->$connectionName)) {
                $message = sprintf('Connection "%s" not found in configuration', $connectionName);
                throw new Xi_Exception($message);
            }
            return $manager->openConnection($config->$connectionName, $connectionName, $setCurrent);
        }

        if ($setCurrent) {
            $manager->setCurrentConnection($connectionName);
        }
        return $manager->getConnection($connectionName);
    }

    /**
     * @return Zend_Config
     */
    public function getConfig()
    {
        if ($this->hasOption('config')) {
            $config = $this->getOption('config');
        } else {
            $config = $this->_locator->config->database;
        }

        $config = new Xi_Config_Filter_Inflector($config, $this->getInflector());
        return $config;
    }
    
    /**
     * @return Xi_Filter_Inflector_Recursive
     */
    public function getInflector()
    {
        return clone $this->_locator->config->paths->doctrine->getFilter();
    }
}

