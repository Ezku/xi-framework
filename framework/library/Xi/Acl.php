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
 * Extends the default Acl so that isAllowed() can be provided with parameters
 * that will be forwarded to any possible Assertions handling the authorization.
 * 
 * @see			Xi_User_View_Helper_IsAllowed
 * @category    Xi
 * @package     Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Acl extends Zend_Acl
{
    /**
     * @var array
     */
    protected $_params = array();
    
    /**
     * @param Zend_Acl_Role_Interface|string $role
     * @param Zend_Acl_Resource_Interface|string $resource
     * @param string $privilege
     * @param array $params
     * @return boolean
     */
    public function isAllowed($role = null, $resource = null, $privilege = null, $params = array())
    {
        if (!empty($params)) {
            $this->setParams($params);
        }
        
        $value = parent::isAllowed($role, $resource, $privilege);
        
        if (!empty($params)) {
            $this->clearParams();
        }
        
        return $value;
    }

    /**
     * Modified version of 
     * 
     * @param  Zend_Acl_Resource_Interface $resource
     * @param  Zend_Acl_Role_Interface     $role
     * @param  string                      $privilege
     * @return string|null
     */
    protected function _getRuleType(Zend_Acl_Resource_Interface $resource = null, Zend_Acl_Role_Interface $role = null,
                                    $privilege = null)
    {
        // get the rules for the $resource and $role
        if (null === ($rules = $this->_getRules($resource, $role))) {
            return null;
        }

        // follow $privilege
        if (null === $privilege) {
            if (isset($rules['allPrivileges'])) {
                $rule = $rules['allPrivileges'];
            } else {
                return null;
            }
        } else if (!isset($rules['byPrivilegeId'][$privilege])) {
            return null;
        } else {
            $rule = $rules['byPrivilegeId'][$privilege];
        }

        // check assertion if necessary
        if (null === $rule['assert'] || $this->_assert($rule['assert'], $role, $resource, $privilege)) {
            return $rule['type'];
        } else if (null !== $resource || null !== $role || null !== $privilege) {
            return null;
        } else if (self::TYPE_ALLOW === $rule['type']) {
            return self::TYPE_DENY;
        } else {
            return self::TYPE_ALLOW;
        }
    }
    
    /**
     * @param  Zend_Acl_Assert_Interface   $assert
     * @param  Zend_Acl_Role_Interface     $role
     * @param  Zend_Acl_Resource_Interface $resource
     * @param  string                      $privilege
     */
    protected function _assert(Zend_Acl_Assert_Interface $assert, Zend_Acl_Role_Interface $role = null, $resource = null,
                                    $privilege = null)
    {
        return $assert->assert($this, $role, $resource, $privilege, $this->getParams());
    }
    
    /**
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * @param array $params
     * @return Xi_Acl
     */
    public function setParams(array $params)
    {
        $this->_params = $params;
        return $this;
    }
    
    /**
     * @param string $name
     * @return mixed
     */
    public function getParam($name, $default = null)
    {
        if (!isset($this->_params[$name])) {
            return $default;
        }
        return $this->_params[$name];
    }
    
    /**
     * @param string $name
     * @return boolean
     */
    public function hasParam($name)
    {
        return isset($this->_params[$name]);
    }
    
    /**
     * @param string $name
     * @param mixed $value
     * @return Xi_Acl
     */
    public function setParam($name, $value)
    {
        $this->_params[$name] = $value;
        return $this;
    }
    
    /**
     * @return Xi_Acl
     */
    public function clearParams()
    {
        $this->_params = array();
    }
}
