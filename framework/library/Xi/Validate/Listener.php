<?php
/**
 * Allows listening on validation result
 * 
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Validate_Listener extends Xi_Validate_Outer
{
    /**
     * @var Xi_Event_Dispatcher
     */
    protected $_dispatcher;
    
    /**
     * @param Zend_Validate_Interface $validator
     * @param Xi_Event_Dispatcher $dispatcher
     */
    public function __construct($validator, $dispatcher = null)
    {
        parent::__construct($validator);
        if (null === $dispatcher) {
            $dispatcher = new Xi_Event_Dispatcher;
        }
        $this->_dispatcher = $dispatcher;
    }
    
    /**
     * @return Xi_Event_Dispatcher
     */
    public function getEventDispatcher()
    {
        return $this->_dispatcher;
    }
    
    /**
     * Attach a listener that will be triggered for valid values
     *
     * @param Xi_Event_Listener_Interface $listener
     * @return Xi_Validate_Listener
     */
    public function attachSuccessListener($listener)
    {
        $this->getEventDispatcher()->attach('success', $listener);
        return $this;
    }
    
    /**
     * Attach a listener that will be triggered for invalid values
     *
     * @param Xi_Event_Listener_Interface $listener
     * @return Xi_Validate_Listener
     */
    public function attachFailureListener($listener)
    {
        $this->getEventDispatcher()->attach('failure', $listener);
        return $this;
    }
    
    /**
     * Delegate validation to inner validator, notify event listeners of
     * validation results
     *
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        if (parent::isValid($value)) {
            $this->getEventDispatcher()->notify(new Xi_Event('success', $this, array('in' => $value, 'out' => true)));
            return true;
        }
        $this->getEventDispatcher()->notify(new Xi_Event('failure', $this, array('in' => $value, 'out' => false)));
        return false;
    }
}
