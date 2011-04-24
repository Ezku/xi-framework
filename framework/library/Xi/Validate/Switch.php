<?php
/**
 * Given a switch validator and two target validators, chooses a target
 * validator based on the switch validator's return value
 * 
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Validate_Switch extends Xi_Validate_Outer
{
    /**
     * @var Zend_Validate_Interface
     */
    protected $_success;
    
    /**
     * @var Zend_Validate_Interface
     */
    protected $_failure;
    
    /**
     * @var boolean
     */
    protected $_valid;
    
    /**
     * @param Zend_Validate_Interface $validator
     * @param Zend_Validate_Interface $success
     * @param Zend_Validate_Interface $failure
     * @return void
     */
    public function __construct($validator, $success, $failure)
    {
        parent::__construct($validator);
        $this->_success = $success;
        $this->_failure = $failure;
    }
    
    /**
     * @return Zend_Validate_Interface
     */
    public function getSuccessValidator()
    {
        return $this->_success;
    }
    
    /**
     * @return Zend_Validate_Interface
     */
    public function getFailureValidator()
    {
        return $this->_failure;
    }
    
    /**
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        if ($this->getValidator()->isValid($value)) {
            $this->_valid = true;
            return $this->getSuccessValidator()->isValid($value);
        }
        $this->_valid = false;
        return $this->getFailureValidator()->isValid($value);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        switch ($this->_valid) {
            case true:
                return $this->getSuccessValidator()->getErrors();
            case false:
                return $this->getFailureValidator()->getErrors();
        }
        return array();
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        switch ($this->_valid) {
            case true:
                return $this->getSuccessValidator()->getMessages();
            case false:
                return $this->getFailureValidator()->getMessages();
        }
        return array();
    }

}
