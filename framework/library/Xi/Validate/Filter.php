<?php
/**
 * Describes an outer validator that can take a filter to apply to the input
 * value before validation
 *
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Validate_Filter extends Xi_Validate_Outer implements Zend_Filter_Interface, Xi_Filter_Aggregate
{
    protected $_filter;

    public function __construct($validator, $filter)
    {
        parent::__construct($validator);
        $this->_filter = $filter;
    }

    public function getFilter()
    {
        return $this->_filter;
    }

    public function isValid($value)
    {
        return parent::isValid($this->filter($value));
    }

    public function filter($value)
    {
        return $this->getFilter()->filter($value);
    }
}
