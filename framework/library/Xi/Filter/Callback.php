<?php
/**
 * Defers filtering to a callback function
 *
 * @category    Xi
 * @package     Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Filter_Callback implements Zend_Filter_Interface
{
    protected $_callback;

    public function __construct($callback)
    {
        $this->_callback = $callback;
    }

    public function filter($value)
    {
        return call_user_func($this->_callback, $value);
    }
}
