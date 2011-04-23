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
 * A FilterIterator that accepts or rejects values based on whether they are
 * valid according to a Zend_Validate_Interface object
 * 
 * @category    Xi
 * @package     Xi_Iterator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Iterator_Validate extends FilterIterator implements Xi_Validate_Aggregate 
{
    /**
     * @var Zend_Validate_Interface
     */
    protected $_validator;
    
    /**
     * @param Iterator $it
     * @param Zend_Validate_Interface $validator
     */
    public function __construct(Iterator $it, Zend_Validate_Interface $validator)
    {
        parent::__construct($it);
        $this->_validator = $validator;
    }
    
    /**
     * @return Zend_Validate_Interface
     */
    public function getValidator()
    {
        return $this->_validator;
    }
    
    /**
     * Validate current value before accepting it
     *
     * @return boolean
     */
    public function accept()
    {
        return $this->_validator->isValid($this->current());
    }
}
