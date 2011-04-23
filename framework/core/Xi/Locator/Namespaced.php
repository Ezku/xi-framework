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
 * @package     Xi_Locator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Locator_Namespaced extends Xi_Locator
{
    /**
     * @var string separates namespaces in resource names
     */
    const NAMESPACE_SEPARATOR = '.';
    
    /**
     * @var string which class to instantiate branches as
     */
    protected $_branchClass = __CLASS__;

    /**
     * @param string resource name
     * @param mixed contents
     * @return Xi_Locator
     */
    public function offsetSet($name, $resource)
    {
        $pos = strpos($name, self::NAMESPACE_SEPARATOR);
        if (false === $pos) {
            return parent::offsetSet($name, $resource);
        }
        
        $root   = substr($name, 0, $pos);
        $branch = substr($name, $pos+1);
        if (!parent::offsetExists($root)) {
            parent::offsetSet($root, $this->createBranch());
        }
        $namespace = parent::offsetGet($root);
        if (!$namespace instanceof self) {
            throw new Xi_Locator_Exception('Namespace '.$name.' already in use');
        }
        $namespace->offsetSet($branch, $resource);
        
        return $this;
    }

    /**
     * @param string resource name
     * @param boolean parse namespaces in resource name
     * @return boolean
     */
    public function offsetExists($name, $parseNamespaces = true)
    {
        if (!($parseNamespaces && strpos($name, self::NAMESPACE_SEPARATOR))) {
            return parent::offsetExists($name);
        }
        
        /**
         * Parse namespaces into nodes and traverse through them
         */
        $nodes = explode(self::NAMESPACE_SEPARATOR, $name);
        $value = $this;
        end($nodes);
        $last  = key($nodes);
        foreach ($nodes as $i => $node) {
            if (!$value instanceof self || !$value->offsetExists($node, false)) {
                $parent = $this->_parent;
                return isset($parent) ? $parent->offsetExists($name) : false;
            } elseif ($i == $last) {
                return true;
            }
            $value = $value->offsetGet($node, false, false);
        }
        return true;
    }

    /**
     * @param string resource name
     * @param boolean parse namespaces in resource name
     * @return null|mixed
     */
    public function offsetGet($name, $parseNamespaces = true)
    {
        if (!($parseNamespaces && strpos($name, self::NAMESPACE_SEPARATOR))) {
            return parent::offsetGet($name);
        }
        
        $nodes = explode(self::NAMESPACE_SEPARATOR, $name);
        $value = $this;
        foreach ($nodes as $node) {
            if (!$value instanceof Xi_Locator || !$value->hasRaw($node)) {
                $parent = $this->_parent;
                return isset($parent) ? $parent->offsetGet($name) : null;
            }
            $value = $value->getRaw($node);
        }
        
        $value = $this->getFactoryValues($value);
        
        return $value;
    }

    /**
     * Retrieve a resource.
     *
     * If the resource stored under the given name is an instance of
     * Xi_Factory_Interface, the return value will be retrieved from the
     * factory.
     *
     * If the resource does not exist, return null.
     *
     * @see Xi_Factory_Interface
     * @see offsetGet()
     *
     * @param string resource name
     * @param null|array optional arguments
     * @return null|mixed
     */
    public function getResource($name, $args = null, $parseNamespaces = true)
    {
        if (!($parseNamespaces && strpos($name, self::NAMESPACE_SEPARATOR))) {
            return parent::getResource($name, $args);
        }
        
        $nodes = explode(self::NAMESPACE_SEPARATOR, $name);
        $value = $this;
        foreach ($nodes as $node) {
            if (!$value instanceof Xi_Locator || !$value->hasRaw($node)) {
                $parent = $this->_parent;
                return isset($parent) ? $parent->getResource($name, $args) : null;
            }
            $value = $value->getRaw($node);
        }
        $value = $this->getFactoryValues($value, $args);
        return $value;
    }
}