<?php
/**
 * @category    Xi
 * @package     Xi_Paginator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
interface Xi_Paginator_Range_Interface
{
    /**
     * Set first page
     *
     * @param int $firstPage
     * @return Xi_Paginator_Range_Interface
     */
    public function setFirstPage($firstPage);
    
    /**
     * Get first page
     *
     * @return int
     */
    public function getFirstPage();
    
    /**
     * Set last page
     *
     * @param int $lastPage
     * @return Xi_Paginator_Range_Interface
     */
    public function setLastPage($lastPage);
    
    /**
     * Get last page
     *
     * @return int
     */
    public function getLastPage();
    
    /**
     * Get the amount of pages between the first and last pages (inclusive)
     *
     * @return int
     */
    public function getPageAmount();
    
    /**
     * Set amount of pages between the first and last page. Optionally set the
     * amount starting from the last page instead of the first page.
     * 
     * @param int $pageAmount
     * @param boolean $setFirstPage
     * @return Xi_Paginator_Range_Interface
     */
    public function setPageAmount($pageAmount, $setFirstPage = false);
    
    /**
     * Set current page
     *
     * @param int $page
     * @return Xi_Paginator_Range_Interface
     */
    public function setCurrentPage($page);
    
    /**
     * Get current page
     *
     * @return int
     */
    public function getCurrentPage();
    
    /**
     * Get offset from first page to current page. Optionally get the
     * offset from the last page instead.
     * 
     * @return int
     */
    public function getCurrentPageOffset($useLastPage = false);
    
    /**
     * Set offset from first page to current page. Optionally set the
     * offset from the last page instead.
     *
     * @param int $offset
     * @param boolean $useLastPage
     * @return Xi_Paginator_Range_Interface
     */
    public function setCurrentPageOffset($offset, $useLastPage = false);
    
    /**
     * Set page range
     *
     * @param int $range
     * @return Xi_Paginator_Range_Interface
     */
    public function setPageRange($range);
    
    /**
     * Get page range
     *
     * @return int
     */
    public function getPageRange();
    
    /**
     * Retrieve first page in range
     *
     * @return int
     */
    public function getFirstPageInRange();
    
    /**
     * Retrieve last page in range
     *
     * @return int
     */
    public function getLastPageInRange();
    
    /**
     * Retrieve first and last page in range
     *
     * @return array
     */
    public function getBounds();
}
