<?php
/**
 * Wraps an event listener
 *
 * @category    Xi
 * @package     Xi_Event
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Event_Listener_Outer implements Xi_Event_Listener_Interface
{
    protected $_listener;

    public function __construct($listener)
    {
        $this->_listener = $listener;
    }

    public function invoke($event)
    {
        return $this->_listener->invoke($event);
    }
}


