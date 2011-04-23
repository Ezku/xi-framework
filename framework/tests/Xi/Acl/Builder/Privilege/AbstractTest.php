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
class Xi_Acl_Builder_Privilege_AbstractTest extends PHPUnit_Framework_TestCase 
{
    public function getBuilderMock(array $constructionArguments = array(), array $methods = array())
    {
        return $this->getMock('Xi_Acl_Builder_Privilege_Abstract', array_merge(array('getOperation', 'addPrivilege'), $methods), $constructionArguments);
    }
    
    public function testThrowsExceptionOnMissingRole()
    {
        $this->setExpectedException('Xi_Acl_Builder_Privilege_Exception');
        $builder = $this->getBuilderMock();
        $builder->build(new Zend_Config(array()));
    }
    
    public function testPrivilegeCanBeSet()
    {
        $builder = $this->getBuilderMock();
        $builder->setPrivilege('foo');
        $this->assertEquals('foo', $builder->getPrivilege());
    }
    
    public function testCanFormatPrivilege()
    {
        $builder = $this->getBuilderMock();
        $this->assertEquals('foo', $builder->formatPrivilege('foo'));
        $this->assertEquals(null, $builder->formatPrivilege('*'));
        
        $builder->setPrivilege('bar');
        $this->assertEquals(false, $builder->formatPrivilege('foo'));
        $this->assertEquals(false, $builder->formatPrivilege('*'));
    }
    
    public function testThrowsExceptionOnNonStringValueInAList()
    {
        $this->setExpectedException('Xi_Acl_Builder_Privilege_Exception');
        $builder = $this->getBuilderMock();
        $builder->setRole('role');
        $builder->build(new Zend_Config(array(array())));
    }
    
    public function testThrowsExceptionOnNonStarInAList()
    {
        $this->setExpectedException('Xi_Acl_Builder_Privilege_Exception');
        $builder = $this->getBuilderMock();
        $builder->setRole('role');
        $builder->build(new Zend_Config(array('*')));
    }
    
    public function testCanBuildSimplePrivilegeList()
    {
        $foo = new Zend_Acl_Resource('foo');
        $acl = new Zend_Acl;
        $acl->add($foo);
        $acl->addRole(new Zend_Acl_Role('role'));
        
        $builder = $this->getBuilderMock(array($acl));
        $builder->setRole('role');
        
        $builder->expects($this->once())->method('addPrivilege')->with($this->equalTo('foo'), $this->equalTo(null));
        
        $builder->build(new Zend_Config(array('foo')));
    }
    
    public function testCanBuildSimplePrivilegeListWithStars()
    {
        $foo = new Zend_Acl_Resource('foo');
        $acl = new Zend_Acl;
        $acl->add($foo);
        $acl->addRole(new Zend_Acl_Role('role'));
        
        $builder = $this->getBuilderMock(array($acl));
        $builder->setRole('role');
        
        $builder->expects($this->once())->method('addPrivilege')->with($this->equalTo('foo'), $this->equalTo(null));
        
        $builder->build(new Zend_Config(array('foo' => '*')));
    }
    
    public function testCanBuildNestedPrivilegeListWithStringValues()
    {
        $acl = new Zend_Acl;
        $acl->add(new Zend_Acl_Resource('foo'));
        $acl->add(new Zend_Acl_Resource('foo.bar'));
        $acl->addRole(new Zend_Acl_Role('role'));
        
        $builder = $this->getBuilderMock(array($acl), array('_getChild'));
        $builder->setRole('role');
        
        $child = $this->getBuilderMock(array($acl));
        $builder->expects($this->once())->method('_getChild')->will($this->returnValue($child));
        $child->expects($this->once())->method('addPrivilege')->with($this->equalTo('foo.bar'), $this->equalTo(null));
        
        $builder->build(new Zend_Config(array('foo' => 'bar')));
    }
    
    public function testCanBuildNestedPrivilegeListWithArrayValues()
    {
        $acl = new Zend_Acl;
        $acl->add(new Zend_Acl_Resource('foo'));
        $acl->add(new Zend_Acl_Resource('foo.bar'));
        $acl->addRole(new Zend_Acl_Role('role'));
        
        $builder = $this->getBuilderMock(array($acl), array('_getChild'));
        $builder->setRole('role');
        
        $child = $this->getBuilderMock(array($acl));
        $child->setRole('role');
        
        $builder->expects($this->once())->method('_getChild')->will($this->returnValue($child));
        $child->expects($this->once())->method('addPrivilege')->with($this->equalTo('foo.bar'), $this->equalTo(null));
        
        $builder->build(new Zend_Config(array('foo' => array('bar'))));
    }
    
    public function testSetsPrivilegeIfMissingResource()
    {
        $builder = $this->getBuilderMock();
        $builder->setRole('role');
        $builder->expects($this->once())->method('addPrivilege')->with($this->equalTo(null), $this->equalTo('foo'));
        $builder->build(new Zend_Config(array('foo')));
    }
    
    public function testSetsPrivilegeIfMissingResourceWithKeyAndValue()
    {
        $acl = new Zend_Acl;
        $acl->add(new Zend_Acl_Resource('foo'));
        
        $builder = $this->getBuilderMock(array($acl), array('_getChild'));
        $builder->setRole('role');
        
        $child = $this->getBuilderMock();
        $child->setRole('role');
        
        $builder->expects($this->once())->method('_getChild')->will($this->returnValue($child));
        $child->expects($this->once())->method('addPrivilege')->with($this->equalTo('foo'), $this->equalTo('bar'));
        $builder->build(new Zend_Config(array('foo' => 'bar')));
    }
    
