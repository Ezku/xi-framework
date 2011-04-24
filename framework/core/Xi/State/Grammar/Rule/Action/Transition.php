<?php
/**
 * @category    Xi
 * @package     Xi_State
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_State_Grammar_Rule_Action_Transition extends Xi_State_Grammar_Rule_Action
{
    protected $_from;
    protected $_to;

    public function from($state)
    {
        $this->_from = $state;
        return $this;
    }

    public function to($state)
    {
        $this->_to = $state;
        return $this;
    }

    public function apply($fsm)
    {
        foreach($fsm->getState($this->_from)->getTransitions() as $transition) {
            if ($this->_to == $transition->getTargetState()) {
                $transition->attachListener($this->getListener($this->_listener));
            }
        }

        $this->_from = null;
        $this->_to = null;
    }
}
