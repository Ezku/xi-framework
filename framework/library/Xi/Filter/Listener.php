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
 * Notifies listeners when the filter is triggered
 * 
 * @category    Xi
 * @package     Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_Listener extends Xi_Filter_Outer implements Xi_Event_Subject_Interface
{
    /**
     * @var Xi_Event_Listener_Collection
     */
    protected $_listeners;
    
    /**
     * Name of the event to trigger on filter
     * 
     * @var string
     */
    protected $_event;
    
    /**
     * @param Zend_Filter_Interface $filter
     * @param string $event
     */
    public function __construct($filter, $event = 'filter')
    {
        parent::__construct($filter);
        $this->_listeners = new Xi_Event_Listener_Collection;
        $this->_event = $event;
    }
    
    public function attach($listener)
    {
        $this->_listeners->attach($listener);
        return $this;
    }
    
    public function filter($value)
    {
        $filtered = parent::filter($value);
        $event = $this->_listeners->invoke(new Xi_Event($this->_event, $this, array('in' => $value, 'out' => $filtered)));
        return $event->hasReturnValue() ? $event->getReturnValue() : $filtered;
    }
}
