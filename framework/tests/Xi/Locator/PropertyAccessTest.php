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
 * @package     Xi_Locator
 * @group       Xi_Locator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Locator_PropertyAccessTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->locator = new Xi_Locator;
    }

    public function testPropertyCanBeSet()
    {
        $this->locator->property = 'foo';
        $this->assertEquals($this->locator->property, 'foo');
    }

    public function testSingletonValuesAreIdentical()
    {
        $this->locator->property = new stdClass;
        $this->assertTrue($this->locator->property === $this->locator->property);
    }

    public function testPropertyExistenceCanBeChecked()
    {
        $this->assertFalse(isset($this->locator->property));
        $this->locator->property = 'foo';
        $this->assertTrue(isset($this->locator->property));
    }

    public function testPropertiesCanBeProvidedInConstructor()
    {
        $locator = new Xi_Locator(array('property' => 'foo'));
        $this->assertEquals($locator->property, 'foo');
    }

    public function testNullPropertyIsNotSet()
    {
        $this->locator->property = null;
        $this->assertFalse(isset($this->locator->property));
    }

    public function testUnfoundPropertiesAreCheckedInParent()
    {
        $parent = new Xi_Locator(array('foo' => 'bar'));
        $locator = new Xi_Locator(null, $parent);

        $this->assertTrue(isset($locator->foo));
        $this->assertEquals($locator->foo, 'bar');
    }
}

