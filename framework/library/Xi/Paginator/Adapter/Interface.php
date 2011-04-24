<?php
/**
 * @category    Xi
 * @package     Xi_Paginator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
interface Xi_Paginator_Adapter_Interface
{
    /**
     * Get total amount of items
     *
     * @return int
     */
    public function getCount();
    
    /**
     * Get items for page
     *
     * @param int $page counting from 0
     * @param int $itemsPerPage
     * @return Iterator
     */
    public function getItems($page, $itemsPerPage);
}
