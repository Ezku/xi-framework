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
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_User
{
    /**
     * Default session storage namespace
     */
    const DEFAULT_SESSION_NAMESPACE = __CLASS__;
    
    /**
     * Default session storage member
     */
    const DEFAULT_SESSION_MEMBER = 'instance';
    
    /**
     * Singleton instance
     * 
     * @var Xi_User
     */
    protected static $_instance;
    
    /**
     * Storage instance
     * 
     * @var Xi_Storage_Interface
     */
    protected static $_storage;
    
    /**
     * @var Zend_Acl
     */
    protected $_acl;
    
    /**
     * Acl role
     * 
     * @var string
     */
    protected $_role;
    
    /**
     * Default Acl role
     * 
     * @var string
     */
    protected $_defaultRole = 'guest';
    
    /**
     * Key string for a parameter that the role will be fetched from if it is
     * set.
     * 
     * @var string
     */
    protected $_roleKey = 'role';
    
    /**
     * @var string|null
     */
    protected $_identity;
    
    /**
     * @var string|null
     */
    protected $_identityKey = 'username';
    
    /**
     * @var array
     */
    protected $_params = array();
    
    /**
     * Events provided:
     *  __destruct (when an exception is thrown when trying to persist instance)
     *  setIdentity
     *  setRole
     *  setParams
     * 
     * @var Xi_Event_Dispatcher
     */
    protected $_eventDispatcher;
    
    /**
     * Get singleton user instance. If the storage retrieved from
     * {@link getStorage()} contains an instance of $class, return that
     * instance. Otherwise a new instance of $class is created.
     *
     * @param string $class
     * @return Xi_User
     */
    public static function getInstance($class = __CLASS__)
    {
        if ((null === self::$_instance) || (!self::$_instance instanceof $class)) {
            $storage = self::getStorage();
            if (!$storage->isEmpty() && ($instance = $storage->read()) && ($instance instanceof $class)) {
                self::$_instance = $instance;
            } else {
                self::$_instance = new $class;
            }
        }
        return self::$_instance;
    }
    
    /**
     * Check whether singleton is instantiated
     * 
     * @return boolean
     */
    public static function hasInstance()
    {
        return isset(self::$_instance);
    }
    
    /**
     * Set singleton user instance.
     * 
     * @param Xi_User $user
     * @return Xi_User
     */
    public static function setInstance(Xi_User $user)
    {
        self::$_instance = $user;
        return $user;
    }
    
    /**
     * Clear singleton instance
     * 
     * @return void
     */
    public static function clearInstance()
    {
        $storage = self::getStorage();
        if (!$storage->isEmpty()) {
            $storage->clear();
        }
        self::$_instance = null;
    }
    
    /**
     * Set storage object. Resets the current user instance. Consequent calls
     * to getInstance() are guaranteed to provide a user retrieved from the
     * storage provided to this method.
     * 
     * @param Xi_Storage_Interface
     * @return void
     */
    public static function setStorage(Xi_Storage_Interface $storage)
    {
        self::$_instance = null;
        self::$_storage = $storage;
    }
    
    /**
     * Get storage object
     *
     * @return Xi_Storage_Interface
     */
    public static function getStorage()
    {
        if (null === self::$_storage) {
            self::$_storage = self::getDefaultStorage();
        }
        return self::$_storage;
    }
    
    /**
     * Get default storage object
     *
     * @return Xi_Storage_Interface
     */
    public static function getDefaultStorage()
    {
        return new Xi_Storage_Session(self::DEFAULT_SESSION_NAMESPACE, self::DEFAULT_SESSION_MEMBER);
    }
    
    /**
     * Clear storage instance
     *
     * @return void
     */
    public static function clearStorage()
    {
        self::$_storage = null;
    }
    
    /**
     * Xi_User can be constructed from the outside, but only the singleton
     * instance will be persisted to storage on shutdown
     * 
     * @return void
     */
    public function __construct()
    {
        $this->init();
    }
    
    /**
     * Template method called on construction
     * 
     * @return void
     */
    public function init()
    {}
    
    /**
     * Provide an array of properties to be serialized
     * 
     * @return array
     */
    public function __sleep()
    {
        return array(
            '_defaultRole',
            '_identity',
            '_identityKey',
            '_params',
            '_role',
            '_roleKey'
        );
    }
    
    /**
     * Persist singleton instance to storage on shutdown
     *
     * @return void
     */
    public function __destruct()
    {
        if (($this === self::$_instance)) {
            try {
                self::getStorage()->write($this);
            } catch (Zend_Session_Exception $e) {
                $this->getEventDispatcher()->notify(new Xi_Event(__FUNCTION__, $this, array('exception' => $e)));
            }
        }
    }
    
    /**
     * @return Xi_Event_Dispatcher
     */
    public function getEventDispatcher()
    {
        if (null === $this->_eventDispatcher) {
            $this->_eventDispatcher = new Xi_Event_Dispatcher();
            $this->_eventDispatcher->attach('setParams', new Xi_Event_Listener_Callback(array($this, 'refreshRole')));
            $this->_eventDispatcher->attach('setParams', new Xi_Event_Listener_Callback(array($this, 'refreshIdentity')));
        }
        return $this->_eventDispatcher;
    }
    
    /**
     * @param Zend_Acl $acl
     * @return Xi_User
     */
    public function setAcl(Zend_Acl $acl)
    {
        $this->_acl = $acl;
        return $this;
    }
    
    /**
     * @return Zend_Acl
     */
    public function getAcl()
    {
        if (null === $this->_acl) {
            $this->_acl = $this->getDefaultAcl();
        }
        return $this->_acl;
    }
    
    /**
     * @return Zend_Acl
     */
    public function getDefaultAcl()
    {
        return new Zend_Acl;
    }
    
    /**
     * Set Acl role
     * 
     * @param string $role
     * @return Xi_User
     */
    public function setRole($role)
    {
        if (!$this->getAcl()->hasRole($role)) {
            $error = sprintf("Unknown Acl role '%s'", $role);
            throw new Xi_User_Exception($error);
        }
        $event = new Xi_Event(__FUNCTION__, $this, array('old' => $this->_role, 'new' => $role));
        $this->_role = $role;
        $this->getEventDispatcher()->notify($event);
        return $this;
    }
    
    /**
     * Get Acl role
     * 
     * @return string
     */
    public function getRole()
    {
        if (null === $this->_role) {
            $role = $this->getDefaultRole();
            if ($roleKey = $this->getRoleKey()) {
                $role = $this->getParam($roleKey, $role);
            }
            $this->setRole($role);
        }
        return $this->_role;
    }
    
    /**
     * Set default Acl role
     *
     * @param string $role
     * @return Xi_User
     */
    public function setDefaultRole($role)
    {
        $this->_defaultRole = $role;
        return $this;
    }
    
    /**
     * Get default Acl role
     *
     * @return string
     */
    public function getDefaultRole()
    {
        return $this->_defaultRole;
    }
    
    /**
     * Set key string for role parameter
     * 
     * @param string $key
     * @return Xi_User
     */
    public function setRoleKey($key)
    {
        $this->_roleKey = $key;
        return $this;
    }
    
    /**
     * Get key string for role parameter
     *
     * @return string
     */
    public function getRoleKey()
    {
        return $this->_roleKey;
    }
    
    /**
     * Set role from parameters if available
     * 
     * @return void
     */
    public function refreshRole()
    {
        if (($roleKey = $this->getRoleKey()) && $this->hasParam($roleKey)) {
            $this->setRole($this->getParam($roleKey));
        }
    }
    
    /**
     * Check whether user identity is known
     *
     * @return boolean
     */
    public function hasIdentity()
    {
        return false !== $this->getIdentity();
    }
    
    /**
     * Set user identity
     *
     * @param string $identity
     * @return Xi_User
     */
    public function setIdentity($identity)
    {
        $event = new Xi_Event(__FUNCTION__, $this, array('new' => $identity, 'old' => $this->_identity));
        $this->_identity = $identity;
        $this->getEventDispatcher()->notify($event);
        return $this;
    }
    
    /**
     * Get user identity
     *
     * @return false|string
     */
    public function getIdentity()
    {
        if (null === $this->_identity) {
            $auth = Zend_Auth::getInstance();
            $identity = $auth->hasIdentity() ? $auth->getIdentity() : false;
            if ($identityKey = $this->getIdentityKey()) {
                $identity = $this->getParam($identityKey, $identity);
            }
            $this->setIdentity($identity);
        }
        return $this->_identity;
    }
    
    /**
     * Get key string for user identity
     *
     * @return null|string
     */
    public function getIdentityKey()
    {
        return $this->_identityKey;
    }
    
    /**
     * Set key string for user identity
     *
     * @param string $identityKey
     * @return Xi_User
     */
    public function setIdentityKey($identityKey)
    {
        $this->_identityKey = $identityKey;
        return $this;
    }
    
    /**
     * Set identity from parameters if available
     * 
     * @return void
     */
    public function refreshIdentity()
    {
        if (($identityKey = $this->getIdentityKey()) && $this->hasParam($identityKey)) {
            $this->setIdentity($this->getParam($identityKey));
        }
    }
    
    /**
     * Check whether user is authenticated (ie. has an identity)
     *
     * @return boolean
     */
    public function isAuthed()
    {
        return $this->hasIdentity();
    }
    
    /**
     * Check whether action is allowed for user
     *
     * @param Xi_Acl_Action_Interface $action
     * @return boolean
     */
    public function isAllowed(Xi_Acl_Action_Interface $action)
    {
        $action->setRole($this->getRole());
        return $action->isAllowed($this->_acl);
    }
    
    /**
     * Set a parameter
     * 
     * @param string $name
     * @param mixed $value
     * @return Xi_User
     */
    public function setParam($name, $value)
    {
        return $this->setParams(array($name => $value) + $this->_params);
    }
    
    /**
     * Set array of parameters.
     *
     * @param array $params
     * @return Xi_User
     */
    public function setParams(array $params)
    {
        $event = new Xi_Event(__FUNCTION__, $this, array('new' => $params, 'old' => $this->_params));
        $this->_params = $params;
        $this->getEventDispatcher()->notify($event);
        return $this;
    }
    
    /**
     * Get parameters
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }
    
    /**
     * Check whether parameter is defined
     *
     * @param string $param
     * @return boolean
     */
    public function hasParam($param)
    {
        return isset($this->_params[$param]);
    }
    
    /**
     * Retrieve parameter value or a default value if not defined
     *
     * @param string $param
     * @param mixed $default
     * @return mixed
     */
    public function getParam($param, $default = null)
    {
        if ($this->hasParam($param)) {
            return $this->_params[$param];
        }
        return $default;
    }
}
