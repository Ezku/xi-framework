<?php
/**
 * Notes on the implications of the structure:
 * - States have to be defined before defining grammars
 * - Transitions have to be defined before adding transition listeners
 *
 * @category    Xi
 * @package     Xi_State
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_State_Machine
{
    /**
     * @var array { name: Xi_State }
     */
    protected $_states = array();

    /**
     * @var false|Xi_State current state
     */
    protected $_state;

    /**
     * @var Xi_Stack
     */
    protected $_stack;

    /**
     * @var Xi_Stack
     */
    protected $_output;

    /**
     * @var string
     */
    protected $_initialState;

    /**
     * @var string
     */
    protected $_input;

    /**
     * @param array valid states, the first of which will be set as initial
     * @return void
     */
    public function __construct(array $states = null)
    {
        if (null === $states) {
            $states = array('init');
        }

        foreach ($states as $state) {
            $this->_states[$state] = new Xi_State($state);
        }
        $this->_initialState = reset($states);
        $this->reset();
    }

    /**
     * Set initial state
     *
     * @param string state
     * @return Xi_State_Machine
     */
    public function setInitialState($state)
    {
        $this->_initialState = $this->getState($state)->getName();
        return $this;
    }

    /**
     * Reset machine: set state to initial state, format stack and input
     *
     * @return Xi_State_Machine
     */
    public function reset()
    {
        $this->_state = $this->getState($this->_initialState);
        $this->_stack = new Xi_Stack;
        $this->_output = new Xi_Stack;
        $this->_input = null;

        return $this;
    }

    /**
     * @return array valid states
     */
    public function getStates()
    {
        return array_keys($this->_states);
    }

    /**
     * Record a grammar for this state machine
     *
     * @return Xi_State_Grammar
     */
    public function record()
    {
        return new Xi_State_Grammar($this);
    }

    /**
     * Set current state
     *
     * @param string state
     * @return Xi_State_Machine
     */
    protected function _setState($state)
    {
        $this->_state = $this->getState($state);
        return $this;
    }

    /**
     * Get state by name or the current one if not specified.
     *
     * @param null|string
     * @return Xi_State
     */
    public function getState($state = null)
    {
        if (null === $state) {
            return $this->_state;
        }
        if (!isset($this->_states[$state])) {
            throw new Xi_State_Machine_Exception(sprintf('Unknown state "%s"', $state));
        }
        return $this->_states[$state];
    }

    /**
     * @return mixed last processed input
     */
    public function getInput()
    {
        return $this->_input;
    }

    /**
     * @return Xi_Stack
     */
    public function getStack()
    {
        return $this->_stack;
    }

    /**
     * @return Xi_Stack
     */
    public function getOutput()
    {
        return $this->_output;
    }

    /**
     * Process input
     *
     * @param mixed input
     * @return false|string result state or false if no transitions applied
     */
    public function process($input)
    {
        $this->_input = $input;
        $currentState = $this->getState();
        if ($transition = $currentState->process($this)) {
            if ($currentState->getName() != $transition->getTargetState()) {
                $currentState->notifyExit($this);
            }

            $newState = $this->_setState($transition->getTargetState())->getState();
            $transition->notify($this);

            if ($currentState->getName() != $newState->getName()) {
                $newState->notifyEntry($this);
            }
            return $this->getState()->getName();
        }
        return false;
    }

    public function halt($message = null)
    {
        throw new Xi_State_Machine_Exception('Input was not accepted' . isset($message) ? ': ' . $message : '');
    }
}
