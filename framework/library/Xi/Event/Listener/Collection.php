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
 * @package     Xi_Event
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Event_Listener_Collection implements Xi_Event_Listener_Collection_Interface
{
    /**
     * @var array Xi_Event_Listener_Interface
     */
    protected $_listeners = array();
    
    /**
     * Provide an array of Xi_Event_Listener_Interface instances to set default listeners
     *
     * @param array $listeners
     */
    public function __construct($listeners = array())
    {
        foreach ($listeners as $listener) {
            $this->attach($listener);
        }
    }

    /**
     * Attach event listener to collection
     *
     * @param Xi_Event_Listener_Interface
     * @return Xi_Event_Listener_Collection
     */
    public function attach($listener)
    {
        $this->_listeners[] = $listener;
        return $this;
    }

    /**
     * @param Xi_Event
     * @return Xi_Event
     */
    public function invoke($event)
    {
        foreach ($this->_listeners as $listener) {
            $value = $listener->invoke($event);
            if (null !== $value) {
                $event->setReturnValue($value);
            }
        }
        return $event;
    }
}
