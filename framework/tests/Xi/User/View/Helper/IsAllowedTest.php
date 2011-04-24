<?php
/**
 * @category    Xi_Test
 * @package     Xi_User
 * @group       Xi_User
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_User_View_Helper_IsAllowedTest extends PHPUnit_Framework_TestCase 
{
    public function testQueriesUserWithProvidedParameters()
    {
        $aclAction = $this->getMock('Xi_Acl_Action_Interface');
        
        $user = $this->getMock('Xi_User');
        $user->expects($this->once())->method('isAllowed')->with($this->equalTo($aclAction));
        
        $helper = $this->getMock('Xi_User_View_Helper_IsAllowed', array('getUser', 'getAclAction', 'getRequest'));
        $helper->expects($this->once())->method('getUser')->will($this->returnValue($user));
        $helper->expects($this->once())
               ->method('getAclAction')
               ->with($this->equalTo('action'), $this->equalTo('controller'), $this->equalTo('module'))
               ->will($this->returnValue($aclAction));
        
        $helper->isAllowed('action', 'controller', 'module');
    }
    
    public function testRetrievesParametersFromRequestIfNotProvided()
    {
        $aclAction = $this->getMock('Xi_Acl_Action_Interface');
        
        $user = $this->getMock('Xi_User');
        $user->expects($this->once())->method('isAllowed')->with($this->equalTo($aclAction));
        
        $request = $this->getMock('Zend_Controller_Request_Abstract');
        $request->expects($this->once())->method('getActionName')->will($this->returnValue('action'));
        $request->expects($this->once())->method('getControllerName')->will($this->returnValue('controller'));
        $request->expects($this->once())->method('getModuleName')->will($this->returnValue('module'));
        
        $helper = $this->getMock('Xi_User_View_Helper_IsAllowed', array('getUser', 'getAclAction', 'getRequest'));
        $helper->expects($this->once())->method('getUser')->will($this->returnValue($user));
        $helper->expects($this->once())->method('getRequest')->will($this->returnValue($request));
        $helper->expects($this->once())
               ->method('getAclAction')
               ->with($this->equalTo('action'), $this->equalTo('controller'), $this->equalTo('module'))
               ->will($this->returnValue($aclAction));
        
        $helper->isAllowed();
    }
    
    public function testProvidesReturnValueFromUser()
    {
        $user = $this->getMock('Xi_User');
        $user->expects($this->once())->method('isAllowed')->will($this->returnValue(true));
        
        $helper = $this->getMock('Xi_User_View_Helper_IsAllowed', array('getUser', 'getRequest'));
        $helper->expects($this->once())->method('getUser')->will($this->returnValue($user));
        
        $this->assertTrue($helper->isAllowed('action', 'controller', 'module'));
    }
}
