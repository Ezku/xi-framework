<?php
/**
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
abstract class Xi_Validate_Composite implements Zend_Validate_Interface
{
    /**
     * @var array
     */
    protected $_validators = array();

    /**
     * @param array $validators
     */
    public function __construct(array $validators = array())
    {
        $this->_validators = $validators;
    }

    /**
     * @return array
     */
    public function getValidators()
    {
        return $this->_validators;
    }

    /**
     * @param Zend_Validate_Interface $validator
     * @param boolean $prepend
     * @return Xi_Validate_Composite
     */
    public function addValidator($validator, $prepend = false)
    {
        if ($prepend) {
            array_unshift($this->_validators, $validator);
        } else {
            $this->_validators[] = $validator;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        $messages = array();
        foreach ($this->_validators as $validator) {
            $messages = array_merge($messages, $validator->getMessages());
        }
        return $messages;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return array_keys($this->getMessages());
    }
}
