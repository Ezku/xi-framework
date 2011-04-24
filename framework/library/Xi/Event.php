<?php
/**
 * @category    Xi
 * @package     Xi_Event
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Event
{
    /**
     * @var string
     */
    protected $_name;

    /**
     * @var mixed
     */
    protected $_context;

    /**
     * @var Xi_Array
     */
    protected $_params;

    /**
     * @var mixed
     */
    protected $_returnValue;

    /**
     * @var boolean
     */
    protected $_cancelled = false;

    /**
     * @param string name
     * @param null|
     */
    public function __construct($name, $context = null, $params = array())
    {
        $this->_name = $name;
        $this->_context = $context;
        $this->_params = new Xi_Array($params);
    }

    /**
     * @return string name
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Check whether context is set
     *
     * @return boolean
     */
    public function hasContext()
    {
        return null !== $this->_context;
    }

    /**
     * Get context
     *
     * @return mixed
     */
    public function getContext()
    {
        return $this->_context;
    }

    /**
     * Check whether there are any parameters
     *
     * @return boolean
     */
    public function hasParams()
    {
        return 0 !== count($this->_params);
    }

    /**
     * Retrieve parameters
     *
     * @return Xi_Array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Cancel event
     *
     * @return Xi_Event
     */
    public function cancel()
    {
        $this->_cancelled = true;
        return $this;
    }

    /**
     * Is the event cancelled?
     *
     * @return boolean
     */
    public function isCancelled()
    {
        return $this->_cancelled;
    }
    
    /**
     * Check whether return value is set
     *
     * @return boolean
     */
    public function hasReturnValue()
    {
        return isset($this->_returnValue);
    }

    /**
     * @return mixed
     */
    public function getReturnValue()
    {
        return $this->_returnValue;
    }

    /**
     * @param mixed
     * @return Xi_Event
     */
    public function setReturnValue($value)
    {
        $this->_returnValue = $value;
        return $this;
    }

    /**
     * Property read access redirected to {@link $_params}.
     *
     * @param string
     * @return mixed
     */
    public function __get($name)
    {
        return $this->_params->$name;
    }

    /**
     * Property read access redirected to {@link $_params}.
     *
     * @param string
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->_params->$name);
    }
}
