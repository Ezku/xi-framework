<?php
/**
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
interface Xi_Factory_Configurable_Interface
{
    /**
     * @param array options
     * @return void
     */
    public function __construct($options);
    
    /**
     * Retrieve arguments from options, if available
     *
     * @return null|array
     */
    public function getArgumentsFromOptions();
    
    /**
     * Retrieve an option value, or a default value if undefined
     *
     * @param string option name
     * @param null|mixed default value
     */
    public function getOption($name, $default = null);

    /**
     * Check whether option is defined
     *
     * @param string option name
     * @return boolean
     */
    public function hasOption($name);
}
?>
