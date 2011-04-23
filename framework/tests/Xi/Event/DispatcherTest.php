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
 * @package     Xi_Event
 * @group       Xi_Event
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Event_DispatcherTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Xi_Event_Dispatcher::resetInstances();
    }

    public function testDefaultSingletonInstanceIsNamedGlobal()
    {
        $this->assertFalse(Xi_Event_Dispatcher::hasInstance('global'));
        $this->assertTrue(Xi_Event_Dispatcher::getInstance() instanceof Xi_Event_Dispatcher);
        $this->assertTrue(Xi_Event_Dispatcher::hasInstance('global'));
    }

    public function testReturnsEventOnNotify()
    {
        $event = new Xi_Event('event');
        $dispatcher = new Xi_Event_Dispatcher;
        $this->assertTrue($event === $dispatcher->notify($event));
    }

    public function testCanBeNotifiedViaMagicInterface()
    {
        $dispatcher = new Xi_Event_Dispatcher;
        $this->assertEquals('event', $dispatcher->event()->getName());
    }

    public function testListenerIsInvokedWithNotify()
    {
        $event = new Xi_Event('event');

        $listener = $this->getMock('Xi_Event_Listener_Interface');
        $listener->expects($this->once())->method('invoke')->with($this->equalTo($event));

        $dispatcher = new Xi_Event_Dispatcher;
        $dispatcher->attach('event', $listener);
        $dispatcher->notify($event);
    }

    public function testListenerIsInvokedWithMagicInterface()
    {
        $listener = $this->getMock('Xi_Event_Listener_Interface');
        $listener->expects($this->once())->method('invoke');

        $dispatcher = new Xi_Event_Dispatcher;
        $dispatcher->attach('event', $listener);
        $dispatcher->event();
    }
}
