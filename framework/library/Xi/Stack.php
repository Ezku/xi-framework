<?php
/**
 * @category    Xi
 * @package     Xi_Stack
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Stack implements Countable, IteratorAggregate
{
    /**
     * @var array
     */
    protected $_stack = array();
    
    /**
     * @param array
     * @return void
     */
    public function __construct($items = array())
    {
        $this->_stack = array_values($items);
    }
    
    /**
     * Inspect topmost value in the stack
     * 
     * @return mixed
     */
    public function peek()
    {
        return end($this->_stack);
    }
    
    /**
     * Push a value to the top of the stack
     * 
     * @param mixed value
     * @return Xi_Stack
     */
    public function push($value)
    {
        array_push($this->_stack, $value);
        return $this;
    }
    
    /**
     * Remove topmost value from the stack and return it
     * 
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->_stack);
    }
    
    /**
     * @return Iterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->_stack);
    }
    
    /**
     * @return int
     */
    public function count()
    {
        return count($this->_stack);
    }
}
