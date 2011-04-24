<?php
/**
 * Wraps a validator
 *
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Validate_Outer implements Zend_Validate_Interface, Xi_Validate_Aggregate
{
    /**
     * Inner validator
     *
     * @var Zend_Validate_Interface
     */
    protected $_validator;

    /**
     * Accepts either a validator or a validator aggregate.
     * 
     * @param Zend_Validate_Interface|Xi_Validate_Aggregate $validator
     * @return void
     */
    public function __construct($validator)
    {
        if (!($validator instanceof Zend_Validate_Interface) && ($validator instanceof Xi_Validate_Aggregate)) {
            $validator = $validator->getValidator();
        }
        $this->_validator = $validator;
    }

    /**
     * Retrieve inner validator
     *
     * @return Zend_Validator_Interface
     */
    public function getValidator()
    {
        return $this->_validator;
    }

    public function getMessages()
    {
        return $this->getValidator()->getMessages();
    }

    public function getErrors()
    {
        return $this->getValidator()->getErrors();
    }

    public function isValid($value)
    {
        return $this->getValidator()->isValid($value);
    }
}
