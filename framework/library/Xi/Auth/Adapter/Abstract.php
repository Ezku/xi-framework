<?php
/**
 * @category    Xi
 * @package     Xi_Auth
 * @subpackage  Xi_Auth_Adapter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
abstract class Xi_Auth_Adapter_Abstract implements Xi_Auth_Adapter_Standard_Interface
{
    /**
     * @var string
     */
    protected $_identity;
    
    /**
     * @var string
     */
    protected $_credential;

    /**
     * setIdentity() - set the value to be used as the identity
     *
     * @param  string $value
     * @return Xi_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setIdentity($value)
    {
        $this->_identity = $value;
        return $this;
    }

    /**
     * setCredential() - set the credential value to be used, optionally can specify a treatment
     * to be used, should be supplied in parameterized form, such as 'MD5(?)' or 'PASSWORD(?)'
     *
     * @param  string $credential
     * @return Xi_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setCredential($credential)
    {
        $this->_credential = $credential;
        return $this;
    }
    
    /**
     * Create a Zend_Auth_Result for an {@link authenticate()} request. If no
     * parameters are provided, creates a result object indicating a successful
     * authentication. 
     * 
     * @param int $code
     * @param string|array $messages
     * @return Zend_Auth_Result
     */
    protected function _createResult($code = Zend_Auth_Result::SUCCESS, $messages = array('Authentication successful.'))
    {
        if (null === $this->_identity) {
            throw new Xi_Auth_Adapter_Exception("Cannot create authentication result when no identity set");
        }
        return new Zend_Auth_Result($code, $this->_identity, (array) $messages);
    }
}
