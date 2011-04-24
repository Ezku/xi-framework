<?php
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