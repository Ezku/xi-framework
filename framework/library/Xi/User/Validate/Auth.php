<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.xi-framework.com>.
 */

/**
 * @category    Xi
 * @package     Xi_User
 * @package     Xi_User_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
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
