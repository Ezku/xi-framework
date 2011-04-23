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
class Xi_Paginator_Page_Collection_Iterator implements SeekableIterator, Countable
{
    /**
     * @var Xi_Paginator_Page_Collection
     */
    protected $_pages;
    
    /**
     * @var int
     */
    protected $_firstPage;
    
    /**
     * @var int
     */
    protected $_lastPage;
    
    /**
     * @var int
     */
    protected $_currentPage;
    
    /**
     * @param Xi_Paginator_Page_Collection $pages
     */
    public function __construct(Xi_Paginator_Page_Collection $pages, $firstPageInRange, $lastPageInRange)
    {
        $this->_pages = $pages;
        $this->_firstPage = $firstPageInRange;
        $this->_lastPage = $lastPageInRange;
    }
    
    /**
     * Rewind pointer
     *
     * @return void
     */
    public function rewind()
    {
        $this->_currentPage = $this->_firstPage;
    }
    
    /**
     * Check if current page is valid
     *
     * @return boolean
     */
    public function valid()
    {
        return ($this->_firstPage <= $this->_currentPage) && ($this->_currentPage <= $this->_lastPage);
    }
    
    /**
     * Get current page number
     *
     * @return int
     */
    public function key()
    {
        return $this->_currentPage;
    }
    
    /**
     * Get current page
     *
     * @return Xi_Paginator_Page
     */
    public function current()
    {
        return $this->_pages->getPage($this->_currentPage);
    }
    
    /**
     * Advance pointer
     *
     * @return void
     */
    public function next()
    {
        $this->_currentPage++;
    }
    
    /**
     * Seek to page number
     *
     * @param int $page
     * @throws Xi_Paginator_Exception if page number is out of bounds
     */
    public function seek($page)
    {
        if (($this->_firstPage <= $page) && ($page <= $this->_lastPage)) {
            $this->_currentPage = $page;
        }
        $error = sprintf("Page number %d out of bounds (min %d, max %d)", $page, $this->_firstPage, $this->_lastPage);
        throw new Xi_Paginator_Exception($error);
    }
    
    /**
     * Count number of pages available or false if no pages
     *
     * @return int
     */
    public function count()
    {
        $count = $this->_lastPage - $this->_firstPage + 1;
        if ($count > 0) {
            return $count;
        }
        return false;
    }
}
