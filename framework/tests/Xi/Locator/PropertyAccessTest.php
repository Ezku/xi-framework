<?php
/**
 * @category    Xi_Test
 * @package     Xi_Locator
 * @group       Xi_Locator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Locator_PropertyAccessTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->locator = new Xi_Locator;
    }

    public function testPropertyCanBeSet()
    {
        $this->locator->property = 'foo';
        $this->assertEquals($this->locator->property, 'foo');
    }

    public function testSingletonValuesAreIdentical()
    {
        $this->locator->property = new stdClass;
        $this->assertTrue($this->locator->property === $this->locator->property);
    }

    public function testPropertyExistenceCanBeChecked()
    {
        $this->assertFalse(isset($this->locator->property));
        $this->locator->property = 'foo';
        $this->assertTrue(isset($this->locator->property));
    }

    public function testPropertiesCanBeProvidedInConstructor()
    {
        $locator = new Xi_Locator(array('property' => 'foo'));
        $this->assertEquals($locator->property, 'foo');
    }

    public function testNullPropertyIsNotSet()
    {
        $this->locator->property = null;
        $this->assertFalse(isset($this->locator->property));
    }

    public function testUnfoundPropertiesAreCheckedInParent()
    {
        $parent = new Xi_Locator(array('foo' => 'bar'));
        $locator = new Xi_Locator(null, $parent);

        $this->assertTrue(isset($locator->foo));
        $this->assertEquals($locator->foo, 'bar');
    }
}


