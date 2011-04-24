<?php
/**
 * @category    Xi
 * @package     Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Acl_Builder_Privilege extends Xi_Acl_Builder 
{
    protected $_builders = array(
        'allow' => 'Xi_Acl_Builder_Privilege_Allow',
        'deny' => 'Xi_Acl_Builder_Privilege_Deny'
    );
    
    /**
     * Build an Acl object according to configuration
     * 
     * @param Zend_Config $config
     * @return Zend_Acl
     */
    public function build(Zend_Config $config)
    {
        foreach ($this->getBuilders() as $namespace => $builder) {
            $builder->setAcl($this->getAcl());
            if (isset($config->$namespace)) {
                if (!$config->$namespace instanceof Zend_Config) {
                    $builder->setRole($config->$namespace);
                    $builder->addPrivilege(null, null);
                } else {
                    foreach ($config->$namespace as $role => $resources) {
                        if (is_int($role)) {
                            $builder->setRole($resources);
                            $builder->addPrivilege(null, null);
                        } else {
                            $builder->setRole($role);
                            if ($resources instanceof Zend_Config) {
                                $builder->build($resources);
                            } elseif (false !== ($resource = $builder->formatResource($resources))) {
                                $builder->addPrivilege($resource, null);
                            } else {
                                $error = sprintf('Invalid value "%s", did not map to a resource', $resources);
                                throw new Xi_Acl_Builder_Privilege_Exception($error);
                            }
                        }
                    }
                }
                $this->setAcl($builder->getAcl());
            }
        }
        
        return $this->getAcl();
    }
}
