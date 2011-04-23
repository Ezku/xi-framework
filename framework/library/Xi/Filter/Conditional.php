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
 * Applies different filters based on whether incoming value is valid
 * 
 * @category    Xi
 * @package     Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_Conditional extends Xi_Validate_Outer implements Zend_Filter_Interface
{
    /**
     * @var Zend_Filter_Interface
     */
    protected $_success;
    
    /**
     * @var Zend_Filter_Interface
     */
    protected $_failure;
    
    /**
     * @param Zend_Validate_Interface $validator
     * @param Zend_Filter_Interface $success
     * @param Zend_Filter_Interface $failure
     * @return void
     */
    public function __construct($validator, $success, $failure)
    {
        parent::__construct($validator);
        $this->_success   = $success;
        $this->_failure   = $failure;
    }
    
    /**
     * @return Zend_Filter_Interface
     */
    public function getSuccessFilter()
    {
        return $this->_success;
    }
    
    /**
     * @return Zend_Filter_Interface
     */
    public function getFailureFilter()
    {
        return $this->_failure;
    }
    
    public function filter($value)
    {
        switch ($this->isValid($value)) {
            case true:
                return $this->_success->filter($value);
            case false:
                return $this->_failure->filter($value);
            default:
                return null;
        }
    }
}