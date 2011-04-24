<?php
/**
 * @category    Xi_Test
 * @package     Xi_Validate
 * @group       Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Validate_OrTest extends Xi_Validate_TestCase
{
    public function testReturnsFalseWhenEmpty()
    {
        $validator = new Xi_Validate_Or;
        $this->assertFalse($validator->isValid('foo'));
    }

    public function testReturnsTrueIfAnyValidatorPasses()
    {
        $one = $this->getValidatorMock(true);
        $validator = new Xi_Validate_Or(array($one));
        $this->assertTrue($validator->isValid('foo'));

        $two = $this->getValidatorMock(false);
        $validator = new Xi_Validate_Or(array($two, $one));
        $this->assertTrue($validator->isValid('foo'));
    }

    public function testReturnsFalseIfAllValidatorsFail()
    {
        $one = $this->getValidatorMock(false);
        $two = $this->getValidatorMock(false);
        $validator = new Xi_Validate_And(array($one, $two));
        $this->assertFalse($validator->isValid('foo'));
    }
}
