<?php
/**
 * @category    Xi
 * @package     Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Acl_Builder extends Xi_Acl_Builder_Abstract
{
    /**
     * @var array Xi_Acl_Builder_Interface objects or strings
     */
    protected $_builders = array(
        'roles' => 'Xi_Acl_Builder_Role',
        'resources' => 'Xi_Acl_Builder_Resource',
        'privileges' => 'Xi_Acl_Builder_Privilege',
    );
    
    /**
     * Create a new Xi_Acl_Builder instance
     * 
     * @return Xi_Acl_Builder
     */
    public static function create()
    {
        $args = func_get_args();
        return Xi_Class::create(__CLASS__, $args);
    }
    
    /**
     * Set builder for configuration namespace. Accepts either a
     * Xi_Acl_Builder_Interface object or a class name.
     * 
     * @param string $namespace
     * @param string|Xi_Acl_Builder_Interface $builder
     * @return Xi_Acl_Builder
     */
    public function setBuilder($namespace, $builder)
    {
        $this->_builders[$namespace] = $builder;
        return $this;
    }
    
    /**
     * Get builders for namespaces
     *
     * @return array Xi_Acl_Builder_Interface objects
     */
    public function getBuilders()
    {
        foreach ($this->_builders as &$builder) {
            if (is_string($builder)) {
                $builder = new $builder;
            }
        }
        return $this->_builders;
    }
    
    /**
     * Set builders for namespaces
     * 
     * @param array Xi_Acl_Builder_Interface objects or class names
     * @return Xi_Acl_Builder
     */
    public function setBuilders(array $builders)
    {
        $this->_builders = $builders;
        return $this;
    }
    
    /**
     * Apply registered builders to configuration
     *
     * @param Zend_Config $config
     * @return Zend_Acl
     */
    public function build(Zend_Config $config)
    {
        foreach ($this->getBuilders() as $namespace => $builder) {
            if (isset($config->$namespace)) {
                $builder->setAcl($this->getAcl());
                $this->setAcl($builder->build($config->$namespace));
            }
        }
        
        return $this->getAcl();
    }
}
