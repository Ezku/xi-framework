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
