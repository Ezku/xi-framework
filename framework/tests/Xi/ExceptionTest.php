<?php
/**
 * @category    Xi_Test
 * @package     Xi_Exception
 * @group       Xi_Exception
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_ExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testCanConvertErrorsToExceptions()
    {
        try {
            Xi_Exception::handleError(100, 'message', 'file', 123);
        } catch (Xi_Exception $e) {
        }
        
        $this->assertEquals(100, $e->getCode());
        $this->assertEquals('message', $e->getMessage());
        $this->assertEquals('file', $e->getFile());
        $this->assertEquals(123, $e->getLine());
    }
}
