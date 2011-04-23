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
 * An outer iterator that passes all values through a Zend_Filter_Interface
 * object
 * 
 * @category    Xi
 * @package     Xi_Iterator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Iterator_Filter extends IteratorIterator implements Xi_Filter_Aggregate 
{
    /**
     * @var Zend_Filter_Interface
     */
    protected $_filter;
    
    /**
     * @param Iterator $it
     * @param Zend_Filter_Interface $filter
     */
    public function __construct(Iterator $it, Zend_Filter_Interface $filter)
    {
        parent::__construct($it);
        $this->_filter = $filter;
    }
    
    /**
     * @return Zend_Filter_Interface
     */
    public function getFilter()
    {
        return $this->_filter;
    }
    
    /**
     * Filter current value before returning it
     *
     * @return mixed
     */
    public function current()
    {
        return $this->_filter->filter(parent::current());
    }
}
