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
 * @package     Xi_Storage
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Storage_Session implements Xi_Storage_Interface
{
    /**
     * @var Zend_Session_Namespace
     */
    protected $_namespace;
    
    /**
     * @var string
     */
    protected $_member;
    
    /**
     * Provide either a Zend_Session_Namespace or a string
     * 
     * @param Zend_Session_Namespace|string $namespace
     * @param string $member
     */
    public function __construct($namespace, $member)
    {
        if (!$namespace instanceof Zend_Session_Namespace) {
            $namespace = new Zend_Session_Namespace($namespace);
        }
        $this->_namespace = $namespace;
        $this->_member = $member;
    }
    
    /**
     * @return Zend_Session_Namespace
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
        return !isset($this->getNamespace()->{$this->getMember()});
    }
    
    /**
     * Read contents of storage
     *
     * @return mixed
     */
    public function read()
    {
        return $this->getNamespace()->{$this->getMember()};
    }
    
    /**
     * Write contents to storage
     *
     * @param mixed $contents
     */
    public function write($contents)
    {
        $this->getNamespace()->{$this->getMember()} = $contents;
    }
    
    /**
     * Clear storage contents
     *
     * @return void
     */
    public function clear()
    {
        unset($this->getNamespace()->{$this->getMember()});
    }

}
