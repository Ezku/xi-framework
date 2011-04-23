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
 * @package     Xi_Config
 * @group       Xi_Config
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Config_Filter_InflectorTest extends PHPUnit_Framework_TestCase
{
    public function testCreatesDefaultInflectorIfNotProvided()
    {
        $config = new Xi_Config_Filter_Inflector(array());
        $this->assertTrue($config->getFilter() instanceof Xi_Filter_Inflector_Recursive);
    }
    
    public function testDefaultConfigIsReadOnly()
    {
        $config = new Xi_Config_Filter_Inflector(array());
        $this->assertTrue($config->getConfig()->isReadOnly());
    }
    
    public function testUsesScalarValuesForRules()
    {
        $config = new Xi_Config_Filter_Inflector(array('foo' => 'bar', 'bar' => ':foo'));
        $this->assertEquals('bar', $config->bar);
    }
    
    public function testModifyingValuesImpliesModifyingRules()
    {
        $config = new Xi_Config_Filter_Inflector(new Xi_Config(array('foo' => 'bar', 'bar' => ':foo'), true));
        $config->foo = 'foobar';
        $this->assertEquals('foobar', $config->bar);
    }
    
    public function testRulesArePassedToChildren()
    {
        $config = new Xi_Config_Filter_Inflector(array('foo' => 'bar', 'bar' => array('foobar' => ':foo')));
        $this->assertEquals('bar', $config->bar->foobar);
    }
    
    public function testChangesInRulesArePassedToChildren()
    {
        $config = new Xi_Config_Filter_Inflector(array('foo' => 'bar', 'bar' => array('foobar' => ':foo')));
        $config->addRules(array('foo' => 'barfoo'));
        $this->assertEquals('barfoo', $config->bar->foobar);
    }
    
    public function testChangesInChildrenAreNotPassedToParent()
    {
        $config = new Xi_Config_Filter_Inflector(array('foo' => ':bar', 'bar' => array('foobar' => ':foo')));
        $config->addRules(array('bar' => 'foobar'));
        $config->bar->addRules(array('bar' => 'barfoo'));
        $this->assertEquals('foobar', $config->foo);
    }
    
    public function testValuesCanBeAccessedWithMethod()
    {
        $config = new Xi_Config_Filter_Inflector(array('foo' => 'bar'));
        $this->assertEquals('bar', $config->foo());
    }
    
    public function testRulesCanBeProvidedWhenAccessingWithMethod()
    {
        $config = new Xi_Config_Filter_Inflector(array('foo' => ':bar'));
        $this->assertEquals('foobar', $config->foo(array('bar' => 'foobar')));
    }
    
    public function testAccessingChildWithRulesProducesVolatileInstance()
    {
        $config = new Xi_Config_Filter_Inflector(array('one' => 'two', 'three' => array('four' => ':one')));
        $this->assertEquals('five', $config->three(array('one' => 'five'))->four);
        $this->assertEquals('two', $config->three->four);
    }
}
