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
 * Implements a DSL to create state transition rules
 *
 * @category    Xi
 * @package     Xi_State
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_State_Grammar
{
    /**
     * @var Xi_State_Machine
     */
    protected $_fsm;

    /**
     * @param Xi_State_Machine
     * @return void
     */
    public function __construct($fsm)
    {
        $this->_fsm = $fsm;
    }

    /**
     * @return Xi_State_Machine
     */
    public function getStateMachine()
    {
        return $this->_fsm;
    }

    /**
     * @param mixed input
     * @return Xi_State_Grammar_Rule_Transition
     */
    public function on($input)
    {
        $rule = new Xi_State_Grammar_Rule_Transition($this);
        return $rule->on($input);
    }

    /**
     * @param string state
     * @return Xi_State_Grammar_Rule_Transition
     */
    public function from($state)
    {
        $rule = new Xi_State_Grammar_Rule_Transition($this);
        return $rule->from($state);
    }

    /**
     * @param string state
     * @return Xi_State_Grammar_Rule_Action_Entry
     */
    public function before($state)
    {
        $rule = new Xi_State_Grammar_Rule_Action_Entry($this);
        return $rule->before($state);
    }

    /**
     * @param string state
     * @return Xi_State_Grammar_Rule_Action_Exit
     */
    public function after($state)
    {
        $rule = new Xi_State_Grammar_Rule_Action_Exit($this);
        return $rule->after($state);
    }

    /**
     * @return Xi_State_Grammar_Rule_Action_Transition
     */
    public function when()
    {
        return new Xi_State_Grammar_Rule_Action_Transition($this);
    }

    /**
     * @param string state
     * @return Xi_State_Grammar_Rule_Action_Input
     */
    public function in($state)
    {
        $rule = new Xi_State_Grammar_Rule_Action_Input($this);
        return $rule->in($state);
    }

    public function apply($rule)
    {
        return $rule->apply($this->getStateMachine());
    }
}
