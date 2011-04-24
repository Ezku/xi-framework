<?php
/**
 * @category    Xi_Test
 * @package     Xi_Acl
 * @group       Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Acl_BuilderTest extends PHPUnit_Framework_TestCase
{
    public function testHasRoleResourceAndPrivilegeBuildersByDefault()
    {
        $builder = new Xi_Acl_Builder;
        $this->assertEquals(array(), array_diff(array('roles', 'resources', 'privileges'), array_keys($builder->getBuilders())));
    }
    
    public function testBuildersCanBeSet()
    {
        $builder = new Xi_Acl_Builder;
        $mock = $this->getMock('Xi_Acl_Builder_Interface');
        $builder->setBuilders(array('mock' => $mock));
        $this->assertEquals(array('mock' => $mock), $builder->getBuilders());
    }
    
    public function testBuilderCanBeSet()
    {
        $builder = new Xi_Acl_Builder;
        $mock = $this->getMock('Xi_Acl_Builder_Interface');
        $builder->setBuilders(array());
        $builder->setBuilder('mock', $mock);
        $this->assertEquals(array('mock' => $mock), $builder->getBuilders());
    }
    
    public function testCallsBuildersIfNamespaceIsAvailable()
    {
        $acl = new Zend_Acl;
        $config = new Zend_Config(array('available' => array()));
        
        $available = $this->getMock('Xi_Acl_Builder_Interface');
        $available->expects($this->once())->method('setAcl')->with($this->equalTo($acl));
        $available->expects($this->once())->method('build')->with($this->equalTo($config->available))->will($this->returnValue($acl));
        
        $unavailable = $this->getMock('Xi_Acl_Builder_Interface');
        $unavailable->expects($this->never())->method('build');
        $builder = new Xi_Acl_Builder($acl);
        $builder->setBuilders(array('available' => $available, 'unavailable' => $unavailable));
        $builder->build($config);
    }
}
