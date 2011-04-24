<?php
/**
 * @category    Xi
 * @package     Xi_Acl
 * @subpackage  Xi_Acl_Assert
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Acl_Assert_Lazy extends Xi_Acl_Assert_Outer
{
    /**
     * @param string $class
     * @return void
     */
    public function __construct($class)
    {
        if (!is_string($class)) {
            $error = sprintf("Expecting string, %s given", Xi_Class::getType($class));
            throw new Xi_Acl_Assert_Exception($error);
        }
        
        if (!Xi_Class::implementsInterface($class, 'Zend_Acl_Assert_Interface')) {
            $error = sprintf("Class %s does not implement Zend_Acl_Assert_Interface", $class);
            throw new Xi_Acl_Assert_Exception($error);
        }
        
        $this->_assert = $class;
    }
    
    /**
     * @return Zend_Acl_Assert_Interface
     */
    public function getAssert()
    {
        if (is_string($this->_assert)) {
            $class = $this->_assert;
            $this->_assert = new $class;
        }
        return $this->_assert;
    }
}
