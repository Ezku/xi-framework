<?php
/**
 * @category    Xi
 * @package     Xi_State
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
abstract class Xi_State_Grammar_Rule_Action extends Xi_State_Grammar_Rule_Abstract
{
    /**
     * @var Xi_Event_Listener_Interface
     */
    protected $_listener;

    /**
     * @var mixed input condition
     */
    protected $_on;

    public function on($input)
    {
        $this->_on = $input;
        return $this;
    }

    /**
     * Provide a listener to trigger when rule matches
     *
     * @param Xi_Event_Listener_Interface|callback
     * @return Xi_State_Grammar_Rule_Event
     */
    public function trigger($listener)
    {
        $this->_listener = $listener;
        $this->getGrammar()->apply($this);
        $this->_listener = null;
        return $this;
    }

    public function getListener($listener)
    {
        if (null === $this->_on) {
            return parent::getListener($listener);
        }
        return new Xi_Event_Listener_Conditional(parent::getListener($listener), $this->getInputValidator($this->_on));
    }
}
