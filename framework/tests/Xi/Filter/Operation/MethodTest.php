<?php
/**
 * @category    Xi_Test
 * @package     Xi_Filter
 * @group       Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
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
