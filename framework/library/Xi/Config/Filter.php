<?php
/**
 * A config decorator that applies a filter to values and/or keys in the config
 *
 * @category    Xi
 * @package     Xi_Config
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Config_Filter extends Xi_Config_Outer_Cascading implements Xi_Filter_Aggregate
{
    /**
     * Option to enable filtering keys
     */
    const FILTER_KEYS = 1;

    /**
     * Option to enable filtering values
     */
    const FILTER_VALUES = 2;

    /**
     * Option to enable filtering values and keys
     */
    const FILTER_VALUES_AND_KEYS = 3;

    /**
     * @var Zend_Filter_Interface
     */
    protected $_filter;

    /**
     * @var int
     */
    protected $_options;

    /**
     * @var int
     */
    protected $_defaultOptions = self::FILTER_VALUES;

    /**
     * @var string
     */
    protected $_childClass = __CLASS__;

    public function __construct($config, $filter = null, $options = null)
    {
        parent::__construct($config);
        $this->_filter = $filter;
        if (null === $options) {
            $options = $this->_defaultOptions;
        }
        $this->_options = (int) $options;
    }

    /**
     * @return Zend_Filter_Interface
     */
    public function getFilter()
    {
        return $this->_filter;
    }

    /**
     * @return int
     */
    public function getOptions()
    {
        return $this->_options;
    }

    protected function _getChildCreationArguments($config)
    {
        return array($config, $this->_filter, $this->_options);
    }

    public function get($name, $default = null)
    {
        return $this->filterValue(parent::get($name, $default));
    }

    public function filterValue($value)
    {
        if (!($value instanceof Zend_Config) && (null !== $this->_filter) && (self::FILTER_VALUES & $this->_options)) {
            return $this->_filter->filter($value);
        }
        return $value;
    }

    public function filterKey($key)
    {
        if ((null !== $this->_filter) && (self::FILTER_KEYS & $this->_options)) {
            return $this->_filter->filter($key);
        }
        return $key;
    }

    public function current()
    {
        return $this->filterValue(parent::current());
    }

    public function key()
    {
        return $this->filterKey(parent::key());
    }
}
