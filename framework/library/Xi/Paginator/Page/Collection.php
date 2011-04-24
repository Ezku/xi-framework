<?php
/**
 * @category    Xi
 * @package     Xi_Paginator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Paginator_Page_Collection implements IteratorAggregate, Countable
{
    /**
     * @var Xi_Paginator_Range_Interface
     */
    protected $_range;
    
    /**
     * @var int
     */
    protected $_itemCount;
    
    /**
     * @var int
     */
    protected $_itemsPerPage;
    
    /**
     * @param Xi_Paginator_Range_Interface $range
     * @param int $itemCount
     * @param int $itemsPerPage
     */
    public function __construct(Xi_Paginator_Range_Interface $range, $itemCount, $itemsPerPage)
    {
        $this->_range = $range;
        $this->_itemCount = $itemCount;
        $this->_itemsPerPage = $itemsPerPage;
    }
    
    /**
     * @return Xi_Paginator_Page
     */
    public function getFirstPage()
    {
        return $this->getPage($this->_range->getFirstPage());
    }
    
    /**
     * @return Xi_Paginator_Page
     */
    public function getLastPage()
    {
        return $this->getPage($this->_range->getLastPage());
    }
    
    /**
     * @return Xi_Paginator_Page
     */
    public function getFirstPageInRange()
    {
        return $this->getPage($this->_range->getFirstPageInRange());
    }
    
    /**
     * @return Xi_Paginator_Page
     */
    public function getLastPageInRange()
    {
        return $this->getPage($this->_range->getLastPageInRange());
    }
    
    /**
     * @return boolean
     */
    public function hasPreviousPage()
    {
        return $this->_range->getCurrentPage() > $this->_range->getFirstPage();
    }
    
    /**
     * @return Xi_Paginator_Page
     * @throws Xi_Paginator_Exception
     */
    public function getPreviousPage()
    {
        $currentPage = $this->_range->getCurrentPage();
        if (!$this->hasPreviousPage()) {
            $error = sprintf("There is no previous page for page %d", $currentPage);
            throw new Xi_Paginator_Exception($error);
        }
        return $this->getPage($currentPage - 1, "previous");
    }
    
    /**
     * @return boolean
     */
    public function hasNextPage()
    {
        return $this->_range->getCurrentPage() < $this->_range->getLastPage();
    }
    
    /**
     * @return Xi_Paginator_Page
     * @throws Xi_Paginator_Exception
     */
    public function getNextPage()
    {
        $currentPage = $this->_range->getCurrentPage();
        if (!$this->hasNextPage()) {
            $error = sprintf("There is no next page for page %d", $currentPage);
            throw new Xi_Paginator_Exception($error);
        }
        return $this->getPage($currentPage + 1, "next");
    }
    
    /**
     * @param int $pageNumber
     * @param string $name
     * @return Xi_Paginator_Page
     */
    public function getPage($pageNumber, $name = null)
    {
        $key = $pageNumber . $name;
        if (!isset($this->_pages[$key])) {
            if (!$this->hasPage($pageNumber)) {
                $error = sprintf("Page number %d not available", $pageNumber);
                throw new Xi_Paginator_Exception($error);
            }
            $this->_pages[$key] = $this->_createPage($pageNumber, $name);
        }
        return $this->_pages[$key];
    }
    
    /**
     * @param int $pageNumber
     * @return boolean
     */
    public function hasPage($pageNumber)
    {
        return ($this->_range->getFirstPage() <= $pageNumber) && ($pageNumber <= $this->_range->getLastPage());
    }
    
    /**
     * @param int $pageNumber
     * @param string $name
     * @return Xi_Paginator_Page
     */
    public function _createPage($pageNumber, $name = null)
    {
        if (null === $name) {
            $name = $pageNumber;
        }
        return new Xi_Paginator_Page($this, $pageNumber, $name);
    }
    
    /**
     * @return Xi_Paginator_Range_Interface
     */
    public function getRange()
    {
        return $this->_range;
    }
    
    /**
     * Convert to array
     *
     * @return array
     */
    public function toArray()
    {
        $array = array();
        foreach ($this as $key => $value) {
            $array[$key] = $value;
        }
        return $array;
    }
    
    /**
     * Retrieve iterator for contents
     *
     * @return Iterator
     */
    public function getIterator()
    {
        return new Xi_Paginator_Page_Collection_Iterator($this, $this->_range->getFirstPageInRange(), $this->_range->getLastPageInRange());
    }
    
    /**
     * Count number of pages available or false if no pages
     *
     * @return int|false
     */
    public function count()
    {
        $count = $this->_range->getLastPageInRange() - $this->_range->getFirstPageInRange() + 1;
        if ($count > 0) {
            return $count;
        }
        return false;
    }
}
