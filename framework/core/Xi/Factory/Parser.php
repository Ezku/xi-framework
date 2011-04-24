<?php
/**
 * Parse factory definitions from configuration data
 *
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Factory_Parser implements Zend_Filter_Interface 
{
    protected $_parsers = array(
        'Xi_Factory_Parser_Factory',
        'Xi_Factory_Parser_Class',
        'Xi_Factory_Parser_Callback',
        'Xi_Factory_Parser_Reference',
        'Xi_Factory_Parser_Registry'
    );

    /**
     * @param array $parsers
     */
    public function __construct($parsers = array())
    {
        $this->setParsers(array_merge($this->_parsers, $parsers));
    }

    /**
     * @param mixed configuration data
     * @return mixed|array
     */
    public function filter($config)
    {
        if (!(is_array($config) || $config instanceof Iterator || $config instanceof IteratorAggregate)) {
            return $config;
        }

        /**
         * Parse branches
         */
        $parsed = array();
        foreach ($config as $key => $value) {
            /**
             * Leave scalar values untouched
             */
            if (!is_scalar($value)) {
                $value = $this->filter($value);
            }

            $parsed[$key] = $value;
        }

        /**
         * Parse current node
         */
        foreach ($this->_parsers as $parser) {
            if ($parser->isValidConfig($parsed)) {
                return $parser->fromConfig($parsed);
            }
        }
        return $parsed;
    }


    /**
     * Add a parser to the list of factory parser classes to be used
     *
     * @param string factory parser class name or instance
     * @return void
     * @see Xi_Factory_Parser_Abstract
     */
    public function addParser($parser)
    {
        if (is_string($parser)) {
            $parser = new $parser;
        }
        $this->_parsers[] = $parser;
    }

    /**
     * Set factory parsers
     *
     * @param array instances or class names
     * @return Xi_Factory_Parser
     */
    public function setParsers($parsers)
    {
        foreach ($parsers as &$parser) {
            if (is_string($parser)) {
                $parser = new $parser;
            }
        }
        $this->_parsers = $parsers;
    }

    /**
     * Get factory parser instances
     *
     * @return array
     */
    public function getParsers()
    {
        return $this->_parsers;
    }
}


