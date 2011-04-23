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
 * @package     Xi_Class
 * @group       Xi_Class
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_ClassTest extends PHPUnit_Framework_TestCase
{
    function testClassCanBeCreated()
    {
        $this->assertTrue(Xi_Class::create('stdClass') instanceof stdClass);
    }

    public static function constructionArguments()
    {
        return array(
            array('foo'),
            array('foo', 'bar'),
            array('foo', 'bar', 'qux'),
            array('foo', 'bar', 'qux', 'quz')
        );
    }

    /**
     * @dataProvider constructionArguments
     */
    function testClassCanBeGivenConstructionArguments()
    {
        $args = func_get_args();
        $instance = Xi_Class::create('Xi_ClassTest_ConstructionArgumentStub', $args);
        $this->assertEquals($instance->args, $args);
    }

    public function testNonExistingClassResultsInException()
    {
        $this->setExpectedException('Xi_Exception');
        Xi_Class::create('does not exist');
    }

    public static function invalidClassNames()
    {
        return array(
            array(true),
            array(array()),
            array(new stdClass),
            array(1234)
        );
    }

    /**
     * @dataProvider invalidClassNames
     */
    public function testNonStringClassNameResultsInException($class)
    {
        $this->setExpectedException('Xi_Exception');
        Xi_Class::create($class);
    }
}

class Xi_ClassTest_ConstructionArgumentStub
{
    public $args;
    public function __construct()
    {
        $this->args = func_get_args();
    }
}