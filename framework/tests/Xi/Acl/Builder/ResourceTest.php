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
