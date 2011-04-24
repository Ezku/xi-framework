<?php
/**
 * @category    Xi
 * @package     Xi_State
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_State_Grammar_Rule_Transition extends Xi_State_Grammar_Rule_Abstract
{
    /**
     * @var string event
     */
    protected $_on;

    /**
     * @var string state
     */
    protected $_from;

    /**
     * @var string state
     */
    protected $_to;

    /**
     * @var Xi_Event_Listener_Interface
     */
    protected $_with;

    public function on($input)
    {
        $this->_on = $input;
        return $this;
    }

    public function from($state)
    {
        $this->_from = $state;
        return $this;
    }

    public function to($state)
    {
        $this->_to = $state;
        $this->getGrammar()->apply($this);
        return $this;
    }

    public function with($listener)
    {
        $this->_with = $listener;
        return $this;
    }

    public function apply($fsm)
    {
        $transition = $this->getTransition();
        if (null === $this->_from) {
            foreach ($fsm->getStates() as $state) {
                $fsm->getState($state)->addTransition($transition);
            }
        } else {
            $fsm->getState($this->_from)->addTransition($transition);
        }
        $this->_from = null;
        $this->_to = null;
        $this->_with = null;
    }

    /**
     * @return Xi_State_Transition
     */
    public function getTransition()
    {
        $transition = new Xi_State_Transition($this->getValidator($this->_on), $this->_to);
        if (null !== $this->_with) {
            $transition->attachListener($this->getListener($this->_with));
        }
        return $transition;
    }
}
