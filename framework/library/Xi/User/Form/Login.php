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
 * @subpackage  Xi_User_Form
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_User_Form_Login extends Zend_Form
{
    /**
     * @var Xi_User_Validate_Auth
     */
    protected $_auth;
    
    /**
     * @var array
     */
    protected $_identityElementOptions = array();
    
    /**
     * @var array
     */
    protected $_credentialElementOptions = array();
    
    /**
     * @param Xi_User_Auth_Adapter_Interface|Xi_User_Validator_Auth $auth
     * @param mixed $options
     * @return void
     */
    public function __construct($auth, $options = null)
    {
        if ($auth instanceof Xi_User_Auth_Adapter_Interface) {
            $auth = new Xi_User_Validate_Auth($auth);
        } elseif (!$auth instanceof Xi_User_Validate_Auth) {
            $error = sprintf("Argument must be an instance of Xi_User_Auth_Adapter_Interface or Xi_User_Validate_Auth, %s given", Xi_Class::getType($auth));
            throw new Xi_User_Exception($error);
        }
        
        $this->_auth = $auth;
        
        parent::__construct($options);
    }

    /**
     * Set form state from options array
     * 
     * @param  array $options 
     * @return Zend_Form
     */
    public function setOptions(array $options)
    {
        if (isset($options['identityElementOptions'])) {
            $this->setIdentityElementOptions($options['identityElementOptions']);
        }
        if (isset($options['credentialElementOptions'])) {
            $this->setCredentialElementOptions($options['credentialElementOptions']);
        }
        return parent::setOptions($options);
    }
    
    /**
     * @var array
     * @return Xi_User_Form_Login
     */
    public function setIdentityElementOptions($options)
    {
        if (is_scalar($options)) {
            $options = array('label' => $options);
        }
        $this->_identityElementOptions = $options;
        return $this;
    }
    
    /**
     * @var array
     * @return Xi_User_Form_Login
     */
    public function setCredentialElementOptions($options)
    {
        if (is_scalar($options)) {
            $options = array('label' => $options);
        }
        $this->_credentialElementOptions = $options;
        return $this;
    }
    
    /**
     * @return array
     */
    public function getIdentityElementOptions()
    {
        return $this->_identityElementOptions + array('name' => $this->getAuthValidator()->getIdentityElementName());
    }
    
    /**
     * @return array
     */
    public function getCredentialElementOptions()
    {
        return $this->_credentialElementOptions + array('name' => $this->getAuthValidator()->getCredentialElementName());
    }
    
    /**
     * @return void
     */
    public function init()
    {
        $this->addElement($this->getIdentityElement());
        $this->addElement($this->getCredentialElement());
    }
    
    /**
     * @return Zend_Form_Element
     */
    public function getIdentityElement()
    {
        $element = new Zend_Form_Element_Text($this->getIdentityElementOptions());
        $element->setRequired(true);
        return $element;
    }
    
    /**
     * @return Zend_Form_Element
     */
    public function getCredentialElement()
    {
        $element = new Zend_Form_Element_Password($this->getCredentialElementOptions());
        $element->setRequired(true);
        $element->addValidator($this->getAuthValidator());
        return $element;
    }
    
    /**
     * @return Xi_User_Validate_Auth
     */
    public function getAuthValidator()
    {
        return $this->_auth;
    }
}
