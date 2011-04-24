<?php
/**
 * @category    Xi_Test
 * @package     Xi_Event
 * @group       Xi_Event
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_EventTest extends PHPUnit_Framework_TestCase
{
    public function testCanBeInstantiatedWithName()
    {
        $event = new Xi_Event('name');
        $this->assertEquals('name', $event->getName());
    }

    public function testHasNoContextIfNotProvided()
    {
        $event = new Xi_Event('name');
        $this->assertFalse($event->hasContext());
    }

    public function testCanBeInstantiatedWithContext()
    {
        $event = new Xi_Event('name', $this);
        $this->assertTrue($event->hasContext());
        $this->assertTrue($this === $event->getContext());
    }

    public function testHasNoParametersIfNotProvided()
    {
        $event = new Xi_Event('name');
        $this->assertFalse($event->hasParams());
        $this->assertEquals(0, count($event->getParams()));
    }

    public function testCanBeInstantiatedWithParameters()
    {
        $event = new Xi_Event('name', $this, array('foo' => 'bar'));
        $this->assertTrue($event->hasParams());
        $this->assertTrue(isset($event->foo));
        $this->assertEquals('bar', $event->foo);
        $this->assertTrue(isset($event->getParams()->foo));
        $this->assertEquals('bar', $event->getParams()->foo);
    }

    public function testCanBeCancelled()
    {
        $event = new Xi_Event('name');
        $this->assertFalse($event->isCancelled());
        $this->assertTrue($event->cancel()->isCancelled());
    }
}
