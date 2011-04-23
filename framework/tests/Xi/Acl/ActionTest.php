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
 * @package     Xi_Acl
 * @group       Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
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