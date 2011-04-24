<?php
/**
 * @category    Xi
 * @package     Xi_Auth
 * @subpackage  Xi_Auth_Adapter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Auth_Adapter_Doctrine extends Xi_Auth_Adapter_Abstract
{
    /**
     * Field in the record used to indicate successfully matched credentials
     */
    const CREDENTIAL_VALIDITY_FIELD = 'xi_auth_credential_match';
    
    /**
     * @var string
     */
    protected $_tableName;
    
    /**
     * @var string
     */
    protected $_identityColumn = 'username';
    
    /**
     * @var string
     */
    protected $_credentialColumn = 'password';
    
    /**
     * @var string
     */
    protected $_credentialTreatment = 'MD5(?)';
    
    /**
     * @var Zend_Auth_Result
     */
    protected $_result;
    
    /**
     * @var Doctrine_Record
     */
    protected $_resultRecord;
    
    /**
     * @param string $tableName
     * @param string $identityColumn
     * @param string $credentialColumn
     * @param string $credentialTreatment
     * @return void
     */
    public function __construct($tableName, $identityColumn = null, $credentialColumn = null, $credentialTreatment = null)
    {
        $this->setTableName($tableName);
        if (null !== $identityColumn) {
            $this->setIdentityColumn($identityColumn);
        }
        if (null !== $credentialColumn) {
            $this->setCredentialColumn($credentialColumn);
        }
        if (null !== $credentialTreatment) {
            $this->setCredentialTreatment($credentialTreatment);
        }
    }
    
    /**
     * @param string $tableName
     * @return Xi_Auth_Adapter_Doctrine
     */
    public function setTableName($tableName)
    {
        $this->_tableName = $tableName;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->_tableName;
    }
    
    /**
     * @param string $identityColumn
     * @return Xi_Auth_Adapter_Doctrine
     */
    public function setIdentityColumn($identityColumn)
    {
        $this->_identityColumn = $identityColumn;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getIdentityColumn()
    {
        return $this->_identityColumn;
    }
    
    /**
     * @param string $credentialColumn
     * @return Xi_Auth_Adapter_Doctrine
     */
    public function setCredentialColumn($credentialColumn)
    {
        $this->_credentialColumn = $credentialColumn;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getCredentialColumn()
    {
        return $this->_credentialColumn;
    }
    
    /**
     * @param string $credentialTreatment
     * @return Xi_Auth_Adapter_Doctrine
     */
    public function setCredentialTreatment($credentialTreatment)
    {
        $this->_credentialTreatment = $credentialTreatment;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getCredentialTreatment()
    {
        return $this->_credentialTreatment;
    }

    /**
     * authenticate() - defined by Zend_Auth_Adapter_Interface.  This method is called to 
     * attempt an authenication.  Previous to this call, this adapter would have already
     * been configured with all nessissary information to successfully connect to a database
     * table and attempt to find a record matching the provided identity.
     *
     * @throws Zend_Auth_Adapter_Exception if answering the authentication query is impossible
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        $query = $this->_getQuery();
        $result = $query->execute($this->_getQueryParams());
        if ($this->_isValidCollection($result)) {
            $record = $result->getFirst();
            if ($this->_isValidRecord($record)) {
                $this->_setResultRecord($record);
                return $this->_createResult();
            }
        }
        return $this->_getResult();
    }
    
    /**
     * @param Doctrine_Record $record
     * @return Xi_Auth_Adapter_Doctrine
     */
    protected function _setResultRecord($record)
    {
        $this->_resultRecord = $record;
        return $this;
    }
    
    /**
     * @return Doctrine_Record
     */
    public function getResultRecord()
    {
        unset($this->_resultRecord->{self::CREDENTIAL_VALIDITY_FIELD});
        return $this->_resultRecord;
    }
    
    /**
     * @param Doctrine_Collection $result
     * @return boolean
     */
    protected function _isValidCollection($result)
    {
        switch (count($result)) {
            case 0:
                $this->_setResult($this->_createResult(
                    Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,
                    array('A record with the supplied identity could not be found.')
                ));
                return false;
            case 1:
                return true;
            default:
                $this->_setResult($this->_createResult(
                    Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS,
                    array('More than one record matches the supplied identity.')
                ));
                return false;
        }
    }
    
    /**
     * @param Doctrine_Record $record
     * @return boolean
     */
    protected function _isValidRecord($record)
    {
        if (empty($record->{self::CREDENTIAL_VALIDITY_FIELD})) {
            $this->_setResult($this->_createResult(
                Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,
                array('Supplied credential is invalid.')
            ));
            return false;
        }
        return true;
    }
    
    /**
     * @param Zend_Auth_Result $result
     * @return Xi_Auth_Adapter_Doctrine
     */
    protected function _setResult($result)
    {
        $this->_result = $result;
        return $this;
    }
    
    /**
     * @return Zend_Auth_Result
     */
    protected function _getResult()
    {
        if (null === $this->_result) {
            $this->_result = $this->_createResult();
        }
        return $this->_result;
    }
    
    /**
     * @return Doctrine_Query
     */
    protected function _getQuery()
    {
        $alias = $this->_getRecordAlias();
        return Doctrine_Query::create()
            ->select(sprintf('%s.*, (%s) as %s', $alias, $this->_getCredentialCondition(), self::CREDENTIAL_VALIDITY_FIELD))
            ->from(sprintf('%s %s', $this->getTableName(), $alias))
            ->where(sprintf('%s', $this->_getIdentityCondition()));
    }
    
    /**
     * @return string
     */
    protected function _getRecordAlias()
    {
        return 'u';
    }
    
    /**
     * @return string
     */
    protected function _getIdentityCondition()
    {
        return sprintf('%s.%s = :identity', $this->_getRecordAlias(), $this->getIdentityColumn());
    }
    
    /**
     * @return string
     */
    protected function _getCredentialCondition()
    {
        return sprintf('%s.%s = %s', $this->_getRecordAlias(), $this->getCredentialColumn(), $this->_getCredentialTerm());
    }
    
    /**
     * @return string
     */
    protected function _getCredentialTerm()
    {
        if (!strlen($this->_credential)) {
            $error = "Credential was not provided prior to authentication attempt";
            throw new Xi_Auth_Adapter_Exception($error);
        }
        return str_replace('?', sprintf("'%s'", $this->_credential), $this->getCredentialTreatment());
    }
    
    /**
     * @return array
     */
    protected function _getQueryParams()
    {
        if (!strlen($this->_identity)) {
            $error = "Identity was not provided prior to authentication attempt";
            throw new Xi_Auth_Adapter_Exception($error);
        }
        return array('identity' => $this->_identity); 
    }
}
