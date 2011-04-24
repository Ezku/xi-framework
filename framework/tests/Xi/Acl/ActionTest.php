<?php
/**
 * @category    Xi_Test
 * @package     Xi_Acl
 * @group       Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Acl_ActionTest extends PHPUnit_Framework_TestCase
{
    public function testPrivilegeResourceAndRoleDefaultToNull()
    {
        $action = new Xi_Acl_Action;
        $this->assertEquals(null, $action->getPrivilege());
        $this->assertEquals(null, $action->getResource());
        $this->assertEquals(null, $action->getRole());
    }
    
    public function testPrivilegeResourceAndRoleCanBeProvidedInConstructor()
    {
        $action = new Xi_Acl_Action('privilege', 'resource', 'role');
        $this->assertEquals('privilege', $action->getPrivilege());
        $this->assertEquals('resource', $action->getResource());
        $this->assertEquals('role', $action->getRole());
    }
    
    public function testPrivilegeCanBeSet()
    {
        $action = new Xi_Acl_Action;
        $action->setPrivilege('privilege');
        $this->assertEquals('privilege', $action->getPrivilege());
    }
    
    public function testResourceCanBeSet()
    {
        $action = new Xi_Acl_Action;
        $action->setResource('resource');
        $this->assertEquals('resource', $action->getResource());
    }
    
    public function testRoleCanBeSet()
    {
        $action = new Xi_Acl_Action;
        $action->setRole('role');
        $this->assertEquals('role', $action->getRole());
    }
    
    public function testCanBeValidatedAgainstAcl()
    {
        $action = new Xi_Acl_Action;
        $this->assertFalse($action->isAllowed(new Zend_Acl));
    }
    
    public function testPassesPrivilegeResourceAndRoleToAcl()
    {
        $action = new Xi_Acl_Action('privilege', 'resource', 'role');
        $acl = $this->getMock('Zend_Acl');
        $acl->expects($this->once())->method('isAllowed')->with($this->equalTo('role'), $this->equalTo('resource'), $this->equalTo('privilege'));
        $action->isAllowed($acl);
    }
    
    public function testReturnsAclReturnValue()
    {
        $action = new Xi_Acl_Action;
        $acl = $this->getMock('Zend_Acl');
        $acl->expects($this->once())->method('isAllowed')->will($this->returnValue(true));
        $this->assertTrue($action->isAllowed($acl));
    }
}
