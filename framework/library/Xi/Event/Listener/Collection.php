<?php
/**
 * @category    Xi
 * @package     Xi_Event
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Event_Listener_Collection implements Xi_Event_Listener_Collection_Interface
{
    /**
     * @var array Xi_Event_Listener_Interface
     */
    protected $_listeners = array();
    
    /**
     * Provide an array of Xi_Event_Listener_Interface instances to set default listeners
     *
     * @param array $listeners
     */
    public function __construct($listeners = array())
    {
        foreach ($listeners as $listener) {
            $this->attach($listener);
        }
    }

    /**
     * Attach event listener to collection
     *
     * @param Xi_Event_Listener_Interface
     * @return Xi_Event_Listener_Collection
     */
    public function attach($listener)
    {
        $this->_listeners[] = $listener;
        return $this;
    }

    /**
     * @param Xi_Event
     * @return Xi_Event
     */
    public function invoke($event)
    {
        foreach ($this->_listeners as $listener) {
            $value = $listener->invoke($event);
            if (null !== $value) {
                $event->setReturnValue($value);
            }
        }
        return $event;
    }
}
