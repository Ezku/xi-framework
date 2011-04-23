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
abstract class Xi_State_Grammar_Rule_Action extends Xi_State_Grammar_Rule_Abstract
{
    /**
     * @var Xi_Event_Listener_Interface
     */
    protected $_listener;

    /**
     * @var mixed input condition
     */
    protected $_on;

    public function on($input)
    {
        $this->_on = $input;
        return $this;
    }

    /**
     * Provide a listener to trigger when rule matches
     *
     * @param Xi_Event_Listener_Interface|callback
     * @return Xi_State_Grammar_Rule_Event
     */
    public function trigger($listener)
    {
        $this->_listener = $listener;
        $this->getGrammar()->apply($this);
        $this->_listener = null;
        return $this;
    }

    public function getListener($listener)
    {
        if (null === $this->_on) {
            return parent::getListener($listener);
        }
        return new Xi_Event_Listener_Conditional(parent::getListener($listener), $this->getInputValidator($this->_on));
    }
}
