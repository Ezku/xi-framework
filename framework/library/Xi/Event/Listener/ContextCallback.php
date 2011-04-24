<?php
/**
 * Only provides event context to the callback
 *
 * @category    Xi
 * @package     Xi_Event
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Event_Listener_ContextCallback extends Xi_Event_Listener_Callback
{
    public function invoke($event)
    {
        return call_user_func($this->_callback, $event->getContext());
    }
}

