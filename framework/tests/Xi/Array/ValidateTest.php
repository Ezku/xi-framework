<?php
/**
 * @category    Xi_Test
 * @package     Xi_Array
 * @group       Xi_Array
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
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
