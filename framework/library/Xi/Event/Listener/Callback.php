<?php
/**
 * Redirects event handling to a callback function
 *
 * @category    Xi
 * @package     Xi_Event
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Event_Listener_Callback implements Xi_Event_Listener_Interface
{
    protected $_callback;

    public function __construct($callback)
    {
        $this->_callback = $callback;
    }

    public function invoke($event)
    {
        return call_user_func($this->_callback, $event);
    }
}

