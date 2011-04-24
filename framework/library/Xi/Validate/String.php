<?php
/**
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Validate_String extends Zend_Validate_Abstract
{
    const NOT_STRING = 'notString';
    
    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_STRING    => "The value is not a string"
    );
    
    /**
     * @param mixed $value
     * @return true
     */
    public function isValid($value)
    {
        $this->_setValue($value);
        
        if (!is_string($value)) {
            $this->_error(self::NOT_STRING);
            return false;
        }
        
        return true;
    }
}
