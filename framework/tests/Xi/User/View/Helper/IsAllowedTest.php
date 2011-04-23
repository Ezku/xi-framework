<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.xi-framework.com>.
 */

/**
 * @category    Xi_Test
 * @package     Xi_User
 * @group       Xi_User
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
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
