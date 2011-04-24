<?php
/**
 * @category    Xi_Test
 * @package     Xi_State
 * @group       Xi_State
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_State_MachineTest extends PHPUnit_Framework_TestCase
{
    public function testCanBeCreated()
    {
        new Xi_State_Machine;
    }

    public function testCanBeCreatedWithListOfStates()
    {
        $states = array('one', 'two', 'three');
        $fsm = new Xi_State_Machine($states);
    }

    public function testStatesCanBeRetrieved()
    {
        $states = array('one', 'two', 'three');
        $fsm = new Xi_State_Machine($states);
        $this->assertEquals($states, $fsm->getStates());
    }

    public function testFirstStateIsTheInitialOne()
    {
        $fsm = new Xi_State_Machine(array('foo', 'bar'));
        $this->assertEquals('foo', $fsm->getState()->getName());
    }

    public function testInitialStateCanBeSet()
    {
        $fsm = new Xi_State_Machine(array('foo', 'bar'));
        $this->assertEquals('bar', $fsm->setInitialState('bar')->reset()->getState()->getName());
    }

    public function testSettingUnknownStateGeneratesException()
    {
        $this->setExpectedException('Xi_State_Machine_Exception');
        $fsm = new Xi_State_Machine;
        $fsm->setInitialState('foo');
    }

    public function testInputCanBeProcessed()
    {
        $fsm = new Xi_State_Machine(array('state'));
        $fsm->process('input');
    }

    public function testProcessingInputWithNoTransitionReturnsFalse()
    {
        $fsm = new Xi_State_Machine(array('state'));
        $this->assertEquals(false, $fsm->process('input'));
    }

    public function testGrammarCanBeRetrieved()
    {
        $fsm = new Xi_State_Machine;
        $this->assertTrue($fsm->record() instanceof Xi_State_Grammar);
        $this->assertTrue($fsm->record()->getStateMachine() === $fsm);
    }

    public function testBasicTransitionCanBeTriggered()
    {
        $fsm = new Xi_State_Machine(array('start', 'finish'));
        $fsm->record()->on('proceed')->from('start')->to('finish');
        $this->assertEquals('finish', $fsm->process('proceed'));
    }

    public function testCircularTransitionCanBeTriggered()
    {
        $fsm = new Xi_State_Machine(array('one', 'two'));
        $fsm->record()->on('toggle')->from('one')->to('two')->from('two')->to('one');
        $this->assertEquals('two', $fsm->process('toggle'));
        $this->assertEquals('one', $fsm->process('toggle'));
        $this->assertEquals('two', $fsm->process('toggle'));
    }

    public function testAllCatchingTransitionCanBeTriggered()
    {
        $fsm = new Xi_State_Machine(array('one', 'two', 'three'));
        $fsm->record()->on('proceed')->to('three');
        $this->assertEquals('three', $fsm->setInitialState('one')->process('proceed'));
        $this->assertEquals('three', $fsm->setInitialState('two')->process('proceed'));
    }

    public function testEntryStateCanBeListened()
    {
        $fsm = new Xi_State_Machine(array('one', 'two'));
        $fsm->record()->on('proceed')->from('one')->to('two');

        $listener = $this->getMock('Xi_Event_Listener_Interface');
        $listener->expects($this->once())->method('invoke');

        $fsm->record()->before('two')->trigger($listener);
        $fsm->process('proceed');
    }

    public function testExitStateCanBeListened()
    {
        $fsm = new Xi_State_Machine(array('one', 'two'));
        $fsm->record()->on('proceed')->from('one')->to('two');

        $listener = $this->getMock('Xi_Event_Listener_Interface');
        $listener->expects($this->once())->method('invoke');

        $fsm->record()->after('one')->trigger($listener);
        $fsm->process('proceed');
    }

    public function testStateTransitionCanBeListened()
    {
        $fsm = new Xi_State_Machine(array('one', 'two'));
        $fsm->record()->on('toggle')->from('one')->to('two');

        $listener = $this->getMock('Xi_Event_Listener_Interface');
        $listener->expects($this->once())->method('invoke');

        $fsm->record()->when()->from('one')->to('two')->trigger($listener);
        $fsm->process('toggle');
        $fsm->process('toggle');
    }

    public function testListenerCanBeDefinedWhenRecordingTransition()
    {
        $listener = $this->getMock('Xi_Event_Listener_Interface');
        $listener->expects($this->once())->method('invoke');

        $fsm = new Xi_State_Machine(array('one', 'two'));
        $fsm->record()->on('toggle')->from('one')->with($listener)->to('two');
        $fsm->process('toggle');
    }

    public function testInputCanBeListened()
    {
        $listener = $this->getMock('Xi_Event_Listener_Interface');
        $listener->expects($this->once())->method('invoke');

        $fsm = new Xi_State_Machine;
        $fsm->record()->in('init')->on('input')->trigger($listener);

        $fsm->process('input');
    }

    public function testCallbackIsWrappedInContextCallback()
    {
        $fsm = new Xi_State_Machine;
        $fsm->record()->in('init')->on('input')->trigger(array($this, 'callback'));
        $fsm->process('input');
        $this->assertTrue($this->_context === $fsm);
    }

    protected $_context;

    public function callback($context)
    {
        $this->_context = $context;
    }

    public function testInputConditionCanBeAValidator()
    {
        $fsm = new Xi_State_Machine(array('one', 'two'));
        $fsm->record()->on(new Xi_Validate_Equal('foo'))->from('one')->to('two');

        $this->assertEquals('two', $fsm->process('foo'));
    }
}
