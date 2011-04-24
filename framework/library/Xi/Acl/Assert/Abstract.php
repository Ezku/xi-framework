<?php
/**
 * @category    Xi
 * @package     Xi_Acl
 * @subpackage  Xi_Acl_Assert
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
abstract class Xi_Acl_Assert_Abstract implements Zend_Acl_Assert_Interface
{
    /**
     * @var Zend_Controller_Request_Abstract
     */
    protected $_request;
    
    /**
     * @return Zend_Controller_Request_Abstract
     */
    public function getRequest()
    {
        if (null === $this->_request) {
            $this->_request = Zend_Controller_Front::getInstance()->getRequest();
        }
        return $this->_request;
    }
}
