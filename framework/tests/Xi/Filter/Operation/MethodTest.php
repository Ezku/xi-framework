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
 * @package     Xi_Filter
 * @group       Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_Operation_MethodTest extends PHPUnit_Framework_TestCase
{
    public function testAccessesMethodProvidedInConstructor()
    {
        $filter = new Xi_Filter_Operation_Method('foo');
        $this->assertEquals('bar', $filter->filter($this));
    }

    public function foo()
    {
        return 'bar';
    }

    public function testDefaultsToNullIfMethodDoesNotExist()
    {
        $filter = new Xi_Filter_Operation_Method('foo');
        $this->assertEquals(null, $filter->filter(new stdClass));
    }

    public function testDefaultValueCanBeSetInConstructor()
    {
        $filter = new Xi_Filter_Operation_Method('foo', 'bar');
        $this->assertEquals('bar', $filter->filter(new stdClass));
    }

    public function testDefaultValueCanBeSet()
    {
        $filter = new Xi_Filter_Operation_Method('foo');
        $filter->setDefaultValue('bar');
        $this->assertEquals('bar', $filter->filter(new stdClass));
    }
}
