<?php
/**
 * @category    Xi_Test
 * @package     Xi_Event
 * @group       Xi_Event
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Event_DispatcherTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Xi_Event_Dispatcher::resetInstances();
    }

    public function testDefaultSingletonInstanceIsNamedGlobal()
    {
        $this->assertFalse(Xi_Event_Dispatcher::hasInstance('global'));
        $this->assertTrue(Xi_Event_Dispatcher::getInstance() instanceof Xi_Event_Dispatcher);
        $this->assertTrue(Xi_Event_Dispatcher::hasInstance('global'));
    }

    public function testReturnsEventOnNotify()
    {
        $event = new Xi_Event('event');
        $dispatcher = new Xi_Event_Dispatcher;
        $this->assertTrue($event === $dispatcher->notify($event));
    }

    public function testCanBeNotifiedViaMagicInterface()
    {
        $dispatcher = new Xi_Event_Dispatcher;
        $this->assertEquals('event', $dispatcher->event()->getName());
    }

    public function testListenerIsInvokedWithNotify()
    {
        $event = new Xi_Event('event');

        $listener = $this->getMock('Xi_Event_Listener_Interface');
        $listener->expects($this->once())->method('invoke')->with($this->equalTo($event));

        $dispatcher = new Xi_Event_Dispatcher;
        $dispatcher->attach('event', $listener);
        $dispatcher->notify($event);
    }

    public function testListenerIsInvokedWithMagicInterface()
    {
        $listener = $this->getMock('Xi_Event_Listener_Interface');
        $listener->expects($this->once())->method('invoke');

        $dispatcher = new Xi_Event_Dispatcher;
        $dispatcher->attach('event', $listener);
        $dispatcher->event();
    }
}
