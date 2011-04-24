<?php
/**
 * Notifies listeners when the filter is triggered
 * 
 * @category    Xi
 * @package     Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_Listener extends Xi_Filter_Outer implements Xi_Event_Subject_Interface
{
    /**
     * @var Xi_Event_Listener_Collection
     */
    protected $_listeners;
    
    /**
     * Name of the event to trigger on filter
     * 
     * @var string
     */
    protected $_event;
    
    /**
     * @param Zend_Filter_Interface $filter
     * @param string $event
     */
    public function __construct($filter, $event = 'filter')
    {
        parent::__construct($filter);
        $this->_listeners = new Xi_Event_Listener_Collection;
        $this->_event = $event;
    }
    
    public function attach($listener)
    {
        $this->_listeners->attach($listener);
        return $this;
    }
    
    public function filter($value)
    {
        $filtered = parent::filter($value);
        $event = $this->_listeners->invoke(new Xi_Event($this->_event, $this, array('in' => $value, 'out' => $filtered)));
        return $event->hasReturnValue() ? $event->getReturnValue() : $filtered;
    }
}
