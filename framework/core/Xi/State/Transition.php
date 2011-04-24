<?php
/**
 * @category    Xi
 * @package     Xi_State
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_State_Transition
{
    /**
     * @var mixed input
     */
    protected $_on;

    /**
     * @var string state
     */
    protected $_to;

    /**
     * @var Xi_Event_Listener_Collection
     */
    protected $_listeners;

    /**
     * @param Zend_Validate_Interface input condition
     * @param string to state
     * @return void
     */
    public function __construct($on, $to)
    {
        $this->_on = $on;
        $this->_to = $to;
        $this->_listeners = new Xi_Event_Listener_Collection;
    }

    /**
     * Notify listeners of the transition
     *
     * @param Xi_State_Machine
     * @return Xi_Event
     */
    public function notify($fsm)
    {
        return $this->_listeners->invoke(new Xi_Event('transition', $fsm));
    }

    /**
     * Attach a listener that will be notified if the transition is successfully
     * applied
     *
     * @param Xi_Event_Listener_Interface
     * @return Xi_State_Transition
     */
    public function attachListener($listener)
    {
        $this->_listeners->attach($listener);
        return $this;
    }

    /**
     * @return string
     */
    public function getTargetState()
    {
        return $this->_to;
    }

    /**
     * @param mixed
     * @return boolean
     */
    public function isValidInput($input)
    {
        return $this->_on->isValid($input);
    }
}
