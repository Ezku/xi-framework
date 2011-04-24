<?php
/**
 * @category    Xi
 * @package     Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_Chain implements Zend_Filter_Interface
{
    /**
     * @var array Zend_Filter_Interface
     */
    protected $_filters = array();

    /**
     * @param array Zend_Filter_Interface
     */
    public function __construct(array $filters = array())
    {
        $this->_filters = $filters;
    }

    /**
     * @param Zend_Filter_interface
     * @param null|boolean prepend instead of appending to filter chain
     */
    public function addFilter($filter, $prepend = false)
    {
        if ($prepend) {
            array_unshift($this->_filters, $filter);
        } else {
            $this->_filters[] = $filter;
        }
        return $this;
    }

    /**
     * @return array Zend_Filter_Interface
     */
    public function getFilters()
    {
        return $this->_filters;
    }

    public function filter($value)
    {
        foreach ($this->_filters as $filter) {
            $value = $filter->filter($value);
        }
        return $value;
    }
}
