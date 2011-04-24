<?php
/**
 * @category    Xi_Test
 * @package     Xi_Acl
 * @group       Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Acl_Builder_RoleTest extends PHPUnit_Framework_TestCase
{
    public function testCanBuildFlatRoleList()
    {
        $roles = new Zend_Config(array('foo', 'bar'));
        $builder = new Xi_Acl_Builder_Role;
        $acl = $builder->build($roles);
        
        $this->assertTrue($acl->hasRole('foo'));
        $this->assertTrue($acl->hasRole('bar'));
    }
    
    public function testCanBuildInheritanceRoleList()
    {
        $roles = new Zend_Config(array('foo' => array('bar')));
        $builder = new Xi_Acl_Builder_Role;
        $acl = $builder->build($roles);
        
        $this->assertTrue($acl->inheritsRole('bar', 'foo'));
    }
    
    public function testCanBuildDeepInheritanceRoleList()
    {
        $roles = new Zend_Config(array('foo' => array('bar' => array('foobar' => array('barfoo')))));
        $builder = new Xi_Acl_Builder_Role;
        $acl = $builder->build($roles);
        
        $this->assertTrue($acl->inheritsRole('bar', 'foo'));
        $this->assertTrue($acl->inheritsRole('foobar', 'bar'));
        $this->assertTrue($acl->inheritsRole('barfoo', 'foobar'));
    }
    
    public function testChildCanBeAString()
    {
        $roles = new Zend_Config(array('foo' => 'bar'));
        $builder = new Xi_Acl_Builder_Role;
        $acl = $builder->build($roles);
        $this->assertTrue($acl->inheritsRole('bar', 'foo'));
    }
    
    public function testThrowExceptionOnNonStringListItem()
    {
        $this->setExpectedException('Xi_Acl_Builder_Role_Exception');
        $builder = new Xi_Acl_Builder_Role;
        $builder->build(new Zend_Config(array(array())));
    }
}
