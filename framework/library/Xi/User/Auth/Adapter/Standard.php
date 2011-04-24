<?php
/**
 * @category    Xi
 * @package     Xi_User
 * @package     Xi_User_Auth
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_User_Auth_Adapter_Standard implements Xi_User_Auth_Adapter_Interface
{
    /**
     * @var Xi_Auth_Adapter_Standard_Interface
     */
    protected $_adapter;
    
    /**
     * @param Xi_Auth_Adapter_Standard_Interface $adapter
     */
    public function __construct(Xi_Auth_Adapter_Standard_Interface $adapter)
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
