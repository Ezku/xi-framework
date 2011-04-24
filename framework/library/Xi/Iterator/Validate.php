<?php
/**
 * A FilterIterator that accepts or rejects values based on whether they are
 * valid according to a Zend_Validate_Interface object
 * 
 * @category    Xi
 * @package     Xi_Iterator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Iterator_Validate extends FilterIterator implements Xi_Validate_Aggregate 
{
    /**
     * @var Zend_Validate_Interface
     */
    protected $_validator;
    
    /**
     * @param Iterator $it
     * @param Zend_Validate_Interface $validator
     */
    public function __construct(Iterator $it, Zend_Validate_Interface $validator)
    {
        parent::__construct($it);
        $this->_validator = $validator;
    }
    
    /**
     * @return Zend_Validate_Interface
     */
    public function getValidator()
    {
        return $this->_validator;
    }
    
    /**
     * Validate current value before accepting it
     *
     * @return boolean
     */
    public function accept()
    {
        return $this->_validator->isValid($this->current());
    }
}
