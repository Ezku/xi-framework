<?php
/**
 * Filters incoming values only if they are not valid
 * 
 * @category    Xi
 * @package     Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_Validate extends Xi_Filter_Outer implements Xi_Validate_Aggregate
{
    /**
     * @var Zend_Validate_Interface
     */
    protected $_validator;
    
    /**
     * @param Zend_Filter_Interface $filter
     * @param Zend_Validate_Interface $validator
     */
    public function __construct($filter, $validator)
    {
        parent::__construct($filter);
        $this->_validator = $validator;
    }
    
    /**
     * @return Zend_Validate_Interface $value
     */
    public function getValidator()
    {
        return $this->_validator;
    }
    
    public function filter($value)
    {
        if ($this->_validator->isValid($value)) {
            return $value;
        }
        return parent::filter($value);
    }
}