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
