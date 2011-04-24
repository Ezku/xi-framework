<?php
/**
 * @category    Xi_Test
 * @package     Xi_Config
 * @group       Xi_Config
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Config_FilterTest extends PHPUnit_Framework_TestCase
{
    public function testFilterIsNullIfNotProvided()
    {
        $config = new Xi_Config_Filter(array());
        $this->assertEquals(null, $config->getFilter());
    }
    
    public function testFilterCanBeRetrieved()
    {
        $filter = new Zend_Filter_StringToUpper();
        $config = new Xi_Config_Filter(array(), $filter);
        $this->assertTrue($filter === $config->getFilter());
    }
    
    public function testCanBeCreatedWithArray()
    {
        $config = new Xi_Config_Filter(array('foo' => 'bar'));
        $this->assertEquals(array('foo' => 'bar'), $config->toArray());
    }

    public function testCanBeCreatedWithConfig()
    {
        $config = new Xi_Config_Filter(new Zend_Config(array('foo' => 'bar')));
        $this->assertEquals(array('foo' => 'bar'), $config->toArray());
    }
    
    public function testCanFilterValues()
    {
        $config = new Xi_Config_Filter(array('foo' => 'bar'), new Zend_Filter_StringToUpper());
        $this->assertEquals('BAR', $config->foo);
    }
    
    public function testCanFilterKeys()
    {
        $config = new Xi_Config_Filter(array('foo' => 'bar'), new Zend_Filter_StringToUpper(), Xi_Config_Filter::FILTER_KEYS);
        $this->assertEquals(array('FOO' => 'bar'), $config->toArray());
    }
    
    public function testCanFilterKeysAndValues()
    {
        $config = new Xi_Config_Filter(array('foo' => 'bar'), new Zend_Filter_StringToUpper(), Xi_Config_Filter::FILTER_VALUES_AND_KEYS);
        $this->assertEquals(array('FOO' => 'BAR'), $config->toArray());
    }
    
    public function testCanFilterMultidimensionalData()
    {
        $config = new Xi_Config_Filter(array('foo' => array('bar' => 'foobar')), new Zend_Filter_StringToUpper(), Xi_Config_Filter::FILTER_VALUES_AND_KEYS);
        $this->assertEquals(array('FOO' => array('BAR' => 'FOOBAR')), $config->toArray());
    }
}
?>