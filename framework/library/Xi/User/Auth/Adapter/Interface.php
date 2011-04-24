<?php
/**
 * Describes a front-end for any two-part authentication mechanism with
 * identity and credential components
 * 
 * @category    Xi
 * @package     Xi_User
 * @package     Xi_User_Auth
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
interface Xi_User_Auth_Adapter_Interface
{
    /**
     * @param string $identity
     * @param string $credentials
     * @return Zend_Auth_Result
     */
    public function authenticate($identity, $credentials);
}
