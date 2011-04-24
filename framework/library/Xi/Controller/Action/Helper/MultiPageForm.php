<?php
/**
 * A tool for presenting a single form on multiple pages and persisting the
 * data posted to the form until it is processed
 * 
 * @category    Xi
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_Action
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Controller_Action_Helper_MultiPageForm extends Xi_Controller_Action_Helper_Abstract 
{
    /**
     * Default session storage namespace
     */
    const DEFAULT_SESSION_NAMESPACE = __CLASS__;
    
    /**
     * Default session storage member
     */
    const DEFAULT_SESSION_MEMBER = 'form';
    
    /**
     * @var Zend_Form
     */
    protected $_form;
    
    /**
     * @param Zend_Form $form
     * @return Xi_Controller_Action_Helper_MultiPageForm
     */
    public function direct(Zend_Form $form)
    {
        $this->setForm($form);
        return $this;
    }
    
    /**
     * @param Zend_Form $form
     * @return Xi_Controller_Action_Helper_MultiPageForm
     */
    public function setForm(Zend_Form $form)
    {
        $this->_form = $form;
        return $this;
    }
    
    /**
     * @return Zend_Form|null
     */
    public function getForm()
    {
        return $this->_form;
    }
    
    /**
     * Set storage object
     *
     * @param Xi_Storage_Interface $storage
     * @return Xi_Controller_Action_Helper_MultiPageForm
     */
    public function setStorage(Xi_Storage_Interface $storage)
    {
        $this->_storage = $storage;
        return $this;
    }
    
    /**
     * Retrieve Zend_Form storage
     *
     * @return Xi_Storage_Interface
     */
    public function getStorage()
    {
        if (null === $this->_storage) {
            $this->_storage = $this->getDefaultStorage();
        }
        return $this->_storage;
    }
    
    /**
     * Retrieve default storage object
     *
     * @return Xi_Storage_Interface
     */
    public function getDefaultStorage()
    {
        return new Xi_Storage_Session(self::DEFAULT_SESSION_NAMESPACE, self::DEFAULT_SESSION_MEMBER);
    }
    
    /**
     * Persist Zend_Form to storage after the action has ran
     *
     * @return void
     */
    public function postDispatch()
    {
        $form = $this->getForm();
        if ($form) {
            $this->getStorage()->write($form);
        }
    }
}