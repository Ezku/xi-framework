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
class Xi_Filter_TestCase extends PHPUnit_Framework_TestCase 
{
    /**
     * @param boolean expects filter to be called?
     * @return Zend_Filter_Interface
     */
    public function getFilterMock($expect = true)
    {
        $filter = $this->getMock('Zend_Filter_Interface');
        if ($expect) {
            $filter->expects($this->once())->method('filter')->with($this->equalTo('foo'))->will($this->returnValue('bar'));
        } else {
            $filter->expects($this->never())->method('filter');
        }
        return $filter;
    }
    
    public function testMockFilterAcceptsFooAndReturnsBar()
    {
        $this->assertEquals('bar', $this->getFilterMock()->filter('foo'));
    }
}
