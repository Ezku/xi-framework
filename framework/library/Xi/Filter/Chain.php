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
 * @package     Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_Chain implements Zend_Filter_Interface
{
    /**
     * @var array Zend_Filter_Interface
     */
    protected $_filters = array();

    /**
     * @param array Zend_Filter_Interface
     */
    public function __construct(array $filters = array())
    {
        $this->_filters = $filters;
    }

    /**
     * @param Zend_Filter_interface
     * @param null|boolean prepend instead of appending to filter chain
     */
    public function addFilter($filter, $prepend = false)
    {
        if ($prepend) {
            array_unshift($this->_filters, $filter);
        } else {
            $this->_filters[] = $filter;
        }
        return $this;
    }

    /**
     * @return array Zend_Filter_Interface
     */
    public function getFilters()
    {
        return $this->_filters;
    }

    public function filter($value)
    {
        foreach ($this->_filters as $filter) {
            $value = $filter->filter($value);
        }
        return $value;
    }
}
