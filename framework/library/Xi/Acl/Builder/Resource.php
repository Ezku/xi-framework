<?php
/**
 * @category    Xi
 * @package     Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Acl_Builder_Resource extends Xi_Acl_Builder_Abstract 
{
    /**
     * Parent resource to inherit added resources from
     *
     * @var Zend_Acl_Resource
     */
    protected $_resource;
    
    /**
     * Set parent resource
     *
     * @param string $parent
     * @return Xi_Acl_Builder_Resource
     */
    public function setParentResource($parent)
    {
        $this->_resource = $parent;
        return $this;
    }
    
    /**
     * Get parent resource
     *
     * @return Zend_Acl_Resource
     */
    public function getParentResource()
    {
        return $this->_resource;
    }
    
    /**
     * @param string|Zend_Acl_Resource $resource
     * @return Zend_Acl_Resource
     */
    public function formatResource($resource)
    {
        if ($parent = $this->getParentResource()) {
            $resource = $resource instanceof Zend_Acl_Resource ? $resource->getResourceId() : $resource;
            return new Zend_Acl_Resource($parent->getResourceId() . '.' . $resource);
        }
        return $resource instanceof Zend_Acl_Resource ? $resource : new Zend_Acl_Resource($resource);
    }
    
    /**
     * Create Acl resources out of configuration data
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
                    throw new Xi_Acl_Builder_Resource_Exception($error);
                }
                $this->addResource($value);
            } else {
                $resource = $this->addResource($key);
                $child = $this->_getChild()->setParentResource($resource);
                if ($value instanceof Zend_Config) {
                    $this->setAcl($child->build($value));
                } else {
                    $child->addResource($value);
                    $this->setAcl($child->getAcl());
                }
            }
        }
        
        return $this->getAcl();
    }
    
    /**
     * Add resource to Acl
     *
     * @param string $resource
     * @return Zend_Acl_Resource
     */
    public function addResource($resource)
    {
        $resource = $this->formatResource($resource);
        $this->getAcl()->add($resource, $this->getParentResource());
        return $resource;
    }
}
