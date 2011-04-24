<?php
/**
 * @category    Xi
 * @package     Xi_Acl
 * @subpackage  Xi_Acl_Assert
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
abstract class Xi_Acl_Assert_Composite implements Zend_Acl_Assert_Interface
{
    /**
     * @var array<Zend_Acl_Assert_Interface>
     */
    protected $_asserts = array();
    
    /**
     * @param array<Zend_Acl_Assert_Interface> $asserts
     * @return void
     */
    public function __construct(array $asserts = array())
    {
        $this->_asserts = $asserts;
    }
    
    /**
     * @return array<Zend_Acl_Assert_Interface>
     */
    public function getAsserts()
    {
        return $this->_asserts;
    }
    
    /**
     * @param Zend_Acl_Assert_Interface $assert
     * @param boolean $prepend
     * @return Xi_Acl_Assert_Composite
     */
    public function addAssert($assert, $prepend = false)
    {
         if ($prepend) {
             array_unshift($this->_asserts, $assert);
         } else {
             $this->_asserts[] = $assert;
         }
         return $this;
    }
}
