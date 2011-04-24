<?php
/**
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Factory_Behaviour_Singleton extends Xi_Factory_Behaviour_Abstract
{
    protected $_singletonValue = null;

    public function get($args = null)
    {
        if (isset($this->_singletonValue)) {
            return $this->_singletonValue;
        }

        $this->_singletonValue = $this->_factory->get($args);
        unset($this->_factory);
        return $this->_singletonValue;
    }
}