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
 * @package     Xi_State
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
abstract class Xi_State_Grammar_Rule_Abstract
{
    protected $_grammar;

    public function __construct($grammar)
    {
        $this->_grammar = $grammar;
    }

    public function getGrammar()
    {
        return $this->_grammar;
    }

    public function getListener($listener)
    {
        if (!$listener instanceof Xi_Event_Listener_Interface) {
            $listener = $this->_getDefaultListener($listener);
        }
        return $listener;
    }

    public function _getDefaultListener($listener)
    {
        return new Xi_Event_Listener_ContextCallback($listener);
    }

    /**
     * @param mixed|Zend_Validate_Interface
     * @return Zend_Validate_Interface
     */
    public function getValidator($condition)
    {
        if (!$condition instanceof Zend_Validate_Interface) {
            $condition = $this->_getDefaultValidator($condition);
        }
        return $condition;
    }

    /**
     * @param mixed|Zend_Validate_Interface
     * @return Xi_State_Validate_Input
     */
    public function getInputValidator($condition)
    {
        return new Xi_State_Validate_Input($this->getValidator($condition));
    }

    /**
     * @return Zend_Validate_Interface
     */
    public function _getDefaultValidator($condition)
    {
        if (is_array($condition)) {
            foreach ($condition as $key => $value) {
                $condition[$key] = new Xi_Validate_Equal($value);
            }
            return new Xi_Validate_Or($condition);
        }
        return new Xi_Validate_Equal($condition);
    }

    /**
     * Apply grammar rule to state machine
     *
     * @param Xi_State_Machine
     * @return void
     */
    abstract public function apply($fsm);
}