    public function testThrowsExceptionIfMissingResourceAndPrivilegeIsSet()
    {
        $this->setExpectedException('Xi_Acl_Builder_Privilege_Exception');
        $builder = $this->getBuilderMock();
        $builder->setRole('role');
        $builder->setPrivilege('privilege');
        $builder->expects($this->never())->method('addPrivilege');
        $builder->build(new Zend_Config(array('foo')));
    }
    
    public function testThrowsExceptionIfMissingResourceAndPrivilegeIsSetWithKeyAndValue()
    {
        $this->setExpectedException('Xi_Acl_Builder_Privilege_Exception');
        
        $acl = new Zend_Acl;
        $acl->add(new Zend_Acl_Resource('foo'));
        
        $builder = $this->getBuilderMock(array($acl), array('_getChild'));
        $builder->setRole('role');
        $builder->setPrivilege('privilege');
        
        $child = $this->getBuilderMock();
        $child->setRole('role');
        $child->setPrivilege('privilege');
        
        $builder->expects($this->once())->method('_getChild')->will($this->returnValue($child));
        $child->expects($this->never())->method('addPrivilege');
        $builder->build(new Zend_Config(array('foo' => 'bar')));
    }
    
    public function testThrowsExceptionIfKeyRefersToMissingResourceWhenValueIsAnArray()
    {
        $this->setExpectedException('Xi_Acl_Builder_Privilege_Exception');
        $acl = new Zend_Acl;
        $acl->add(new Zend_Acl_Resource('resource'));
        $builder = $this->getBuilderMock(array($acl));
        $builder->setRole('role');
        $builder->expects($this->never())->method('addPrivilege');
        $builder->build(new Zend_Config(array('not a resource' => array('resource'))));
    }
    
    public function testThrowsExceptionIfKeyRefersToMissingResourceWhenValueIsAString()
    {
        $this->setExpectedException('Xi_Acl_Builder_Privilege_Exception');
        $acl = new Zend_Acl;
        $acl->add(new Zend_Acl_Resource('resource'));
        $builder = $this->getBuilderMock(array($acl));
        $builder->setRole('role');
        $builder->expects($this->never())->method('addPrivilege');
        $builder->build(new Zend_Config(array('not a resource' => 'resource')));
    }
    
    public function testCanFormatResourceWithoutParent()
    {
        $acl = new Zend_Acl;
        $acl->add(new Zend_Acl_Resource('foo'));
        
        $builder = $this->getBuilderMock(array($acl));
        $this->assertEquals('foo', $builder->formatResource('foo'));
    }
    
    public function testCanFormatResourceWithParent()
    {
        $acl = new Zend_Acl;
        $acl->add(new Zend_Acl_Resource('foo.bar'));
        
        $builder = $this->getBuilderMock(array($acl));
        $builder->setParentResource('foo');
        $this->assertEquals('foo.bar', $builder->formatResource('bar'));
    }
    
    public function testCanFormatResourceWithStar()
    {
        $builder = $this->getBuilderMock();
        $this->assertEquals(null, $builder->formatResource('*'));
    }
    
    public function testCanFormatResourceWithStarAndParent()
    {
        $acl = new Zend_Acl;
        $acl->add(new Zend_Acl_Resource('foo'));
        
        $builder = $this->getBuilderMock(array($acl));
        $builder->setParentResource('foo');
        $this->assertEquals('foo', $builder->formatResource('*'));
    }
    
    public function testReturnsFalseIfFormattedResourceCannotBeFound()
    {
        $builder = $this->getBuilderMock();
        $this->assertEquals(false, $builder->formatResource('foo'));
        
        $builder = $this->getBuilderMock();
        $builder->setParentResource('foo');
        $this->assertEquals(false, $builder->formatResource('*'));
    }
    
    public function testCanBuildPrivilegeListWithAssertion()
    {
        $acl = new Zend_Acl;
        $acl->addRole(new Zend_Acl_Role('role'));
        $builder = $this->getBuilderMock(array($acl));
        $builder->setRole('role');
        $builder->expects($this->once())->method('addPrivilege')->with($this->equalTo(null), $this->equalTo(null), $this->isInstanceOf('Xi_Acl_Assert_Null'));
        $builder->build(new Zend_Config(array(new Xi_Acl_Assert_Null)));
    }
    
    public function testCanBuildPrivilegeListWithResourceAndAssertion()
    {
        $acl = new Zend_Acl;
        $acl->addRole(new Zend_Acl_Role('role'));
        $acl->add(new Zend_Acl_Resource('resource'));
        
        $child = $this->getBuilderMock();
        $child->expects($this->once())->method('addPrivilege')->with($this->equalTo('resource'), $this->equalTo(null), $this->isInstanceOf('Xi_Acl_Assert_Null'));
        
        $builder = $this->getBuilderMock(array($acl), array('_getChild'));
        $builder->setRole('role');
        $builder->expects($this->once())->method('_getChild')->will($this->returnValue($child));
        $builder->build(new Zend_Config(array('resource' => new Xi_Acl_Assert_Null)));
    }
    
    public function testCanBuildPrivilegeListWithResourcePrivilegeAndAssertion()
    {
        $acl = new Zend_Acl;
        $acl->addRole(new Zend_Acl_Role('role'));
        $acl->add(new Zend_Acl_Resource('resource'));
        
        $child = $this->getBuilderMock();
        $child->setRole('role');
        $child->setParentResource('resource');
        $child->expects($this->once())->method('addPrivilege')->with($this->equalTo('resource'), $this->equalTo('privilege'), $this->isInstanceOf('Xi_Acl_Assert_Null'));
        
        $builder = $this->getBuilderMock(array(), array('_getChild'));
        $builder->expects($this->once())->method('_getChild')->will($this->returnValue($child));
        $builder->buildAssociatedValue('privilege', new Xi_Acl_Assert_Null);
    }
}
