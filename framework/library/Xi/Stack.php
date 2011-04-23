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
 * @package     Xi_Stack
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Stack implements Countable, IteratorAggregate
{
    /**
     * @var array
     */
    protected $_stack = array();
    
    /**
     * @param array
     * @return void
     */
    public function __construct($items = array())
    {
        $this->_stack = array_values($items);
    }
    
    /**
     * Inspect topmost value in the stack
     * 
     * @return mixed
     */
    public function peek()
    {
        return end($this->_stack);
    }
    
    /**
     * Push a value to the top of the stack
     * 
     * @param mixed value
     * @return Xi_Stack
     */
    public function push($value)
    {
        array_push($this->_stack, $value);
        return $this;
    }
    
    /**
     * Remove topmost value from the stack and return it
     * 
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->_stack);
    }
    
    /**
     * @return Iterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->_stack);
    }
    
    /**
     * @return int
     */
    public function count()
    {
        return count($this->_stack);
    }
}
