<?php
/**
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Factory_Injectable extends Xi_Factory_Configurable implements Xi_Locator_Injectable_Interface
{
    /**
     * @var Xi_Locator
     */
    protected $_locator;

    public function setLocator($locator)
    {
        $this->_locator = $locator;
        $locator->inject($this->_options);
    }
    
    public function getArguments($args)
    {
        return $this->_locator->getFactoryValues(parent::getArguments($args));
    }
    
    public function get($args = null)
    {
        $value = parent::get($args);
        $this->_locator->inject($value);
        return $value;
    }
    
    public function getOption($name, $default = null)
    {
        $value = parent::getOption($name, null);
        if (null === $value) {
            return $default;
        }
        return $this->_locator->getFactoryValues($value);
    }
}