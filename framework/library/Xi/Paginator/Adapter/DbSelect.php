<?php
/**
 * @category    Xi
 * @package     Xi_Paginator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Paginator_Adapter_DbSelect implements Xi_Paginator_Adapter_Interface
{
    /**
     * @var Zend_Db_Select
     */
    protected $_select;
    
    /**
     * Zend_Db_Statement fetch mode
     * 
     * @var string
     */
    protected $_fetchMode;
    
    /**
     * @var int
     */
    protected $_count;
    
    /**
     * @param Zend_Db_Select $select
     * @param string $fetchMode
     */
    public function __construct(Zend_Db_Select $select, $fetchMode = null)
    {
        $this->_select = $select;
        $this->_fetchMode = $fetchMode;
    }
    
    /**
     * Get the number of items available
     *
     * @return int|false
     */
    public function getCount()
    {
        if (null === $this->_count) {
            $countSelect = clone $this->_select;
            $countSelect->reset()->from($this->_select, new Zend_Db_Expr('COUNT(*) AS count'));
            $this->_count = (int) $countSelect->query()->fetchColumn();
        }
        return $this->_count;
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
        $select = clone $this->_select;
        $select->limitPage($page + 1, $itemsPerPage);
        return $select->query($this->_fetchMode)->fetchAll();
    }
}
