<?php
/**
 * Describes filters that perform an operation on the value to be filtered
 *
 * @category    Xi
 * @package     Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
abstract class Xi_Filter_Operation_Abstract implements Zend_Filter_Interface
{
    /**
     * @var mixed
     */
    protected $_argument;

    /**
     * @var mixed
     */
    protected $_default;

    /**
     * @param mixed argument to use with the operation
     * @param mixed default value if operation is invalid
     */
    public function __construct($argument, $default = null)
    {
        $this->_argument = $argument;
        $this->_default = $default;
    }

    /**
     * @param mixed default value if operation is invalid
     * @return Xi_Filter_Operation_Abstract
     */
    public function setDefaultValue($value)
    {
        $this->_default = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArgument()
    {
        return $this->_argument;
    }
}

