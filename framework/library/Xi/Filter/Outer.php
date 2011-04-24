<?php
/**
 * Wraps a filter
 *
 * @category    Xi
 * @package     Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_Outer implements Zend_Filter_Interface, Xi_Filter_Aggregate
{
    /**
     * @var Zend_Filter_Interface
     */
    protected $_filter;

    /**
     * @param Zend_Filter_Interface inner validator
     */
    public function __construct($filter)
    {
        $this->_filter = $filter;
    }

    /**
     * @return Zend_Filter_Interface
     */
    public function getFilter()
    {
        return $this->_filter;
    }

    public function filter($value)
    {
        return $this->getFilter()->filter($value);
    }
}

