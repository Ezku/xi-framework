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
class Xi_Locator_NamespacedTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->locator = new Xi_Locator_Namespaced;
    }

    public function testNamespaceCanBeAccessedWithDots()
    {
        $this->locator['one.two'] = 'foo';

        $this->assertTrue(isset($this->locator['one.two']));
        $this->assertEquals($this->locator['one.two'], 'foo');

        $this->assertTrue(isset($this->locator['one']['two']));
        $this->assertEquals($this->locator['one']['two'], 'foo');
    }

    public function testNamespacesAreCheckedAsWhole()
    {
        $parent = new Xi_Locator_Namespaced(array('one' => array('two' => 'foo'),
                                       'two' => 'not what we want'));
        $locator = new Xi_Locator_Namespaced(null, $parent);

        $locator['one'] = 'not what we want';
        $locator['two'] = 'not what we want';

        $this->assertTrue(isset($locator['one.two']));
        $this->assertEquals($locator['one.two'], 'foo');
    }
}