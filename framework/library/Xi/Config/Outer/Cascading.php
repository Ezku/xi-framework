<?php
/**
 * Decorates Zend_Config instances retrieved from the inner config with a branch
 * of itself and keeps account of the child-objects produced
 *
 * @category    Xi
 * @package     Xi_Config
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Config_Outer_Cascading extends Xi_Config_Outer
{
    /**
     * @var string
     */
    protected $_childClass = __CLASS__;

    /**
     * @var array
     */
    protected $_children = array();

    /**
     * Create child Xi_Config_Outer_Cascading instance
     *
     * @param array|Zend_Config $config
     * @return Xi_Config_Outer_Cascading
     */
    public function createChild($config)
    {
        if (!$config instanceof Zend_Config) {
            $config = $this->createBranch($config);
        }
        $class = $this->_childClass;
        return Xi_Class::create($class, $this->_getChildCreationArguments($config));
    }

    /**
     * Get arguments for creating a child Xi_Config_Outer_Cascading instance
     *
     * @param Zend_Config $config
     * @return array
     */
    protected function _getChildCreationArguments($config)
    {
        return array($config);
    }

    public function __set($name, $value)
    {
        $return = parent::__set($name, $value);
        unset($this->_children[$name]);
        return $return;
    }

    public function __unset($name)
    {
        $return = parent::__unset($name);
        unset($this->_children[$name]);
        return $return;
    }

    public function get($name, $default = null)
    {
        $value = parent::get($name, $default);

        if (!$value instanceof Zend_Config) {
            return $value;
        }

        return $this->getChild($name, $value);
    }
    
    public function getRaw($name)
    {
        return parent::get($name);
    }

    public function current()
    {
        $value = parent::current();

        if (!$value instanceof Zend_Config) {
            return $value;
        }

        return $this->getChild($this->key(), $value);
    }
    
    /**
     * @return array
     */
    public function toArray()
    {
        $result = array();
        foreach ($this as $key => $value) {
            if ($value instanceof Zend_Config) {
                $value = $value->toArray();
            }
            $result[$key] = $value;
        }
        return $result;
    }

    /**
     * Assuming a key and a correspoding Zend_Config object, produce a child
     * Xi_Config_Outer_Cascading instance
     *
     * @param string
     * @param Zend_Config
     * @return Xi_Config_Outer_Cascading
     */
    public function getChild($name, $value)
    {
        if (isset($this->_children[$name]) && ($value === $this->_children[$name]->getConfig())) {
            return $this->_children[$name];
        }

        return $this->_children[$name] = $this->createChild($value);
    }

    /**
     * Retrieve all currently instantiated children. Does not account for all
     * children that possibly could be retrieved from the inner Zend_Config
     * instance.
     *
     * @return array Xi_Config_Outer_Cascading instances
     */
    public function getChildren()
    {
        return $this->_children;
    }
}
