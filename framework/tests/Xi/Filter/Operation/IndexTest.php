<?php
/**
 * @category    Xi_Test
 * @package     Xi_Filter
 * @group       Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Filter_Operation_IndexTest extends PHPUnit_Framework_TestCase
{
    public function testAccessesIndexProvidedInConstructor()
    {
        $filter = new Xi_Filter_Operation_Index('foo');
        $this->assertEquals('bar', $filter->filter(array('foo' => 'bar')));
    }

    public function testDefaultsToNullIfIndexIsUnset()
    {
        $filter = new Xi_Filter_Operation_Index('foo');
        $this->assertEquals(null, $filter->filter(array()));
    }

    public function testDefaultValueCanBeSetInConstructor()
    {
        $filter = new Xi_Filter_Operation_Index('foo', 'bar');
        $this->assertEquals('bar', $filter->filter(array()));
    }

    public function testDefaultValueCanBeSet()
    {
        $filter = new Xi_Filter_Operation_Index('foo');
        $filter->setDefaultValue('bar');
        $this->assertEquals('bar', $filter->filter(array()));
    }
}
