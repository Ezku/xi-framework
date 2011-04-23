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
 * Allows listening on validation result
 * 
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Validate_Listener extends Xi_Validate_Outer
{
    /**
     * @var Xi_Event_Dispatcher
     */
    protected $_dispatcher;
    
    /**
     * @param Zend_Validate_Interface $validator
     * @param Xi_Event_Dispatcher $dispatcher
     */
    public function __construct($validator, $dispatcher = null)
    {
        parent::__construct($validator);
        if (null === $dispatcher) {
            $dispatcher = new Xi_Event_Dispatcher;
        }
        $this->_dispatcher = $dispatcher;
    }
    
    /**
     * @return Xi_Event_Dispatcher
     */
    public function getEventDispatcher()
    {
        return $this->_dispatcher;
    }
    
    /**
     * Attach a listener that will be triggered for valid values
     *
     * @param Xi_Event_Listener_Interface $listener
     * @return Xi_Validate_Listener
     */
    public function attachSuccessListener($listener)
    {
        $this->getEventDispatcher()->attach('success', $listener);
        return $this;
    }
    
    /**
     * Attach a listener that will be triggered for invalid values
     *
     * @param Xi_Event_Listener_Interface $listener
     * @return Xi_Validate_Listener
     */
    public function attachFailureListener($listener)
    {
        $this->getEventDispatcher()->attach('failure', $listener);
        return $this;
    }
    
    /**
     * Delegate validation to inner validator, notify event listeners of
     * validation results
     *
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        if (parent::isValid($value)) {
            $this->getEventDispatcher()->notify(new Xi_Event('success', $this, array('in' => $value, 'out' => true)));
            return true;
        }
        $this->getEventDispatcher()->notify(new Xi_Event('failure', $this, array('in' => $value, 'out' => false)));
        return false;
    }
}
