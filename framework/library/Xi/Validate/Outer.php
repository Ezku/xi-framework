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
 * Wraps a validator
 *
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Validate_Outer implements Zend_Validate_Interface, Xi_Validate_Aggregate
{
    /**
     * Inner validator
     *
     * @var Zend_Validate_Interface
     */
    protected $_validator;

    /**
     * Accepts either a validator or a validator aggregate.
     * 
     * @param Zend_Validate_Interface|Xi_Validate_Aggregate $validator
     * @return void
     */
    public function __construct($validator)
    {
        if (!($validator instanceof Zend_Validate_Interface) && ($validator instanceof Xi_Validate_Aggregate)) {
            $validator = $validator->getValidator();
        }
        $this->_validator = $validator;
    }

    /**
     * Retrieve inner validator
     *
     * @return Zend_Validator_Interface
     */
    public function getValidator()
    {
        return $this->_validator;
    }

    public function getMessages()
    {
        return $this->getValidator()->getMessages();
    }

    public function getErrors()
    {
        return $this->getValidator()->getErrors();
    }

    public function isValid($value)
    {
        return $this->getValidator()->isValid($value);
    }
}
