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
 * @package     Xi_Array
 * @group       Xi_Array
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Array_ValidateTest extends PHPUnit_Framework_TestCase
{
    public function getValidatorMock($return = null, $expect = 'foo')
    {
        $validator = $this->getMock('Xi_Validate_Abstract');
        if (null !== $return) {
            $validator->expects($this->atLeastOnce())->method('isValid')->with($this->equalTo($expect))->will($this->returnValue($return));
        }
        return $validator;
    }

    public function testCanBeConstructedWithValidator()
    {
        $validator = $this->getValidatorMock();
        $array = new Xi_Array_Validate(array(), $validator);
        $this->assertTrue($validator === $array->getValidator());
    }

    public function testAllowsValidValues()
    {
        $validator = $this->getValidatorMock(true);
        $array = new Xi_Array_Validate(array('bar' => 'foo'), $validator);
        $array->bar = 'foo';
    }

    public function testThrowsExceptionOnInvalidValueInConstructor()
    {
        $this->setExpectedException('Xi_Array_Exception');

        $validator = $this->getValidatorMock(false);
        new Xi_Array_Validate(array('bar' => 'foo'), $validator);
    }

    public function testThrowsExceptionOnInvalidValue()
    {
        $this->setExpectedException('Xi_Array_Exception');

        $validator = $this->getValidatorMock(false);
        $array = new Xi_Array_Validate(array(), $validator);
        $array->bar = 'foo';
    }

    public function testPassesValidatorToBranch()
    {
        $validator = $this->getValidatorMock(true, array());
        $array = new Xi_Array_Validate(array('foo' => array()), $validator);
        $this->assertEquals($validator, $array->foo->getValidator());
    }

    public function testArrayIsInvalidUnlessExplicitlyAllowed()
    {
        $validator = $this->getValidatorMock(true, array());
        $array = new Xi_Array_Validate(array('foo' => array()), $validator);

        $this->setExpectedException('Xi_Array_Exception');

        $validator = $this->getValidatorMock(false, array());
        $array = new Xi_Array_Validate(array('foo' => array()), $validator);
    }
}
