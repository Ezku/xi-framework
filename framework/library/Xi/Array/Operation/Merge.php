<?php
/**
 * Given two arrays, recursively merge their values
 *
 * @category    Xi
 * @package     Xi_Array
 * @subpackage  Xi_Array_Operation
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Array_Operation_Merge
{
    /**
     * @var array
     */
    protected $_primary;

    /**
     * @var array
     */
    protected $_secondary;

    /**
     * @var array
     */
    protected $_options;

    /**
     * @var array
     */
    protected $_defaultOptions = array('mode' => 'append');

    /**
     * @var string
     */
    protected $_arrayOptionKey = '_mergeOptions';

    /**
     * Values in $primary get overridden or merged with those from $secondary
     *
     * @param array primary
     * @param array secondary
     * @param array options
     */
    public function __construct(array $primary, array $secondary, array $options = array())
    {
        $this->_primary = $primary;
        $this->_secondary = $secondary;
        $this->_options = $options
                          + $this->_getOptionsFromArray($primary)
                          + $this->_getOptionsFromArray($secondary)
                          + $this->_getDefaultOptions();
    }

    /**
     * Alias for {@link __construct()}
     *
     * @param array primary
     * @param array secondary
     * @param array options
     * @return Xi_Array_Operation_Merge
     */
    public static function create(array $primary, array $secondary, array $options = array())
    {
        return new self($primary, $secondary, $options);
    }

    /**
     * @param array primary
     * @param array secondary
     * @param array options
     * @return Xi_Array_Operation_Merge
     */
    public function createBranch(array $primary, array $secondary, array $options = array())
    {
        $class = get_class($this);
        return new $class($primary, $secondary, $options);
    }

    /**
     * Get default options
     *
     * @return array
     */
    protected function _getDefaultOptions()
    {
        return $this->_defaultOptions;
    }

    /**
     * Interpret parsing options from array data
     *
     * @param array input
     * @return array options
     */
    protected function _getOptionsFromArray(array $array)
    {
        $key = $this->_arrayOptionKey;
        if (!isset($array[$key])) {
            return array();
        }
        if (!is_array($array[$key])) {
            return array('mode' => $array[$key]);
        }
        return $array[$key];
    }

    /**
     * Split an array into two parts: associated and integer-indexed
     *
     * @param array
     * @return array
     */
    protected function _split(array $array)
    {
        $associated = array();
        $indexed = array();
        foreach ($array as $key => $value) {
            if (is_int($key)) {
                $indexed[$key] = $value;
            } else {
                $associated[$key] = $value;
            }
        }
        return compact('associated', 'indexed');
    }

    /**
     * Execute operation
     *
     * @param null|array options
     * @return array
     */
    public function execute(array $options = array())
    {
        $primary = $this->_split($this->_primary);
        $secondary = $this->_split($this->_secondary);

        $result = array();
        foreach ($primary['associated'] as $key => $value) {
            if (!isset($secondary['associated'][$key])) {
                $result[$key] = $value;
            } elseif (!(is_array($value) && is_array($secondary['associated'][$key]))) {
                $result[$key] = $secondary['associated'][$key];
            } else {
                $merge = $this->createBranch($value, $secondary['associated'][$key], $this->_options);
                $result[$key] = $merge->execute($options);
            }
        }
        $result += $secondary['associated'];

        $options += $this->_options;
        if ('append' == $options['mode']) {
            $result += array_merge($primary['indexed'], $secondary['indexed']);
        } elseif ('override' == $options['mode']) {
            $result += empty($secondary['indexed']) ? $primary['indexed'] : $secondary['indexed'];
        }

        return $result;
    }
}
