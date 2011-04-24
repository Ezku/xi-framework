<?php
/**
 * Wrap a factory
 *
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Factory_Behaviour_Abstract implements Xi_Factory_Behaviour_Interface
{
    /**
     * @var null|Xi_Factory_Interface
     */
    protected $_factory;

    /**
     * @param null|Xi_Factory_Interface
     * @return void
     */
    public function __construct($factory = null)
    {
        $this->_factory = $factory;
    }

    public function setLocator($locator)
    {
        if ($this->_factory instanceof Xi_Factory_Behaviour_Interface) {
            $this->_factory->setLocator($locator);
        }
    }

    public function setFactory(Xi_Factory_Interface $factory)
    {
        if ($this->_factory instanceof Xi_Factory_Behaviour_Interface) {
            $this->_factory->setFactory($factory);
        } else {
            $this->_factory = $factory;
        }
        return $this;
    }

    public function get($args = null)
    {
        $value = $this->_factory->get($args);
        return $value;
    }
}