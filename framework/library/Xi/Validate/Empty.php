<?php
/**
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Validate_Empty extends Zend_Validate_Abstract
{
    const NOT_EMPTY = 'notEmpty';
    
    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_EMPTY    => "Value is not empty"
    );
    
    /**
     * @param mixed $value
     * @return true
     */
    public function isValid($value)
    {
        $this->_setValue($value);
        
        if (!strlen((string) $value)) {
            return true;
        }
        
        $this->_error(self::NOT_EMPTY);
    }
}
