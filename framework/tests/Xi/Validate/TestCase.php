<?php
/**
 * @category    Xi_Test
 * @package     Xi_Validate
 * @group       Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Validate_TestCase extends PHPUnit_Framework_TestCase
{
    public function getValidatorMock($return, $times = null)
    {
        if (null === $times) {
            $times = $this->atLeastOnce();
        }
        $mock = $this->getMock('Zend_Validate_Interface');
        $mock->expects($times)
             ->method('isValid')
             ->with($this->equalTo('foo'))
             ->will($this->returnValue($return));
        return $mock;
    }

    public function testMockValidatorReturnsProvidedValue()
    {
        $this->assertTrue($this->getValidatorMock(true)->isValid('foo'));
        $this->assertFalse($this->getValidatorMock(false)->isValid('foo'));
    }
}
