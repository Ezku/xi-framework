<?php
class Xi_State
{
    /**
     * @var string
     */
    protected $_name;

    /**
     * @var array Xi_State_Transition
     */
    protected $_transitions = array();

    /**
     * @var Xi_Event_Listener_Collection
     */
    protected $_entryListeners;

    /**
     * @var Xi_Event_Listener_Collection
     */
    protected $_exitListeners;

    /**
     * @var Xi_Event_Listener_Collection
     */
    protected $_inputListeners;

    /**
     * @param Xi_State_Machine
     * @param string state name
     * @return void
     */
    public function __construct($name)
    {
        $this->_name = $name;
    }

    /**
     * @return string state name
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Add transition to state
     *
     * @param Xi_State_Transition
     */
    public function addTransition($transition)
    {
        $this->_transitions[] = $transition;
        return $this;
    }

    /**
     * Get transitions
     *
     * @return array Xi_State_Transition
     */
    public function getTransitions()
    {
        return $this->_transitions;
    }

    /**
     * Attach listener that will be notified when entering state
     *
     * @param Xi_Event_Listener_Interface
     * @return Xi_State
     */
    public function attachEntryListener($listener)
    {
        if (null === $this->_entryListeners) {
            $this->_entryListeners = new Xi_Event_Listener_Collection;
        }
        $this->_entryListeners->attach($listener);
        return $this;
    }

    /**
     * Attach listener that will be notified when leaving state
     *
     * @param Xi_Event_Listener_Interface
     * @return Xi_State
     */
    public function attachExitListener($listener)
    {
        if (null === $this->_exitListeners) {
            $this->_exitListeners = new Xi_Event_Listener_Collection;
        }
        $this->_exitListeners->attach($listener);
        return $this;
    }

    /**
     * Attach listener that will be notified when receiving input
     *
     * @param Xi_Event_Listener_Interface
     * @return Xi_State
     */
    public function attachInputListener($listener)
    {
        if (null === $this->_inputListeners) {
            $this->_inputListeners = new Xi_Event_Listener_Collection;
        }
        $this->_inputListeners->attach($listener);
        return $this;
    }

    /**
     * Notify listeners of entering state
     *
     * @param Xi_State_Machine
     * @return Xi_Event
     */
    public function notifyEntry($fsm)
    {
        $event = new Xi_Event('entry', $fsm);
        return isset($this->_entryListeners) ? $this->_entryListeners->invoke($event) : $event;
    }

    /**
     * Notify listeners of leaving state
     *
     * @param Xi_State_Machine
     * @return Xi_Event
     */
    public function notifyExit($fsm)
    {
        $event = new Xi_Event('exit', $fsm);
        return isset($this->_exitListeners) ? $this->_exitListeners->invoke($event) : $event;
    }

    /**
     * Notify listeners of receiving input
     *
     * @param Xi_State_Machine
     * @return Xi_Event
     */
    public function notifyInput($fsm)
    {
        $event = new Xi_Event('input', $fsm);
        return isset($this->_inputListeners) ? $this->_inputListeners->invoke($event) : $event;
    }

    /**
     * Process input.
     *
     * @param Xi_State_Machine
     * @return false|Xi_State_Transition
     */
    public function process($fsm)
    {
        $this->notifyInput($fsm);
        foreach ($this->getTransitions() as $transition) {
            if ($transition->isValidInput($fsm->getInput())) {
                return $transition;
            }
        }
        return false;
    }
}
