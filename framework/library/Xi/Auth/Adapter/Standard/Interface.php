<?php
/**
 * @category    Xi
 * @package     Xi_Auth
 * @subpackage  Xi_Auth_Adapter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
interface Xi_Auth_Adapter_Standard_Interface extends Zend_Auth_Adapter_Interface
{
    /**
     * setIdentity() - set the value to be used as the identity
     *
     * @param  string $value
     * @return Xi_Auth_Adapter_Standard_Interface Provides a fluent interface
     */
    public function setIdentity($value);

    /**
     * setCredential() - set the credential value to be used, optionally can specify a treatment
     * to be used, should be supplied in parameterized form, such as 'MD5(?)' or 'PASSWORD(?)'
     *
     * @param  string $credential
     * @return Xi_Auth_Adapter_Standard_Interface Provides a fluent interface
     */
    public function setCredential($credential);
}
