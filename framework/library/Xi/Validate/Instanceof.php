<?php
/**
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Validate_Instanceof extends Zend_Validate_Abstract
{
    const NOT_OBJECT = 'notObject';
    const NOT_VALID_INSTANCE = 'notValidInstance';
    
    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_OBJECT => 'The value was not an object',
        self::NOT_VALID_INSTANCE => 'The object was not an instance of %s'
    );
    
    /**
     * @var array valid classes
     */
    protected $_classes = array();

    /**
     * Provide a class name or an array of class names to check against
     * 
     * @param string|array $classes
     * @return void
     */
    public function __construct($classes)
    {
        $classes = (array) $classes;
        foreach ($classes as $class) {
            if (!class_exists($class) && !interface_exists($class)) {
                throw new Xi_Validate_Exception("Class $class does not exist");
            }
            $this->_classes[] = $class;
        }
        
        $last = array_pop($classes);
        $message = join(', ', $classes);
        $message .= ' or '.$last;
        $this->_messageTemplates[self::NOT_VALID_INSTANCE] = sprintf($this->_messageTemplates[self::NOT_VALID_INSTANCE], $message); 
    }

    /**
     * Check that value is an instance of one of the classes provided in the
     * constructor
     *
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        if (!is_object($value)) {
            $this->_error(self::NOT_OBJECT);
            return false;
        }
        
        foreach ($this->_classes as $class) {
            if ($value instanceof $class) {
                return true;
            }
        }
        
        $this->_error(self::NOT_VALID_INSTANCE);
        return false;
    }
}
