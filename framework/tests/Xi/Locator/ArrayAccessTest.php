<?php
/**
 * @category    Xi_Test
 * @package     Xi_Locator
 * @group       Xi_Locator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Locator_ArrayAccessTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->locator = new Xi_Locator;
    }

    public function testIndexCanBeSet()
    {
        $this->locator['i'] = 'foo';
        $this->assertEquals($this->locator['i'], 'foo');
    }

    public function testPropertyExistenceCanBeChecked()
    {
        $this->assertFalse(isset($this->locator['i']));
        $this->locator['i'] = 'foo';
        $this->assertTrue(isset($this->locator['i']));
    }

    public function testPropertiesCanBeProvidedInConstructor()
    {
        $locator = new Xi_Locator(array('i' => 'foo'));
        $this->assertEquals($locator['i'], 'foo');
    }

    public function testNullPropertyIsNotSet()
    {
        $this->locator['i'] = null;
        $this->assertFalse(isset($this->locator['i']));
    }

    public function testUnfoundPropertiesAreCheckedInParent()
    {
        $parent = new Xi_Locator(array('foo' => 'bar'));
        $locator = new Xi_Locator(null, $parent);

        $this->assertTrue(isset($locator['foo']));
        $this->assertEquals($locator['foo'], 'bar');
    }

    public function testSingletonValuesAreIdentical()
    {
        $this->locator['i'] = new stdClass;
        $this->assertTrue($this->locator['i'] === $this->locator['i']);
    }
}

