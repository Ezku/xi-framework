<?php
/**
 * Wraps an event listener and filters invocations based on whether an event
 * validates according to provided validation rules
 *
 * @category    Xi
 * @package     Xi_Event
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Event_Listener_Conditional extends Xi_Event_Listener_Outer implements Xi_Validate_Aggregate
{
    protected $_validator;

    public function __construct($listener, $validator)
    {
        parent::__construct($listener);
        $this->_validator = $validator;
    }

    public function getValidator()
    {
        return $this->_validator;
    }

    public function isValid($event)
    {
        if (isset($this->_validator)) {
            return $this->_validator->isValid($event);
        }
        return false;
    }

    public function invoke($event)
    {
        if ($this->isValid($event)) {
            return parent::invoke($event);
        }
    }
}
