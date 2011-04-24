<?php
/**
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Factory_Configurable extends Xi_Factory_Abstract implements Xi_Factory_Configurable_Interface
{
    /**
     * @var array
     */
    protected $_options;

    /**
     * @var string
     */
    protected $_argumentOptionKey = 'args';

    /**
     * Whether to retrieve arguments from options if no arguments are provided
     * in the second parameter of {@link get()}.
     *
     * @var boolean
     */
    protected $_enableArgumentsFromOptions = true;

    /**
     * @param array options
     * @return void
     */
    public function __construct($options)
    {
        $this->_options = $options;
        $this->init();
    }

    /**
     * Template method called on construction
     *
     * @return void
     */
    public function init()
    {}
    
    public function getArguments($args)
    {
        if (null === $args) {
            if ($this->_enableArgumentsFromOptions) {
                $args = $this->getArgumentsFromOptions();
            }
            $args = parent::getArguments($args);
        }

        return $args;
    }
    
    public function getArgumentsFromOptions()
    {
        $key = $this->_argumentOptionKey;
        if (isset($this->_options[$key])) {
            return (array) $this->_options[$key];
        }
    }

    public function getOption($name, $default = null)
    {
        if (isset($this->_options[$name])) {
            return $this->_options[$name];
        }
        return $default;
    }
    
    public function hasOption($name)
    {
        return isset($this->_options[$name]);
    }
}