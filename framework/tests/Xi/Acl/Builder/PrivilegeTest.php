<?php
/**
 * @category    Xi_Test
 * @package     Xi_Acl
 * @group       Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Acl_Builder_PrivilegeTest extends PHPUnit_Framework_TestCase 
{
    public function getPrivilegeMock(array $methods = array())
    {
        return $this->getMock('Xi_Acl_Builder_Privilege_Abstract', array_merge($methods, array('setRole', 'addPrivilege', 'getOperation')));
    }
    
    public function testThrowsExceptionOnInvalidRole()
    {
        $this->setExpectedException('Xi_Acl_Builder_Privilege_Exception');
        $builder = new Xi_Acl_Builder_Privilege;
        $builder->build(new Zend_Config(array('allow' => 'invalid')));
    }
    
    public function testHasAllowAndDenyBuildersByDefault()
    {
        $builder = new Xi_Acl_Builder_Privilege;
        $this->assertEquals(array(), array_diff_key(array('allow', 'deny'), array_keys($builder->getBuilders())));
    }
    
    public function testCanParseFlatAccessList()
    {
        $acl = new Zend_Acl;
        $acl->addRole(new Zend_Acl_Role('role'));
        
        $privilege = $this->getPrivilegeMock();
        $privilege->expects($this->once())->method('setRole')->with($this->equalTo('role'));
        $privilege->expects($this->once())->method('addPrivilege')->with($this->equalTo(null), $this->equalTo(null));
        
        $builder = new Xi_Acl_Builder_Privilege($acl);
        $builder->setBuilders(array('mock' => $privilege));
        
        $builder->build(new Zend_Config(array('mock' => 'role')));
    }
    
    public function testCanParseFlatAccessListWithArray()
    {
        $acl = new Zend_Acl;
        $acl->addRole(new Zend_Acl_Role('role'));
        
        $privilege = $this->getPrivilegeMock();
        $privilege->expects($this->once())->method('setRole')->with($this->equalTo('role'));
        $privilege->expects($this->once())->method('addPrivilege')->with($this->equalTo(null), $this->equalTo(null));
        
        $builder = new Xi_Acl_Builder_Privilege($acl);
        $builder->setBuilders(array('mock' => $privilege));
        
        $builder->build(new Zend_Config(array('mock' => array('role'))));
    }
    
    public function testCanParseNestedAccessList()
    {
        $acl = new Zend_Acl;
        $acl->addRole(new Zend_Acl_Role('role'));
        $acl->add(new Zend_Acl_Resource('resource'));
        
        $privilege = $this->getPrivilegeMock();
        $privilege->expects($this->once())->method('setRole')->with($this->equalTo('role'));
        $privilege->expects($this->once())->method('addPrivilege')->with($this->equalTo('resource'), $this->equalTo(null));
        
        $builder = new Xi_Acl_Builder_Privilege($acl);
        $builder->setBuilders(array('mock' => $privilege));
        
        $builder->build(new Zend_Config(array('mock' => array('role' => 'resource'))));
    }
    
    public function testCanParseListOfResources()
    {
        $config = new Zend_Config(array('mock' => array('role' => array('resource'))));
        
        $acl = new Zend_Acl;
        $acl->addRole(new Zend_Acl_Role('role'));
        $acl->add(new Zend_Acl_Resource('resource'));
        
        $privilege = $this->getPrivilegeMock(array('build'));
        $privilege->expects($this->once())->method('setRole')->with($this->equalTo('role'));
        $privilege->expects($this->once())->method('build')->with($this->equalTo($config->mock->role));
        
        $builder = new Xi_Acl_Builder_Privilege($acl);
        $builder->setBuilders(array('mock' => $privilege));
        $builder->build($config);
    }
}
