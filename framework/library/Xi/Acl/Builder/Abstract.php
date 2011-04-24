<?php
/**
 * @category    Xi
 * @package     Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
abstract class Xi_Acl_Builder_Abstract implements Xi_Acl_Builder_Interface
{
    /**
     * Template Acl object 
     *
     * @var Zend_Acl
     */
    protected $_acl;
    
    /**
     * Provide a Zend_Acl instance to set the template Acl object
     * 
     * @param Zend_Acl $acl
     * @return void
     */
    public function __construct(Zend_Acl $acl = null)
    {
        if (null === $acl) {
            $acl = new Zend_Acl;
        }
        
        $this->setAcl($acl);
        $this->init();
    }
    
    /**
     * Template method triggered on construction
     * 
     * @return void
     */
    public function init()
    {}
    
    /**
     * Set the template Acl object
     * 
     * @param Zend_Acl $acl
     * @return Xi_Acl_Builder_Abstract
     */
    public function setAcl(Zend_Acl $acl)
    {
        $this->_acl = $acl;
        return $this;
    }
    
    /**
     * Retrieve the Acl template object
     *
     * @return Zend_Acl
     */
    public function getAcl()
    {
        return $this->_acl;
    }
    
    /**
     * Retrieve another instance of the current class with the same Acl template
     *
     * @return Xi_Acl_Builder_Abstract
     */
    protected function _getChild()
    {
        $class = get_class($this);
        return new $class($this->getAcl());
    }
}
