<?php
/**
 * @category    Xi
 * @package     Xi_Filter
 * @subpackage  Xi_Filter_Inflector
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_Inflector_Recursive extends Xi_Filter_Outer
{
    /**
     * Maximum amount of times to inflect a string in {@link inflect()}. An
     * exception will be thrown if this is passed.
     *
     * @var int
     */
    protected $_maximumInflectionLevel = 7;
    
    /**
     * @param array|Zend_Config $rules
     * @return void
     */
    public function __construct($rules = array())
    {
        $inflector = new Xi_Filter_Inflector;
        $inflector->setThrowTargetExceptionsOn(false);
        
        if ($rules instanceof Zend_Config) {
            $inflector->setConfig($rules);
        } elseif (!empty($rules)) {
            $inflector->addRules($rules);
        }
        
        parent::__construct($inflector);
    }
    
    public function __clone()
    {
        $this->_filter = clone $this->_filter;
    }
    
    /**
     * @return Xi_Filter_Inflector
     */
    public function getInflector()
    {
        return $this->_filter;
    }

    /**
     * Apply recursive inflection to a string.
     *
     * @param string|array
     * @param array
     * @return string
     * @throws Xi_Filter_Exception if maximum inflection level is reached
     */
    public function filter($string, $params = array())
    {
        $inflector  = $this->getFilter();
        $identifier = $inflector->getTargetReplacementIdentifier();
        $level      = $this->_maximumInflectionLevel;
        $spec       = $string;
        while (--$level) {
            /**
             * Get inflected value
             */
            $inflector->setTarget($spec);
            $value = $inflector->filter((array) $params);

            /**
             * If the inflector did not change the value or if there are no
             * replacement identifiers left, return the current value.
             */
            if (($value == $spec) || (false === strpos($value, $identifier))) {
                return $value;
            }

            /**
             * Otherwise, inflect again.
             */
            $spec = $value;
        }

        throw new Xi_Filter_Exception('Maximum inflection level reached. Circular dependency? Request: '.$string.' Result: '.$spec);
    }
    
    /**
     * Redirect method calls to inflector
     * 
     * @param string
     * @param array
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->getFilter(), $method), $args);
    }
}
