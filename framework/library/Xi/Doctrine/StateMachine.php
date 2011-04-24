<?php
/**
 * @category    Xi
 * @package     Xi_Doctrine
 * @subpackage  Xi_Doctrine_StateMachine
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Doctrine_StateMachine extends Doctrine_Template
{
    /**
     * @var string
     */
    protected $_field;
    
    /**
     * @var array
     */
    protected $_states = array();
    
    /**
     * @var array
     */
    protected $_transitions = array();
    
    /**
     * @var string
     */
    protected $_default;

    /**
     * __construct
     *
     * @param string $array 
     * @return void
     */
    public function __construct(array $options = array())
    {
        $options += array('field' => null, 'states' => array(), 'transitions' => array(), 'default' => null);
        $this->_setField($options['field'])
             ->_setStates($options['states'])
             ->_setTransitions($options['transitions'])
             ->_setDefault($options['default']);
    }
    
    /**
     * Set record field to store state in
     * 
     * @param string
     * @return StateMachine
     */
    protected function _setField($field)
    {
        $this->_field = $field;
        return $this;
    }
    
    /**
     * Set possible states
     * 
     * @param array
     * @return StateMachine
     */
    protected function _setStates($states)
    {
        if (empty($states)) {
            throw new Xi_Doctrine_StateMachine_Exception('StateMachine needs to be provided a list of states');
        }
        $this->_states = array_map('strtolower', $states);
        return $this;
    }
    
    /**
     * Set the transitions that are triggered by events
     * 
     * @param array
     * @return StateMachine
     */
    protected function _setTransitions($transitions)
    {
        $this->_transitions = $transitions;
        return $this;
    }
    
    /**
     * Set the default state
     * 
     * @param null|string $default
     * @return StateMachine
     */
    protected function _setDefault($default)
    {
        if (null === $default) {
            $default = reset($this->_states);
        }
        $this->_default = $default;
        return $this;
    }
    
    public function setTableDefinition()
    {
        $table = $this->getTable();
        foreach ($this->_getEvents() as $event) {
            $table->setMethodOwner($event, __CLASS__);
        }
        $length = $this->_getStateColumnLength();
        $this->hasColumn($this->_field, 'enum', $length, array('values' => $this->_states, 'default' => $this->_default));
    }
    
    /**
     * @return int
     */
    protected function _getStateColumnLength()
    {
        // Check Doctrine for whether we're using native enums or emulating them with integers
        $native = Doctrine_Manager::getInstance()->getAttribute(Doctrine::ATTR_USE_NATIVE_ENUM);
        $length = 0;
        // The setting is null by default in Doctrine 1.0, but corresponds to native enums being enabled
        if ($native || null === $native) {
            foreach ($this->_states as $state) {
                $stateLength = strlen($state);
                if ($stateLength > $length) {
                    $length = $stateLength;
                }
            }
        } else {
            $length = count($this->_states);
        }
        return $length;
    }
    
    /**
     * @return array
     */
    protected function _getEvents()
    {
        return array_keys($this->_transitions);
    }
    
    /**
     * @param string $event
     * @return boolean
     */
    protected function _hasEvent($event)
    {
        return isset($this->_transitions[$event]);
    }
    
    /**
     * @param string $event
     * @return array
     */
    protected function _getEventTransitions($event)
    {
        if (!$this->_hasEvent($event)) {
            throw new Xi_Doctrine_StateMachine_Exception("Transition '$event' not recognized");
        }
        return $this->_transitions[$event];
    }
    
    /**
     * Trigger a transition event
     * 
     * @param string $event
     * @return Doctrine_Record
     * @throws Xi_Doctrine_StateMachine_Exception
     */
    public function trigger($event)
    {
        if (!$this->_hasEvent($event)) {
            throw new Xi_Doctrine_StateMachine_Exception("A transition event by the name '$event' could not be found");
        }
        
        $transitions = $this->_getEventTransitions($event);
        if ($this->_isValidTransition($transitions)) {
            return $this->_applyTransition($transitions);
        } else {
            foreach ($transitions as $transition) {
                if (is_array($transition) && $this->_isValidTransition($transition)) {
                    return $this->_applyTransition($transition);
                }
            }
        }
        throw new Xi_Doctrine_StateMachine_Exception("No valid transitions for event '$event'");
    }
    
    /**
     * @param array $transition
     * @return boolean
     */
    protected function _isValidTransition($transition)
    {
        return !empty($transition['to']);
    }
    
    /**
     * @param array $transition
     * @return Doctrine_Record
     * @throws Xi_Doctrine_StateMachine_Exception
     */
    protected function _applyTransition($transition)
    {
        if (empty($transition['from'])) {
            return $this->_setInvokerState($transition['to']);
        }
        $state = $this->_getInvokerState();
        foreach ((array) $transition['from'] as $from) {
            if ($this->_matchFrom($from, $state)) {
                return $this->_setInvokerState($transition['to']);
            }
        }
        throw new Xi_Doctrine_StateMachine_Exception("No matching transitions from state '$state'");
    }
    
    /**
     * Match $from condition with current $state
     *
     * @param string|array $from
     * @param string $state
     * @return boolean
     */
    protected function _matchFrom($from, $state)
    {
        if (is_array($from)) {
            return in_array($state, $from);
        }
        return $from == $state;
    }
    
    /**
     * @param string $state
     * @return Doctrine_Record
     * @throws Xi_Doctrine_StateMachine_Exception
     */
    protected function _setInvokerState($state)
    {
        if (!in_array($state, $this->_states)) {
            throw new Xi_Doctrine_StateMachine_Exception("Unknown state '$state'");
        }
        $invoker = $this->getInvoker();
        $invoker->{$this->_field} = $state;
        return $invoker;
    }
    
    /**
     * @return string
     */
    protected function _getInvokerState()
    {
        $invoker = $this->getInvoker();
        return $invoker->{$this->_field};
    }
    
    /**
     * @param string $method
     * @return boolean
     */
    protected function _getStateByCheckerMethod($method)
    {
        if (strpos($method, 'is') === 0) {
            $state = strtolower(substr($method, 2));
            if (in_array($state, $this->_states)) {
                return $state;
            }
        }
        return false;
    }
    
    /**
     * Returns true if the provided state is the current one
     * 
     * @param $state
     * @return boolean
     */
    public function checkState($state)
    {
        return $state == $this->_getInvokerState();
    }
    
    /**
     * @param string $method
     * @param array $args
     * @return mixed
     * @throws Xi_Doctrine_StateMachine_Exception
     */
    public function __call($method, $args)
    {
        if ($this->_hasEvent($method)) {
            return $this->trigger($method);
        }
        if ($state = $this->_getStateByCheckerMethod($method)) {
            return $this->checkState($state);
        }
        throw new Xi_Doctrine_StateMachine_Exception("Method '$method' does not exist and didn't match any event triggers or state checkers");
    }
}
