<?php
/**
 * @category    Xi
 * @package     Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
abstract class Xi_Acl_Builder_Privilege_Abstract extends Xi_Acl_Builder_Resource
{
    /**
     * Role for which to set privileges
     *
     * @var string
     */
    protected $_role;
    
    /**
     * Privilege identifier (such as 'login', 'add', 'edit')
     * 
     * @var string
     */
    protected $_privilege;
    
    /**
     * Set role
     *
     * @param string $role
     * @return Xi_Acl_Builder_Privilege_Abstract
     */
    public function setRole($role)
    {
        $this->_role = $role;
        return $this;
    }
    
    /**
     * @return null|string
     */
    public function getRole()
    {
        return $this->_role;
    }
    
    /**
     * Set privilege
     *
     * @param string $privilege
     * @return Xi_Acl_Builder_Privilege_Abstract
     */
    public function setPrivilege($privilege)
    {
        $this->_privilege = $privilege;
        return $this;
    }
    
    /**
     * Get privilege
     *
     * @return string
     */
    public function getPrivilege()
    {
        return $this->_privilege;
    }
    
    /**
     * Get child builder
     *
     * @return Xi_Acl_Builder_Privilege_Abstract
     */
    protected function _getChild()
    {
        $child = parent::_getChild();
        if ($role = $this->getRole()) {
            $child->setRole($role);
        }
        if ($privilege = $this->getPrivilege()) {
            $child->setPrivilege($privilege);
        }
        if ($resource = $this->getParentResource()) {
            $child->setParentResource($resource);
        }
        return $child;
    }
    
    public function formatRole($role)
    {
        $acl = $this->getAcl();
        if (!$acl->hasRole($role)) {
            $error = sprintf("Role '%s' not found", $role);
            throw new Xi_Acl_Builder_Privilege_Exception($error);
        }
        return $acl->getRole($role);
    }
    
    /**
     * Attempt to format a value as a resource, return false on failure
     *
     * @param mixed $resource
     * @return false|null|string
     */
    public function formatResource($resource)
    {
        if (!(is_string($resource) || $resource instanceof Zend_Acl_Resource_Interface)) {
            return false;
        }
        
        if ($this->getPrivilege()) {
            return false;
        }
        
        if ($parent = $this->getParentResource()) {
            if ('*' == $resource) {
                $resource = $parent;
            } else {
                $resource = $parent . '.' . $resource;
            }
        } elseif ('*' == $resource) {
            return null;
        }
        
        $acl = $this->getAcl();
        
        if (!$acl->has($resource)) {
            return false;
        }
        
        return $resource;
    }
    
    /**
     * Attempt to format a value as a privilege, return false on failure
     *
     * @param mixed $privilege
     * @return false|null|string
     */
    public function formatPrivilege($privilege)
    {
        /**
         * Limit possible privileges to primitives: 'edit', 'delete' - not
         * 'edit.10', 'delete.13'.
         */
        if ($this->getPrivilege()) {
            return false;
        }
        if ('*' == $privilege) {
            return null;
        }
        return $privilege;
    }
    
    /**
     * @var array
     */
    protected $_asserts = array();
    
    /**
     * Attempt to format a value as an assertion, return false on failure
     *
     * @param mixed $assert
     * @return false|Zend_Acl_Assert_Interface
     */
    public function formatAssert($assert)
    {
        if (!$assert instanceof Zend_Acl_Assert_Interface) {
            if (!(class_exists($assert) && Xi_Class::implementsInterface($assert, 'Zend_Acl_Assert_Interface'))) {
                return false; 
            }
            $assert = new $assert;
        }
        
        return $this->getAssert($assert);
    }
    
    /**
     * Given an assert, check whether an assert has already been set for the
     * current resource/privilege pair and create a Xi_Acl_Assert_And instance
     * if needed
     * 
     * @param Zend_Acl_Assert_Interface $assert
     * @return Zend_Acl_Assert_Interface
     */
    public function getAssert($assert)
    {
        $resource = $this->getParentResource();
        $resource = is_object($resource) ? $resource->getResourceId() : (string) $resource;
        $privilege = (string) $this->getPrivilege();
        
        if (isset($this->_asserts[$resource][$privilege])) {
            $assert = $this->_asserts[$resource][$privilege] = new Xi_Acl_Assert_And(array(
                $this->_asserts[$resource][$privilege],
                $assert
            ));
        } elseif (isset($this->_asserts[$resource])) {
            $this->_asserts[$resource][$privilege] = $assert;
        } else {
            $this->_asserts[$resource] = array($privilege => $assert);
        }
        
        return $assert;
    }
    
    /**
     * Build a privilege list for the Acl
     *
     * @param Zend_Config $config
     * @return Zend_Acl
     * @throws Xi_Acl_Builder_Privilege_Exception
     */
    public function build(Zend_Config $config)
    {
        if (null === $this->getRole()) {
            throw new Xi_Acl_Builder_Privilege_Exception('No role set');
        }
        foreach ($config as $key => $value) {
            if (is_int($key)) {
                $this->buildListValue($value);
            } else {
                $this->buildAssociatedValue($key, $value);
            }
        }
        
        return $this->getAcl();
    }
    
    /**
     * Called in {@link build()} when an item with an integer index is
     * encountered
     * 
     * @param mixed $value
     * @return void
     * @throws Xi_Acl_Builder_Privilege_Exception
     */
    public function buildListValue($value)
    {
        if ($value instanceof Zend_Config) {
            $error = sprintf("Invalid contents for an integer index, received '%s' when expecting a string", Xi_Class::getType($value));
            throw new Xi_Acl_Builder_Privilege_Exception($error);
        } elseif ('*' == $value) {
            $error = "Invalid contents for integer index, received '*' when expecting an identifier";
            throw new Xi_Acl_Builder_Privilege_Exception($error);
        } else {
            $this->handleValue($value);
        }
    }
    
    /**
     * Called in {@link build()} when an item with a string index is
     * encountered
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     * @throws Xi_Acl_Builder_Privilege_Exception
     */
    public function buildAssociatedValue($key, $value)
    {
        $child = $this->handleKey($key);
        if ($value instanceof Zend_Config) {
            $this->setAcl($child->build($value));
        } else {
            if ('*' === $value) {
                $this->addPrivilege($child->getParentResource(), $child->getPrivilege());
            } else {
                $this->setAcl($child->handleValue($value)->getAcl());
            }
        }
    }
    
    /**
     * Try to handle a value as a new resource or privilege to be added to the
     * Acl by a child instance
     * 
     * @param string $value
     * @return Xi_Acl_Builder_Privilege_Abstract
     * @throws Xi_Acl_Builder_Privilege_Exception
     */
    public function handleKey($value)
    {
        $child = $this->_getChild();
        switch (true) {
            case false !== ($resource = $this->formatResource($value)):
                $child->setParentResource($resource);
            break;
            case false !== ($privilege = $this->formatPrivilege($value)):
                $child->setPrivilege($privilege);
            break;
            default:
                $error = sprintf("Invalid value '%s', did not map to a resource or privilege", $value);
                throw new Xi_Acl_Builder_Privilege_Exception($error);
        }
        return $child;
    }
    
    /**
     * Try to handle a value as a new resource, assertion or privilege to be
     * added to the Acl
     * 
     * @param mixed $value
     * @return Xi_Acl_Builder_Privilege_Abstract
     * @throws Xi_Acl_Builder_Privilege_Exception
     */
    public function handleValue($value)
    {
        switch (true) {
            case false !== ($resource = $this->formatResource($value)):
                $this->addPrivilege($resource, $this->getPrivilege());
            break;
            case false !== ($assert = $this->formatAssert($value)):
                $this->addPrivilege($this->getParentResource(), $this->getPrivilege(), $assert);
            break;
            case false !== ($privilege = $this->formatPrivilege($value)):
                $this->addPrivilege($this->getParentResource(), $privilege);
            break;
            default:
                $error = sprintf("Invalid value '%s', did not map to a resource, privilege or assertion", is_object($value) ? Xi_Class::getType($value) : $value);
                throw new Xi_Acl_Builder_Privilege_Exception($error);
        }
        return $this;
    }
    
    /**
     * @return Xi_Acl_Builder_Privilege_Operation_Interface
     */
    abstract public function getOperation();
    
    /**
     * Add privilege using operation from {@link getOperation()}
     *
     * @param string $resource
     * @param string $privilege
     * @param Zend_Acl_Assert_Interface $assert
     */
    public function addPrivilege($resource, $privilege, Zend_Acl_Assert_Interface $assert = null)
    {
        $this->getOperation()->addPrivilege($this->getAcl(), $this->formatRole($this->getRole()), $resource, $privilege, $assert);
    }
}
