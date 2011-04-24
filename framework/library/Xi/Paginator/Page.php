<?php
/**
 * @category    Xi
 * @package     Xi_Paginator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Paginator_Page
{
    /**
     * @var Xi_Paginator_Page_Collection
     */
    protected $_pages;
    
    /**
     * @var int
     */
    protected $_pageNumber;
    
    /**
     * @param Xi_Paginator_Page_Collection $pages
     * @param int $pageNumber
     * @param string name
     */
    public function __construct($pages, $pageNumber, $name)
    {
        $this->_pages = $pages;
        $this->_pageNumber = $pageNumber;
        $this->_name = $name;
    }
    
    /**
     * Get page name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
    
    /**
     * Get page number
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->_pageNumber;
    }
    
    /**
     * @return boolean
     */
    public function isFirstPage()
    {
        return $this === $this->_pages->getFirstPage();
    }
    
    /**
     * @return boolean
     */
    public function isLastPage()
    {
        return $this === $this->_pages->getLastPage();
    }
    
    /**
     * @return boolean
     */
    public function isFirstPageInRange()
    {
        return $this === $this->_pages->getFirstPageInRange();
    }
    
    /**
     * @return boolean
     */
    public function isLastPageInRange()
    {
        return $this === $this->_pages->getLastPageInRange();
    }
    
    /**
     * Convert to string
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->_name;
    }
}
