<?php
/**
 * An outer iterator that passes all values through a Zend_Filter_Interface
 * object
 * 
 * @category    Xi
 * @package     Xi_Iterator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Iterator_Filter extends IteratorIterator implements Xi_Filter_Aggregate 
{
    /**
     * @var Zend_Filter_Interface
     */
    protected $_filter;
    
    /**
     * @param Iterator $it
     * @param Zend_Filter_Interface $filter
     */
    public function __construct(Iterator $it, Zend_Filter_Interface $filter)
    {
        parent::__construct($it);
        $this->_filter = $filter;
    }
    
    /**
     * @return Zend_Filter_Interface
     */
    public function getFilter()
    {
        return $this->_filter;
    }
    
    /**
     * Filter current value before returning it
     *
     * @return mixed
     */
    public function current()
    {
        return $this->_filter->filter(parent::current());
    }
}
