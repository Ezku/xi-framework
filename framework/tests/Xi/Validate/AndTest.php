<?php
/**
 * @category    Xi_Test
 * @package     Xi_Validate
 * @group       Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Validate_AndTest extends Xi_Validate_TestCase
{
    public function testReturnsTrueWhenEmpty()
    {
        $validator = new Xi_Validate_And;
        $this->assertTrue($validator->isValid('foo'));
    }

    public function testReturnsTrueIfAllValidatorsPass()
    {
        $one = $this->getValidatorMock(true);
        $two = $this->getValidatorMock(true);
        $validator = new Xi_Validate_And(array($one, $two));
        $this->assertTrue($validator->isValid('foo'));
    }

    public function testReturnsFalseIfAnyValidatorFails()
    {
        $one = $this->getValidatorMock(true);
        $two = $this->getValidatorMock(false);
        $validator = new Xi_Validate_And(array($one, $two));
        $this->assertFalse($validator->isValid('foo'));

        $one = $this->getValidatorMock(false);
        $two = $this->getValidatorMock(true);
        $validator = new Xi_Validate_And(array($one, $two));
        $this->assertFalse($validator->isValid('foo'));
    }
}
