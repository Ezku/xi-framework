<?php
/**
 * @category    Xi
 * @package     Xi_State
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_State_Grammar_Rule_Action_Input extends Xi_State_Grammar_Rule_Action
{
    protected $_in;

    public function in($state)
    {
        $this->_in = $state;
        return $this;
    }

    public function apply($fsm)
    {
        $fsm->getState($this->_in)->attachInputListener($this->getListener($this->_listener));
    }
}
