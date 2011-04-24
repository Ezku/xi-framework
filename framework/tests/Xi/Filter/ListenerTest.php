<?php
/**
 * @category    Xi_Test
 * @package     Xi_Filter
 * @group       Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Filter_ListenerTest extends Xi_Filter_TestCase 
{
    public function listen($event)
    {
        $this->event = $event;
    }
    
    public function getListenerFilterMock()
    {
        $subject = new Xi_Filter_Listener($this->getFilterMock());
        $subject->attach(new Xi_Event_Listener_Callback(array($this, 'listen')));
        
        return $subject;
    }
    
    public function testTriggersEventOnFilter()
    {
        $subject = $this->getListenerFilterMock();
        
        $this->assertEquals('bar', $subject->filter('foo'));
        $this->assertTrue($this->event instanceof Xi_Event);
    }
    
    public function testEventProvidesInAndOutValues()
    {
        $this->getListenerFilterMock()->filter('foo');
        
        $this->assertEquals('foo', $this->event->in);
        $this->assertEquals('bar', $this->event->out);
    }
    
    public function testListenerCanAlterReturnValue()
    {
        $listener = $this->getMock('Xi_Event_Listener_Interface');
        $listener->expects($this->once())->method('invoke')->will($this->returnValue('foobar'));
        
        $subject = $this->getListenerFilterMock()->attach($listener);
        $this->assertEquals('foobar', $subject->filter('foo'));
    }
}
