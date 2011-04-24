<?php
/**
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
abstract class Xi_Factory_Abstract implements Xi_Factory_Interface
{
    /**
     * Modify the default behaviour of {@link mapCreationArguments()}: if
     * enabled, arguments will be passed as a single array in the first
     * parameter; otherwise the arguments will be expanded into parameters
     *
     * @var boolean
     */
    protected $_mapArgumentsToArray = false;

    public function get($args = null)
    {
        $args = $this->getArguments($args);
        $args = $this->mapCreationArguments($args);
        return call_user_func_array(array($this, 'create'), $args);
    }
    
    /**
     * Provided the $args passed to {@link get()}, retrieve an array of arguments to use.
     *
     * @param null|array $args
     * @return array
     */
    public function getArguments($args)
    {
        if (null === $args) {
            $args = $this->getDefaultArguments();
        }
        return $args;
    }

    /**
     * Get default creation arguments. Used if there are no arguments available.
     *
     * @return mixed
     */
    public function getDefaultArguments()
    {
        return array();
    }

    /**
     * Maps $args to arguments to {@link create()}.
     *
     * @param null|array
     * @return mixed
     */
    public function mapCreationArguments($args)
    {
        if ($this->_mapArgumentsToArray) {
            return array($args);
        }
        return $args;
    }
}

