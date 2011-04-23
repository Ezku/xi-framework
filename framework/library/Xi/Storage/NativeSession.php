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
 * Directly accesses the $_SESSION superglobal instead of going through
 * Zend_Session_Namespace
 * 
 * @category    Xi
 * @package     Xi_Storage
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Storage_NativeSession implements Xi_Storage_Interface
{
    /**
     * @var string
     */
    protected $_namespace;
    
    /**
     * @var string
     */
    protected $_member;
    
    /**
     * @param string $namespace
     * @param string $member
     */
    public function __construct($namespace, $member)
    {
        $this->_namespace = $namespace;
        $this->_member = $member;
    }
    
    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->_namespace;
    }
    
    /**
     * @return string
     */
    public function getMember()
    {
        return $this->_member;
    }
    
    /**
     * Check whether the storage is empty
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return !isset($_SESSION[$this->getNamespace()][$this->getMember()]);
    }
    
    /**
     * Read contents of storage
     *
     * @return mixed
     */
    public function read()
    {
        if ($this->isEmpty()) {
            return null;
        }
        return $_SESSION[$this->getNamespace()][$this->getMember()];
    }
    
    /**
     * Write contents to storage
     *
     * @param mixed $contents
     */
    public function write($contents)
    {
        $_SESSION[$this->getNamespace()][$this->getMember()] = $contents;
    }
    
    /**
     * Clear storage contents
     *
     * @return void
     */
    public function clear()
    {
        unset($_SESSION[$this->getNamespace()][$this->getMember()]);
    }

}
