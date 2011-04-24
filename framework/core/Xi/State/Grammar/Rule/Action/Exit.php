<?php
/**
 * @category    Xi
 * @package     Xi_State
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_State_Grammar_Rule_Action_Exit extends Xi_State_Grammar_Rule_Action
{
    protected $_after;

    public function after($state)
    {
        $this->_after = $state;
        return $this;
    }

    public function apply($fsm)
    {
        $fsm->getState($this->_after)->attachExitListener($this->getListener($this->_listener));
        $this->_after = null;
    }
}
