<?php
/**
 * Applies different filters based on whether incoming value is valid
 * 
 * @category    Xi
 * @package     Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Filter_Conditional extends Xi_Validate_Outer implements Zend_Filter_Interface
{
    /**
     * @var Zend_Filter_Interface
     */
    protected $_success;
    
    /**
     * @var Zend_Filter_Interface
     */
    protected $_failure;
    
    /**
     * @param Zend_Validate_Interface $validator
     * @param Zend_Filter_Interface $success
     * @param Zend_Filter_Interface $failure
     * @return void
     */
    public function __construct($validator, $success, $failure)
    {
        parent::__construct($validator);
        $this->_success   = $success;
        $this->_failure   = $failure;
    }
    
    /**
     * @return Zend_Filter_Interface
     */
    public function getSuccessFilter()
    {
        return $this->_success;
    }
    
    /**
     * @return Zend_Filter_Interface
     */
    public function getFailureFilter()
    {
        return $this->_failure;
    }
    
    public function filter($value)
    {
        switch ($this->isValid($value)) {
            case true:
                return $this->_success->filter($value);
            case false:
                return $this->_failure->filter($value);
            default:
                return null;
        }
    }
}