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
class Xi_Config_FilterTest extends PHPUnit_Framework_TestCase
{
    public function testFilterIsNullIfNotProvided()
    {
        $config = new Xi_Config_Filter(array());
        $this->assertEquals(null, $config->getFilter());
    }
    
    public function testFilterCanBeRetrieved()
    {
        $filter = new Zend_Filter_StringToUpper();
        $config = new Xi_Config_Filter(array(), $filter);
        $this->assertTrue($filter === $config->getFilter());
    }
    
    public function testCanBeCreatedWithArray()
    {
        $config = new Xi_Config_Filter(array('foo' => 'bar'));
        $this->assertEquals(array('foo' => 'bar'), $config->toArray());
    }

    public function testCanBeCreatedWithConfig()
    {
        $config = new Xi_Config_Filter(new Zend_Config(array('foo' => 'bar')));
        $this->assertEquals(array('foo' => 'bar'), $config->toArray());
    }
    
    public function testCanFilterValues()
    {
        $config = new Xi_Config_Filter(array('foo' => 'bar'), new Zend_Filter_StringToUpper());
        $this->assertEquals('BAR', $config->foo);
    }
    
    public function testCanFilterKeys()
    {
        $config = new Xi_Config_Filter(array('foo' => 'bar'), new Zend_Filter_StringToUpper(), Xi_Config_Filter::FILTER_KEYS);
        $this->assertEquals(array('FOO' => 'bar'), $config->toArray());
    }
    
    public function testCanFilterKeysAndValues()
    {
        $config = new Xi_Config_Filter(array('foo' => 'bar'), new Zend_Filter_StringToUpper(), Xi_Config_Filter::FILTER_VALUES_AND_KEYS);
        $this->assertEquals(array('FOO' => 'BAR'), $config->toArray());
    }
    
    public function testCanFilterMultidimensionalData()
    {
        $config = new Xi_Config_Filter(array('foo' => array('bar' => 'foobar')), new Zend_Filter_StringToUpper(), Xi_Config_Filter::FILTER_VALUES_AND_KEYS);
        $this->assertEquals(array('FOO' => array('BAR' => 'FOOBAR')), $config->toArray());
    }
}
?>