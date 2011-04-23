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
 * @category    Xi_Test
 * @package     Xi_Filter
 * @group       Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_ListenerTest extends Xi_Filter_TestCase 
{
    public function listen($event)
    {
        $this->event = $event;
    }
    
    public function getListenerFilterMock()
    {
        $subject = new Xi_Filter_Listener($this->getFilterMock());
        $subject->attach(new Xi_Event_Listener_Callback(array($this, 'listen')));
        
        return $subject;
    }
    
    public function testTriggersEventOnFilter()
    {
        $subject = $this->getListenerFilterMock();
        
        $this->assertEquals('bar', $subject->filter('foo'));
        $this->assertTrue($this->event instanceof Xi_Event);
    }
    
    public function testEventProvidesInAndOutValues()
    {
        $this->getListenerFilterMock()->filter('foo');
        
        $this->assertEquals('foo', $this->event->in);
        $this->assertEquals('bar', $this->event->out);
    }
    
    public function testListenerCanAlterReturnValue()
    {
        $listener = $this->getMock('Xi_Event_Listener_Interface');
        $listener->expects($this->once())->method('invoke')->will($this->returnValue('foobar'));
        
        $subject = $this->getListenerFilterMock()->attach($listener);
        $this->assertEquals('foobar', $subject->filter('foo'));
    }
}
