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
 * @package     Xi_Acl
 * @subpackage  Xi_Acl_Assert
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Acl_Assert_Event implements Zend_Acl_Assert_Interface
{
    /**
     * @var Xi_Event_Dispatcher
     */
    protected $_eventDispatcher;
    
    /**
     * @return Xi_Event_Dispatcher
     */
    public function getEventDispatcher()
    {
        if (null === $this->_eventDispatcher) {
            $this->_eventDispatcher = Xi_Event_Dispatcher::getInstance($this->getEventDispatcherName());
        }
        return $this->_eventDispatcher;
    }
    
    /**
     * @return string
     */
    public function getEventDispatcherName()
    {
        return __CLASS__;
    }
    
    /**
     * @param Zend_Acl $acl
     * @param Zend_Acl_Role_Interface $role
     * @param Zend_Acl_Resource_Interface $resource
     * @param string $privilege
     * @return string
     */
    public function getEventName(Zend_Acl $acl, Zend_Acl_Role_Interface $role = null, Zend_Acl_Resource_Interface $resource = null, $privilege = null)
    {
        return 'assert';
    }
    
    /**
     * @param Zend_Acl $acl
     * @param Zend_Acl_Role_Interface $role
     * @param Zend_Acl_Resource_Interface $resource
     * @param string $privilege
     * @return Xi_Event
     */
    public function getEvent(Zend_Acl $acl, Zend_Acl_Role_Interface $role = null, Zend_Acl_Resource_Interface $resource = null, $privilege = null)
    {
        return new Xi_Event(
            $this->getEventName($acl, $role, $resource, $privilege),
            $this,
            array(
                'acl' => $acl,
                'role' => $role,
                'resource' => $resource,
                'privilege' => $privilege
            )
        );
    }
    
    /**
     * Dispatches an event and uses the result to determine whether to grant
     * access. If the event is cancelled, access is denied.
     * 
     * @param Zend_Acl $acl
     * @param Zend_Acl_Role_Interface $role
     * @param Zend_Acl_Resource_Interface $resource
     * @param string $privilege
     * @return boolean
     */
    public function assert(Zend_Acl $acl, Zend_Acl_Role_Interface $role = null, Zend_Acl_Resource_Interface $resource = null, $privilege = null)
    {
        $event = $this->getEvent($acl, $role, $resource, $privilege);
        $event = $this->getEventDispatcher()->notify($event);
        
        if ($event->isCancelled()) {
            return false;
        }
        
        if ($event->hasReturnValue()) {
            return (boolean) $event->getReturnValue();
        }
        
        return false;
    }
}
