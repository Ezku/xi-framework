<?php
/**
 * Uses a callback to lazily retrieve the filter only when it's needed
 *
 * @category    Xi
 * @package     Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Filter_Lazy extends Xi_Filter_Outer
{
    protected $_callback;

    public function __construct($callback)
    {
        $this->_callback = $callback;
    }

    public function getFilter()
    {
        if (null === $this->_filter) {
            $this->_filter = call_user_func($this->_callback);
        }
        return $this->_filter;
    }
}
