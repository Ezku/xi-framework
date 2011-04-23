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
abstract class Xi_Acl_Assert_Composite implements Zend_Acl_Assert_Interface
{
    /**
     * @var array<Zend_Acl_Assert_Interface>
     */
    protected $_asserts = array();
    
    /**
     * @param array<Zend_Acl_Assert_Interface> $asserts
     * @return void
     */
    public function __construct(array $asserts = array())
    {
        $this->_asserts = $asserts;
    }
    
    /**
     * @return array<Zend_Acl_Assert_Interface>
     */
    public function getAsserts()
    {
        return $this->_asserts;
    }
    
    /**
     * @param Zend_Acl_Assert_Interface $assert
     * @param boolean $prepend
     * @return Xi_Acl_Assert_Composite
     */
    public function addAssert($assert, $prepend = false)
    {
         if ($prepend) {
             array_unshift($this->_asserts, $assert);
         } else {
             $this->_asserts[] = $assert;
         }
         return $this;
    }
}