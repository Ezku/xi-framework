<?php
/**
 * Uses a callback to lazily retrieve the validator only when it's needed
 *
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Validate_Lazy extends Xi_Validate_Outer
{
    protected $_callback;

    public function __construct($callback)
    {
        $this->_callback = $callback;
    }

    public function getValidator()
    {
        if (null === $this->_validator) {
            $this->_validator = call_user_func($this->_callback);
        }
        return $this->_validator;
    }
}
