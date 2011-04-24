<?php
Xi_Loader::loadInterface('Xi_Factory_Behaviour_Interface');

/**
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
abstract class Xi_Factory_Behaviour_Composite implements Xi_Factory_Behaviour_Interface
{
    /**
     * @var Xi_Locator
     */
    protected $_locator;

    /**
     * @var Xi_Factory_Interface
     */
    protected $_primary;

    /**
     * @var Xi_Factory_Interface
     */
    protected $_secondary;

    public function __construct(Xi_Factory_Interface $primary)
    {
        $this->_primary = $primary;
    }

    public function setLocator($locator)
    {
        $this->_locator = $locator;
        $this->_primary->setLocator($locator);
        if ($this->_secondary instanceof Xi_Factory_Behaviour_Interface) {
            $this->_secondary->setLocator($locator);
        }
    }

    public function setFactory(Xi_Factory_Interface $secondary)
    {
        $this->_secondary = $secondary;
    }
}
