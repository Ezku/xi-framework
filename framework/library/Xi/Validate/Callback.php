<?php
/**
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Validate_Callback extends Xi_Validate_Abstract
{
    /**
     * @var callback
     */
    protected $_callback;
    
    /**
     * @param callback $callback
     */
    public function __construct($callback)
    {
        $this->_callback = $callback;
    }
    
    /**
     * Validate value using callback
     *
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        return (boolean) call_user_func($this->getCallback(), $value);
    }
}
