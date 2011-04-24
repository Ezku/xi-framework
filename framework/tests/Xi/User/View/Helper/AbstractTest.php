<?php
/**
 * @category    Xi_Test
 * @package     Xi_User
 * @group       Xi_User
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_User_View_Helper_AbstractTest extends PHPUnit_Framework_TestCase 
{
    public function testUserCanBeSet()
    {
        $user = $this->getMock('Xi_User');
        $helper = $this->getMock('Xi_User_View_Helper_Abstract', array('getDefaultUser'));
        $helper->setUser($user);
        $this->assertEquals($user, $helper->getUser());
    }
    
    public function testRetrievesDefaultUserIfNoUserSet()
    {
        $user = $this->getMock('Xi_User');
        $helper = $this->getMock('Xi_User_View_Helper_Abstract', array('getDefaultUser'));
        $helper->expects($this->once())->method('getDefaultUser')->will($this->returnValue($user));
        $this->assertEquals($user, $helper->getUser());
    }
}
