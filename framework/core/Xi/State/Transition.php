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
class Xi_State_Transition
{
    /**
     * @var mixed input
     */
    protected $_on;

    /**
     * @var string state
     */
    protected $_to;

    /**
     * @var Xi_Event_Listener_Collection
     */
    protected $_listeners;

    /**
     * @param Zend_Validate_Interface input condition
     * @param string to state
     * @return void
     */
    public function __construct($on, $to)
    {
        $this->_on = $on;
        $this->_to = $to;
        $this->_listeners = new Xi_Event_Listener_Collection;
    }

    /**
     * Notify listeners of the transition
     *
     * @param Xi_State_Machine
     * @return Xi_Event
     */
    public function notify($fsm)
    {
        return $this->_listeners->invoke(new Xi_Event('transition', $fsm));
    }

    /**
     * Attach a listener that will be notified if the transition is successfully
     * applied
     *
     * @param Xi_Event_Listener_Interface
     * @return Xi_State_Transition
     */
    public function attachListener($listener)
    {
        $this->_listeners->attach($listener);
        return $this;
    }

    /**
     * @return string
     */
    public function getTargetState()
    {
        return $this->_to;
    }

    /**
     * @param mixed
     * @return boolean
     */
    public function isValidInput($input)
    {
        return $this->_on->isValid($input);
    }
}
