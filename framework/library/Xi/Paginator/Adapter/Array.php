<?php
/**
 * @category    Xi
 * @package     Xi_Paginator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Paginate_Adapter_Array implements Xi_Paginator_Adapter_Interface
{
    /**
     * 
     * @var array
     */
    protected $_array;
    
    /**
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->_array = $array;
    }
    
    /**
     * Get the number of items available
     *
     * @return int
     */
    public function getCount()
    {
        return count($this->_array);
    }
    
    /**
     * Get items for page
     *
     * @param int $page counting from 0
     * @param int $itemsPerPage
     * @return Iterator
     */
    public function getItems($page, $itemsPerPage)
    {
        $offset = $page * $itemsPerPage;
        $limit  = $itemsPerPage;
        return new ArrayIterator(array_slice($this->_array, $offset, $limit, true));
    }
}
