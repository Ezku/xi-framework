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
abstract class Xi_Paginator_Range_Abstract implements Xi_Paginator_Range_Interface
{
    /**
     * @var int
     */
    protected $_firstPage = 1;
    
    /**
     * @var int
     */
    protected $_lastPage = 1;
    
    /**
     * @var int
     */
    protected $_currentPage = 1;
    
    /**
     * @var int
     */
    protected $_range = 5;
    
    /**
     * @var array
     */
    protected $_bounds = array();
    
    /**
     * @param int $firstPage
     * @param int $lastPage
     * @param int $currentPage
     * @param int $range
     */
    public function __construct($firstPage = null, $lastPage = null, $currentPage = null, $range = null)
    {
        if (null !== $firstPage) {
            $this->setFirstPage($firstPage);
        }
        if (null !== $lastPage) {
            $this->setLastPage($lastPage);
        }
        if (null !== $currentPage) {
            $this->setCurrentPage($currentPage);
        }
        if (null !== $range) {
            $this->setPageRange($range);
        }
    }
    
    /**
     * Set first page
     *
     * @param int $firstPage
     * @return Xi_Paginator_Range_Abstract
     */
    public function setFirstPage($firstPage)
    {
        $this->_firstPage = (int) $firstPage;
        return $this;
    }
    
    /**
     * Get first page
     *
     * @return int
     */
    public function getFirstPage()
    {
        return $this->_firstPage;
    }
    
    /**
     * Set last page
     *
     * @param int $lastPage
     * @return Xi_Paginator_Range_Abstract
     */
    public function setLastPage($lastPage)
    {
        $this->_lastPage = (int) $lastPage;
        return $this;
    }
    
    /**
     * Get last page
     *
     * @return int
     */
    public function getLastPage()
    {
        return $this->_lastPage;
    }
    
    /**
     * Get the amount of pages between the first and last pages (inclusive)
     *
     * @return int
     */
    public function getPageAmount()
    {
        return $this->getLastPage() - $this->getFirstPage() + 1;
    }
    
    /**
     * Set amount of pages between the first and last page. Optionally set the
     * amount starting from the last page instead of the first page.
     * 
     * @param int $pageAmount
     * @param boolean $setFirstPage
     * @return Xi_Paginator_Range_Abstract
     */
    public function setPageAmount($pageAmount, $setFirstPage = false)
    {
        if ($setFirstPage) {
            $this->setFirstPage($this->getLastPage() - $pageAmount + 1);
        } else {
            $this->setLastPage($this->getFirstPage() + $pageAmount - 1);
        }
        return $this;
    }
    
    /**
     * Set current page
     *
     * @param int $page
     * @return Xi_Paginator_Range_Abstract
     */
    public function setCurrentPage($page)
    {
        if ($page < ($firstPage = $this->getFirstPage())) {
            $page = $firstPage;
        } elseif (($lastPage = $this->getLastPage()) < $page) {
            $page = $lastPage;
        }
        $this->_currentPage = (int) $page;
        return $this;
    }
    
    /**
     * Get current page
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->_currentPage;
    }
    
    /**
     * Get offset from first page. Optionally get the offset from the last
     * page instead.
     *
     * @param int $pageNumber
     * @param boolean $useLastPage
     * @return int
     */
    public function getPageOffset($pageNumber, $useLastPage)
    {
        if ($useLastPage) {
            return $this->getLastPage() - $pageNumber;
        } else {
            return $pageNumber - $this->getFirstPage();
        }
    }
    
    /**
     * Get offset from first page to current page. Optionally get the
     * offset from the last page instead.
     * 
     * @return int
     */
    public function getCurrentPageOffset($useLastPage = false)
    {
        return $this->getPageOffset($this->getCurrentPage(), $useLastPage);
    }
    
    /**
     * Set offset from first page to current page. Optionally set the
     * offset from the last page instead.
     *
     * @param int $offset
     * @param boolean $useLastPage
     * @return Xi_Paginator_Range_Abstract
     */
    public function setCurrentPageOffset($offset, $useLastPage = false)
    {
        if ($useLastPage) {
            $this->setCurrentPage($this->getLastPage() - $offset);
        } else {
            $this->setCurrentPage($this->getFirstPage() + $offset);
        }
        return $this;
    }
    
    /**
     * Set page range
     *
     * @param int $range
     * @return Xi_Paginator_Range_Abstract
     */
    public function setPageRange($range)
    {
        $this->_range = $range;
        return $this;
    }
    
    /**
     * Get page range
     *
     * @return int
     */
    public function getPageRange()
    {
        return $this->_range;
    }
    
    /**
     * Retrieve first page in range
     *
     * @return int
     */
    public function getFirstPageInRange()
    {
        list($firstPage) = $this->getBounds();
        return $firstPage;
    }
    
    /**
     * Retrieve last page in range
     *
     * @return int
     */
    public function getLastPageInRange()
    {
        list(, $lastPage) = $this->getBounds();
        return $lastPage;
    }
    
    /**
     * Retrieve first and last page in range
     *
     * @return array
     */
    public function getBounds()
    {
        $key = $this->_getKey();
        if (!isset($this->_bounds[$key])) {
            $this->_bounds[$key] = $this->_calculateBounds();
        }
        return $this->_bounds[$key];
    }
    
    /**
     * Get hash key for current values
     *
     * @return string
     */
    protected function _getKey()
    {
        return sprintf("%d-%d-%d", $this->_firstPage, $this->_lastPage, $this->_range);
    }
    
    /**
     * Calculate first and last page in range
     *
     * @return array
     */
    abstract protected function _calculateBounds();
}