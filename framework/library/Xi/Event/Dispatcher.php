<?php
/**
 * @category    Xi
 * @package     Xi_Event
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Event_Dispatcher extends Xi_Array_Validate_Type
{
    /**
     * @var array Xi_Event_Dispatcher
     */
    protected static $_instances = array();

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct(array(), 'Xi_Event_Listener_Collection');
    }

    /**
     * @param string instance name
     * @return boolean
     */
    public static function hasInstance($name)
    {
        return isset(self::$_instances[$name]);
    }

    /**
     * @param string instance name, defaults to 'global'
     * @return Xi_Event_Dispatcher
     */
    public static function getInstance($name = 'global')
    {
        if (!isset(self::$_instances[$name])) {
            self::$_instances[$name] = new self;
        }
        return self::$_instances[$name];
    }
    
    /**
     * Retrieve event listener collection instance to use with an event
     * namespace
     *
     * @return Xi_Event_Subject_Interface
     */
    public function getListenerSubject()
    {
        return new Xi_Event_Listener_Collection;
    }

    /**
     * Remove instance by name
     *
     * @param string
     * @return void
     */
    public static function clearInstance($name = 'global')
    {
        unset(self::$_instances[$name]);
    }

    /**
     * Remove all existing instances of Xi_Event_Dispatcher in
     * {@link $_instances}.
     *
     * @return void
     */
    public static function resetInstances()
    {
        self::$_instances = array();
    }

    /**
     * Attach listener to event by name
     *
     * @param string name
     * @param Xi_Event_Listener_Interface
     * @return Xi_Event_Dispatcher
     */
    public function attach($name, $listener)
    {
        if (!$this->offsetExists($name)) {
            $this->offsetSet($name, $this->getListenerSubject());
        }
        $this->offsetGet($name)->attach($listener);
        return $this;
    }

    /**
     * Notify listeners of an event.
     *
     * @param Xi_Event
     * @return Xi_Event
     */
    public function notify(Xi_Event $event)
    {
        $name = $event->getName();

        if (isset($this->$name)) {
            $this->$name->invoke($event);
        }
        return $event;
    }

    /**
     * Method access redirected to {@link invoke()}. $dispatcher->foo([args]) is
     * equal to $dispatcher->invoke(new Xi_Event('foo' [, args])) unless the first argument is
     * an instance of Xi_Event, in which case the call is equal to $dispatcher-
     * >notify([args]);
     *
     * @param string method name
     * @param array arguments
     * @return Xi_Event
     */
    public function __call($name, $args)
    {
        if (!current($args) instanceof Xi_Event) {
            array_unshift($args, $name);
            $args = array(Xi_Class::create('Xi_Event', $args));
        }
        
        return call_user_func_array(array($this, 'notify'), $args);
    }
}
