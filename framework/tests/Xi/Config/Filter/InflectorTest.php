<?php
/**
 * @category    Xi_Test
 * @package     Xi_Config
 * @group       Xi_Config
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Config_Filter_InflectorTest extends PHPUnit_Framework_TestCase
{
    public function testCreatesDefaultInflectorIfNotProvided()
    {
        $config = new Xi_Config_Filter_Inflector(array());
        $this->assertTrue($config->getFilter() instanceof Xi_Filter_Inflector_Recursive);
    }
    
    public function testDefaultConfigIsReadOnly()
    {
        $config = new Xi_Config_Filter_Inflector(array());
        $this->assertTrue($config->getConfig()->isReadOnly());
    }
    
    public function testUsesScalarValuesForRules()
    {
        $config = new Xi_Config_Filter_Inflector(array('foo' => 'bar', 'bar' => ':foo'));
        $this->assertEquals('bar', $config->bar);
    }
    
    public function testModifyingValuesImpliesModifyingRules()
    {
        $config = new Xi_Config_Filter_Inflector(new Xi_Config(array('foo' => 'bar', 'bar' => ':foo'), true));
        $config->foo = 'foobar';
        $this->assertEquals('foobar', $config->bar);
    }
    
    public function testRulesArePassedToChildren()
    {
        $config = new Xi_Config_Filter_Inflector(array('foo' => 'bar', 'bar' => array('foobar' => ':foo')));
        $this->assertEquals('bar', $config->bar->foobar);
    }
    
    public function testChangesInRulesArePassedToChildren()
    {
        $config = new Xi_Config_Filter_Inflector(array('foo' => 'bar', 'bar' => array('foobar' => ':foo')));
        $config->addRules(array('foo' => 'barfoo'));
        $this->assertEquals('barfoo', $config->bar->foobar);
    }
    
    public function testChangesInChildrenAreNotPassedToParent()
    {
        $config = new Xi_Config_Filter_Inflector(array('foo' => ':bar', 'bar' => array('foobar' => ':foo')));
        $config->addRules(array('bar' => 'foobar'));
        $config->bar->addRules(array('bar' => 'barfoo'));
        $this->assertEquals('foobar', $config->foo);
    }
    
    public function testValuesCanBeAccessedWithMethod()
    {
        $config = new Xi_Config_Filter_Inflector(array('foo' => 'bar'));
        $this->assertEquals('bar', $config->foo());
    }
    
    public function testRulesCanBeProvidedWhenAccessingWithMethod()
    {
        $config = new Xi_Config_Filter_Inflector(array('foo' => ':bar'));
        $this->assertEquals('foobar', $config->foo(array('bar' => 'foobar')));
    }
    
    public function testAccessingChildWithRulesProducesVolatileInstance()
    {
        $config = new Xi_Config_Filter_Inflector(array('one' => 'two', 'three' => array('four' => ':one')));
        $this->assertEquals('five', $config->three(array('one' => 'five'))->four);
        $this->assertEquals('two', $config->three->four);
    }
}
