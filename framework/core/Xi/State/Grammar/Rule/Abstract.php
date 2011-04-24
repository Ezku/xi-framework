<?php
/**
 * @category    Xi
 * @package     Xi_State
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
abstract class Xi_State_Grammar_Rule_Abstract
{
    protected $_grammar;

    public function __construct($grammar)
    {
        $this->_grammar = $grammar;
    }

    public function getGrammar()
    {
        return $this->_grammar;
    }

    public function getListener($listener)
    {
        if (!$listener instanceof Xi_Event_Listener_Interface) {
            $listener = $this->_getDefaultListener($listener);
        }
        return $listener;
    }

    public function _getDefaultListener($listener)
    {
        return new Xi_Event_Listener_ContextCallback($listener);
    }

    /**
     * @param mixed|Zend_Validate_Interface
     * @return Zend_Validate_Interface
     */
    public function getValidator($condition)
    {
        if (!$condition instanceof Zend_Validate_Interface) {
            $condition = $this->_getDefaultValidator($condition);
        }
        return $condition;
    }

    /**
     * @param mixed|Zend_Validate_Interface
     * @return Xi_State_Validate_Input
     */
    public function getInputValidator($condition)
    {
        return new Xi_State_Validate_Input($this->getValidator($condition));
    }

    /**
     * @return Zend_Validate_Interface
     */
    public function _getDefaultValidator($condition)
    {
        if (is_array($condition)) {
            foreach ($condition as $key => $value) {
                $condition[$key] = new Xi_Validate_Equal($value);
            }
            return new Xi_Validate_Or($condition);
        }
        return new Xi_Validate_Equal($condition);
    }

    /**
     * Apply grammar rule to state machine
     *
     * @param Xi_State_Machine
     * @return void
     */
    abstract public function apply($fsm);
}
