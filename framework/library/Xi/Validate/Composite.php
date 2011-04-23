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
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
abstract class Xi_Validate_Composite implements Zend_Validate_Interface
{
    /**
     * @var array
     */
    protected $_validators = array();

    /**
     * @param array $validators
     */
    public function __construct(array $validators = array())
    {
        $this->_validators = $validators;
    }

    /**
     * @return array
     */
    public function getValidators()
    {
        return $this->_validators;
    }

    /**
     * @param Zend_Validate_Interface $validator
     * @param boolean $prepend
     * @return Xi_Validate_Composite
     */
    public function addValidator($validator, $prepend = false)
    {
        if ($prepend) {
            array_unshift($this->_validators, $validator);
        } else {
            $this->_validators[] = $validator;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        $messages = array();
        foreach ($this->_validators as $validator) {
            $messages = array_merge($messages, $validator->getMessages());
        }
        return $messages;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return array_keys($this->getMessages());
    }
}
