<?php
/**
 * @category    Xi_Test
 * @package     Xi_Locator
 * @group       Xi_Locator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Locator_NamespacedTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->locator = new Xi_Locator_Namespaced;
    }

    public function testNamespaceCanBeAccessedWithDots()
    {
        $this->locator['one.two'] = 'foo';

        $this->assertTrue(isset($this->locator['one.two']));
        $this->assertEquals($this->locator['one.two'], 'foo');

        $this->assertTrue(isset($this->locator['one']['two']));
        $this->assertEquals($this->locator['one']['two'], 'foo');
    }

    public function testNamespacesAreCheckedAsWhole()
    {
        $parent = new Xi_Locator_Namespaced(array('one' => array('two' => 'foo'),
                                       'two' => 'not what we want'));
        $locator = new Xi_Locator_Namespaced(null, $parent);

        $locator['one'] = 'not what we want';
        $locator['two'] = 'not what we want';

        $this->assertTrue(isset($locator['one.two']));
        $this->assertEquals($locator['one.two'], 'foo');
    }
}