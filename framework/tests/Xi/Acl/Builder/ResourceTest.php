<?php
/**
 * @category    Xi_Test
 * @package     Xi_Acl
 * @group       Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Acl_Builder_ResourceTest extends PHPUnit_Framework_TestCase
{
    public function testCanBuildFlatResourceList()
    {
        $resources = new Zend_Config(array('foo', 'bar'));
        $builder = new Xi_Acl_Builder_Resource;
        $acl = $builder->build($resources);
        $this->assertTrue($acl->has('foo'));
        $this->assertTrue($acl->has('bar'));
    }
    
    public function testCanBuildInheritanceResourceList()
    {
        $resources = new Zend_Config(array('foo' => array('bar')));
        $builder = new Xi_Acl_Builder_Resource;
        $acl = $builder->build($resources);
        $this->assertTrue($acl->inherits('foo.bar', 'foo'));
    }
    
    public function testCanBuildDeepInheritanceResourceList()
    {
        $resources = new Zend_Config(array('foo' => array('bar' => array('foobar' => array('barfoo')))));
        $builder = new Xi_Acl_Builder_Resource;
        $acl = $builder->build($resources);
        
        $this->assertTrue($acl->inherits('foo.bar', 'foo'));
        $this->assertTrue($acl->inherits('foo.bar.foobar', 'foo.bar'));
        $this->assertTrue($acl->inherits('foo.bar.foobar.barfoo', 'foo.bar.foobar'));
    }
    
    public function testChildCanBeAString()
    {
        $resources = new Zend_Config(array('foo' => 'bar'));
        $builder = new Xi_Acl_Builder_Resource;
        $acl = $builder->build($resources);
        $this->assertTrue($acl->inherits('foo.bar', 'foo'));
    }
    
    public function testThrowExceptionOnNonStringListItem()
    {
        $this->setExpectedException('Xi_Acl_Builder_Resource_Exception');
        $builder = new Xi_Acl_Builder_Resource;
        $builder->build(new Zend_Config(array(array())));
    }
}
