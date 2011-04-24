<?php
/**
 * @category    Xi
 * @package     Xi_Acl
 * @subpackage  Xi_Acl_Assert
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Acl_Assert_Event implements Zend_Acl_Assert_Interface
{
    /**
     * @var Xi_Event_Dispatcher
     */
    protected $_eventDispatcher;
    
    /**
     * @return Xi_Event_Dispatcher
     */
    public function getEventDispatcher()
    {
        if (null === $this->_eventDispatcher) {
            $this->_eventDispatcher = Xi_Event_Dispatcher::getInstance($this->getEventDispatcherName());
        }
        return $this->_eventDispatcher;
    }
    
    /**
     * @return string
     */
    public function getEventDispatcherName()
    {
        return __CLASS__;
    }
    
    /**
     * @param Zend_Acl $acl
     * @param Zend_Acl_Role_Interface $role
     * @param Zend_Acl_Resource_Interface $resource
     * @param string $privilege
     * @return string
     */
    public function getEventName(Zend_Acl $acl, Zend_Acl_Role_Interface $role = null, Zend_Acl_Resource_Interface $resource = null, $privilege = null)
    {
        return 'assert';
    }
    
    /**
     * @param Zend_Acl $acl
     * @param Zend_Acl_Role_Interface $role
     * @param Zend_Acl_Resource_Interface $resource
     * @param string $privilege
     * @return Xi_Event
     */
    public function getEvent(Zend_Acl $acl, Zend_Acl_Role_Interface $role = null, Zend_Acl_Resource_Interface $resource = null, $privilege = null)
    {
        return new Xi_Event(
            $this->getEventName($acl, $role, $resource, $privilege),
            $this,
            array(
                'acl' => $acl,
                'role' => $role,
                'resource' => $resource,
                'privilege' => $privilege
            )
        );
    }
    
    /**
     * Dispatches an event and uses the result to determine whether to grant
     * access. If the event is cancelled, access is denied.
     * 
     * @param Zend_Acl $acl
     * @param Zend_Acl_Role_Interface $role
     * @param Zend_Acl_Resource_Interface $resource
     * @param string $privilege
     * @return boolean
     */
    public function assert(Zend_Acl $acl, Zend_Acl_Role_Interface $role = null, Zend_Acl_Resource_Interface $resource = null, $privilege = null)
    {
        $event = $this->getEvent($acl, $role, $resource, $privilege);
        $event = $this->getEventDispatcher()->notify($event);
        
        if ($event->isCancelled()) {
            return false;
        }
        
        if ($event->hasReturnValue()) {
            return (boolean) $event->getReturnValue();
        }
        
        return false;
    }
}
