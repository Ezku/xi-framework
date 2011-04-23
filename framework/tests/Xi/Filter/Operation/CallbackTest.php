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
class Xi_Filter_Operation_CallbackTest extends PHPUnit_Framework_TestCase
{
    public $accessed = 0;
    public $args;

    public function tearDown()
    {
        $this->accessed = 0;
        $this->args = 0;
    }

    public function callback()
    {
        $this->accessed++;
        $this->args = func_get_args();
        return 'foo';
    }

    public function testRetrievesValueFromCallback()
    {
        $filter = new Xi_Filter_Operation_Callback;

        $this->assertEquals('foo', $filter->filter(array($this, 'callback')));
        $this->assertEquals(1, $this->accessed);
        $this->assertEquals(array(), $this->args);

        $this->assertEquals('foo', $filter->filter(array($this, 'callback')));
        $this->assertEquals(2, $this->accessed);
        $this->assertEquals(array(), $this->args);
    }

    public function testProvidesSingleArgumentToCallback()
    {
        $filter = new Xi_Filter_Operation_Callback('arg');

        $filter->filter(array($this, 'callback'));
        $this->assertEquals(array('arg'), $this->args);
    }

    public function testProvidesMultipleArgumentsToCallback()
    {
        $filter = new Xi_Filter_Operation_Callback(array('foo', 'bar'));

        $filter->filter(array($this, 'callback'));
        $this->assertEquals(array('foo', 'bar'), $this->args);
    }

    public function testDefaultsToNullIfCallbackIsNotValid()
    {
        $filter = new Xi_Filter_Operation_Callback;
        $this->assertEquals(null, $filter->filter('not a valid callback'));
    }

    public function testDefaultValueCanBeProvidedInConstructor()
    {
        $filter = new Xi_Filter_Operation_Callback(array(), 'foo');
        $this->assertEquals('foo', $filter->filter('not a valid callback'));
    }

    public function testDefaultValueCanBeSet()
    {
        $filter = new Xi_Filter_Operation_Callback;
        $filter->setDefaultValue('foo');
        $this->assertEquals('foo', $filter->filter('not a valid callback'));
    }
}
