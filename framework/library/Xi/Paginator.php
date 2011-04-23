<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.xi-framework.com>.
 */

/**
 * @category    Xi
 * @package     Xi_Paginator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Paginator
{
    /**
     * @var Xi_Paginator_Range_Interface
     */
    protected $_range;
    
    /**
     * @var Xi_Paginator_Adapter_Interface
     */
    protected $_adapter;
    
    /**
     * @var int
     */
    protected $_itemCount;
    
    /**
     * @var int
     */
    protected $_itemsPerPage = 30;
    
    /**
     * @var int
     */
    protected $_pageCount;
    
    /**
     * @param Xi_Paginator_Adapter_Interface $adapter
     * @param Xi_Paginator_Range_Interface $range
     */
    public function __construct(Xi_Paginator_Adapter_Interface $adapter, Xi_Paginator_Range_Interface $range = null)
    {
        $this->_range = $range;
        $this->setAdapter($adapter);
    }
    
    /**
     * Set adapter
     *
     * @param Xi_Paginator_Adapter_Interface $adapter
     * @return Xi_Paginator
     */
    public function setAdapter(Xi_Paginator_Adapter_Interface $adapter)
    {
        $this->_adapter = $adapter;
        $this->_itemCount = $adapter->getCount();
        $this->getRange()->setPageAmount($this->getPageCount());
        return $this;
    }
    
    /**
     * Retrieve adapter
     *
     * @return Xi_Paginator_Adapter_Interface
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }
    
    /**
     * Set range
     *
     * @param Xi_Paginator_Range_Interface $range
     * @return Xi_Paginator
     */
    public function setRange(Xi_Paginator_Range_Interface $range)
    {
        $this->_range = $range;
        $range->setPageAmount($this->getPageCount());
        return $this;
    }
    
    /**
     * Retrieve range
     * 
     * @return Xi_Paginator_Range_Interface
     */
    public function getRange()
    {
        if (null === $this->_range) {
            $this->_range = $this->getDefaultRange();
        }
        return $this->_range;
    }
    
    /**
     * Retrieve default range
     *
     * @return Xi_Paginator_Range_Interface
     */
    public function getDefaultRange()
    {
        return new Xi_Paginator_Range_Sliding;
    }
    
    /**
     * Set items per page. Recalculates the page amount.
     * 
     * @param int $itemsPerPage
     * @return Xi_Paginator
     * @throws Xi_Paginator_Exception
     */
    public function setItemsPerPage($itemsPerPage)
    {
        if (!is_int($itemsPerPage) || ($itemsPerPage < 1)) {
            $error = sprintf("Expected an integer greater than zero, %d given", $itemsPerPage);
            throw new Xi_Paginator_Exception($error);
        }
        $this->_itemsPerPage = $itemsPerPage;
        $this->_pageCount = null;
        $this->getRange()->setPageAmount($this->getPageCount());
        return $this;
    }
    
    /**
     * @return int
     */
    public function getItemsPerPage()
    {
        return $this->_itemsPerPage;
    }
    
    /**
     * @return int
     */
    public function getPageCount()
    {
        if (null == $this->_pageCount) {
            $items = $this->getItemCount();
            $itemsPerPage = $this->getItemsPerPage();
            $this->_pageCount = ceil($items/$itemsPerPage);
        }
        return $this->_pageCount;
    }
    
    /**
     * @return int
     */
    public function getItemCount()
    {
        return $this->_itemCount;
    }
    
    /**
     * Set current page by item offset
     *
     * @param int $offset
     * @return Xi_Paginator
     */
    public function setItemOffset($offset)
    {
        $offset = (int) ($offset / $this->getItemsPerPage());
        $this->getRange()->setCurrentPageOffset($offset);
        return $this;
    }
    
    /**
     * Get item offset for current page
     *
     * @return int
     */
    public function getItemOffset()
    {
        return $this->getRange()->getCurrentPageOffset() * $this->getItemsPerPage();
    }
    
    /**
     * Get items for current page
     *
     * @return Iterator
     */
    public function getItems()
    {
        return $this->getAdapter()->getItems($this->getRange()->getCurrentPageOffset(), $this->getItemsPerPage());
    }
    
    /**
     * Get page collection for current range
     *
     * @return Xi_Paginator_Page_Collection
     */
    public function getPages()
    {
        return new Xi_Paginator_Page_Collection($this->getRange(), $this->getItemCount(), $this->getItemsPerPage());
    }
    
    /**
     * Redirect method calls to {@link getRange()}.
     *
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        $range = $this->getRange();
        $value = call_user_func_array(array($range, $method), $args);
        if ($value === $range) {
            return $this;
        }
        return $value;
    }
}