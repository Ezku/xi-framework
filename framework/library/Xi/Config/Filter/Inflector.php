<?php
/**
 * @category    Xi
 * @package     Xi_Config
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Config_Filter_Inflector extends Xi_Config_Filter
{
    /**
     * @var int
     */
    protected $_defaultOptions = self::FILTER_VALUES_AND_KEYS;

    /**
     * @var string
     */
    protected $_childClass = __CLASS__;

    /**
     * @param array|Zend_Config $config
     * @param Xi_Filter_Inflector_Recursive $filter
     * @param int $options
     */
    public function __construct($config, $filter = null, $options = null)
    {
        if (null === $filter) {
            $filter = new Xi_Filter_Inflector_Recursive;
        } elseif (!$filter instanceof Xi_Filter_Inflector_Recursive) {
            $error = sprintf('Expecting an instance of Xi_Filter_Inflector_Recursive, %s provided', Xi_Class::getType($filter));
            throw new Xi_Config_Exception($error);
        }
        
        $inflector  = $filter->getInflector();
        $identifier = $inflector->getTargetReplacementIdentifier();
        $rules      = array();
        
        foreach ($config as $key => $value) {
            if (is_string($value) && ($key[0] !== $identifier)) {
                $rules[$key] = $value;
            }
        }
        $inflector->addRules($rules);

        parent::__construct($config, $filter, $options);
    }

    public function _getChildCreationArguments($config)
    {
        return array($config, clone $this->_filter, $this->_options);
    }
    
    public function __clone()
    {
        $this->_config = clone $this->_config;
        $this->_filter = clone $this->_filter;
    }

    public function __set($name, $value)
    {
        $return = parent::__set($name, $value);
        if (is_string($value) && ($name[0] !== $this->_filter->getInflector()->getTargetReplacementIdentifier())) {
            $this->addRules(array($name => $value));
        }
        return $return;
    }

    /**
     * Add rules to current inflector and cascade changes to children
     *
     * @param array
     * @return Xi_Config_Filter_Inflector
     */
    public function addRules($rules)
    {
        $this->_filter->getInflector()->addRules($rules);
        foreach ($this->getChildren() as $child) {
            $child->getFilter()->getInflector()->addRules($rules);
        }
        return $this;
    }
    
    public function getInflected($name, $rules = array())
    {
        $value = $this->getRaw($name);
        
        if ((null === $this->_filter) || !(self::FILTER_VALUES & $this->_options)) {
            return $value;
        }
        
        if (!$value instanceof Zend_Config) {
            return $this->getFilter()->filter($value, $rules);
        }
        
        $value = clone $this->getChild($name, $value);
        if (!empty($rules)) {
            $value->addRules($rules);
        }
        return $value;
    }
    
    public function __call($name, $args)
    {
        return $this->getInflected($name, isset($args[0]) && is_array($args[0]) ? $args[0] : array());
    }
}
