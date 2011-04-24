<?php
/**
 * @category    Xi
 * @package     Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Acl_Builder_Role extends Xi_Acl_Builder_Abstract 
{
    /**
     * Parent role to inherit added roles from
     *
     * @var Zend_Acl_Role
     */
    protected $_role;
    
    /**
     * Set parent role
     *
     * @param Zend_Acl_Role $parent
     * @return Xi_Acl_Builder_Role
     */
    public function setParentRole(Zend_Acl_Role $parent)
    {
        $this->_role = $parent;
        return $this;
    }
    
    /**
     * Get parent role
     *
     * @return Zend_Acl_Role
     */
    public function getParentRole()
    {
        return $this->_role;
    }
    
    /**
     * @param string $role
     * @return Zend_Acl_Role
     */
    public function formatRole($role)
    {
        return new Zend_Acl_Role($role);
    }
    
    /**
     * Build an Acl object according to configuration
     * 
     * @param Zend_Config $config
     * @return Zend_Acl
     */
    public function build(Zend_Config $config)
    {
        foreach ($config as $key => $value) {
            if (is_int($key)) {
                if (!is_string($value)) {
                    $error = sprintf("Invalid contents for an integer index, received %s when expecting a string", Xi_Class::getType($value));
                    throw new Xi_Acl_Builder_Role_Exception($error);
                }
                $this->addRole($value);
            } else {
                $role = $this->addRole($key);
                $child = $this->_getChild()->setParentRole($role);
                if ($value instanceof Zend_Config) {
                    $this->setAcl($child->build($value));
                } else {
                    $child->addRole($value);
                    $this->setAcl($child->getAcl());
                }
            }
        }
        
        return $this->getAcl();
    }
    
    /**
     * Add role to Acl
     *
     * @param string $role
     * @return Zend_Acl_Role
     */
    public function addRole($role)
    {
        $role = $this->formatRole($role);
        $this->getAcl()->addRole($role, $this->getParentRole());
        return $role;
    }
}
