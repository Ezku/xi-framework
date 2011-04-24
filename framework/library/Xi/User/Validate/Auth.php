<?php
/**
 * @category    Xi
 * @package     Xi_User
 * @package     Xi_User_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_User_Validate_Auth extends Zend_Validate_Abstract
{
    /**
     * @var Xi_User_Auth_Adapter_Interface
     */
    protected $_adapter;
    
    /**
     * @var string
     */
    protected $_identityElementName;
    
    /**
     * @var string
     */
    protected $_credentialElementName;
    
    /**
     * @param Xi_User_Auth_Adapter_Interface $adapter
     */
    public function __construct(Xi_User_Auth_Adapter_Interface $adapter, $identityElementName = 'username', $credentialElementName = 'password')
    {
        $this->_adapter = $adapter;
        $this->_identityElementName = $identityElementName;
        $this->_credentialElementName = $credentialElementName;
    }
    
    /**
     * @return Xi_User_Auth_Adapter_Interface
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }
    
    /**
     * @return string
     */
    public function getIdentityElementName()
    {
        return $this->_identityElementName;
    }
    
    /**
     * @return string
     */
    public function getCredentialElementName()
    {
        return $this->_credentialElementName;
    }
    
    /**
     * @param null|array $context
     * @return false|string
     */
    public function getIdentity($context)
    {
        if (isset($context[$this->_identityElementName])) {
            return $context[$this->_identityElementName];
        }
        return false;
    }
    
    /**
     * @param null|array $context
     * @return false|string
     */
    public function getCredentials($context)
    {
        if (isset($context[$this->_credentialElementName])) {
            return $context[$this->_credentialElementName];
        }
        return false;
    }
    
    /**
     * @param mixed $value
     * @param null|array $context
     */
    public function isValid($value, $context = null)
    {
        $identity = $this->getIdentity($context);
        if (!$identity) {
            return false;
        }
        $credentials = $this->getCredentials($context);
        if (!$credentials) {
            return false;
        }
        
        $result = $this->_adapter->authenticate($identity, $credentials);
        if ($result->isValid()) {
            return true;
        }
        
        foreach ($result->getMessages() as $message) {
            $this->_messages[] = $message;
        }
        return false;
    }
}
