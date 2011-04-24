<?php
/**
 * @category    Xi
 * @package     Xi_User
 * @package     Xi_User_Auth
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_User_Auth_Adapter_DbTable implements Xi_User_Auth_Adapter_Interface
{
    /**
     * @var Zend_Auth_Adapter_DbTable
     */
    protected $_adapter;
    
    /**
     * @param Zend_Auth_Adapter_DbTable $adapter
     */
    public function __construct(Zend_Auth_Adapter_DbTable $adapter)
    {
        $this->_adapter = $adapter;
    }

    /**
     * @param string $identity
     * @param string $credentials
     * @return Zend_Auth_Result
     */
    public function authenticate($identity, $credentials)
    {
        $this->_adapter->setIdentity($identity);
        $this->_adapter->setCredential($credentials);
        return $this->_adapter->authenticate();
    }
}
