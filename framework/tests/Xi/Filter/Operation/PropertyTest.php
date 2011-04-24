<?php
/**
 * @category    Xi_Test
 * @package     Xi_Filter
 * @group       Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_Operation_PropertyTest extends PHPUnit_Framework_TestCase
{
    public function testAccessesPropertyProvidedInConstructor()
    {
        $filter = new Xi_Filter_Operation_Property('foo');
        $data = new stdClass;
        $data->foo = 'bar';
        $this->assertEquals('bar', $filter->filter($data));
    }

    public function testDefaultsToNullIfPropertyIsUnset()
    {
        $filter = new Xi_Filter_Operation_Property('foo');
        $this->assertEquals(null, $filter->filter(new stdClass));
    }

    public function testDefaultValueCanBeSetInConstructor()
    {
        $filter = new Xi_Filter_Operation_Property('foo', 'bar');
        $this->assertEquals('bar', $filter->filter(new stdClass));
    }

    public function testDefaultValueCanBeSet()
    {
        $filter = new Xi_Filter_Operation_Property('foo');
        $filter->setDefaultValue('bar');
        $this->assertEquals('bar', $filter->filter(new stdClass));
    }
}
