<?php
/**
 * Implements a DSL to create state transition rules
 *
 * @category    Xi
 * @package     Xi_State
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_State_Grammar
{
    /**
     * @var Xi_State_Machine
     */
    protected $_fsm;

    /**
     * @param Xi_State_Machine
     * @return void
     */
    public function __construct($fsm)
    {
        $this->_fsm = $fsm;
    }

    /**
     * @return Xi_State_Machine
     */
    public function getStateMachine()
    {
        return $this->_fsm;
    }

    /**
     * @param mixed input
     * @return Xi_State_Grammar_Rule_Transition
     */
    public function on($input)
    {
        $rule = new Xi_State_Grammar_Rule_Transition($this);
        return $rule->on($input);
    }

    /**
     * @param string state
     * @return Xi_State_Grammar_Rule_Transition
     */
    public function from($state)
    {
        $rule = new Xi_State_Grammar_Rule_Transition($this);
        return $rule->from($state);
    }

    /**
     * @param string state
     * @return Xi_State_Grammar_Rule_Action_Entry
     */
    public function before($state)
    {
        $rule = new Xi_State_Grammar_Rule_Action_Entry($this);
        return $rule->before($state);
    }

    /**
     * @param string state
     * @return Xi_State_Grammar_Rule_Action_Exit
     */
    public function after($state)
    {
        $rule = new Xi_State_Grammar_Rule_Action_Exit($this);
        return $rule->after($state);
    }

    /**
     * @return Xi_State_Grammar_Rule_Action_Transition
     */
    public function when()
    {
        return new Xi_State_Grammar_Rule_Action_Transition($this);
    }

    /**
     * @param string state
     * @return Xi_State_Grammar_Rule_Action_Input
     */
    public function in($state)
    {
        $rule = new Xi_State_Grammar_Rule_Action_Input($this);
        return $rule->in($state);
    }

    public function apply($rule)
    {
        return $rule->apply($this->getStateMachine());
    }
}
