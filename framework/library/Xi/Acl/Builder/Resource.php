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
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Acl_Builder_Resource extends Xi_Acl_Builder_Abstract 
{
    /**
     * Parent resource to inherit added resources from
     *
     * @var Zend_Acl_Resource
     */
    protected $_resource;
    
    /**
     * Set parent resource
     *
     * @param string $parent
     * @return Xi_Acl_Builder_Resource
     */
    public function setParentResource($parent)
    {
        $this->_resource = $parent;
        return $this;
    }
    
    /**
     * Get parent resource
     *
     * @return Zend_Acl_Resource
     */
    public function getParentResource()
    {
        return $this->_resource;
    }
    
    /**
     * @param string|Zend_Acl_Resource $resource
     * @return Zend_Acl_Resource
     */
    public function formatResource($resource)
    {
        if ($parent = $this->getParentResource()) {
            $resource = $resource instanceof Zend_Acl_Resource ? $resource->getResourceId() : $resource;
            return new Zend_Acl_Resource($parent->getResourceId() . '.' . $resource);
        }
        return $resource instanceof Zend_Acl_Resource ? $resource : new Zend_Acl_Resource($resource);
    }
    
    /**
     * Create Acl resources out of configuration data
     * 
     * @param Zend_Config $config
     * @return Zend_Acl
     */
    public function build(Zend_Config $config)
    {
        foreach ($config as $key => $value) {
            if (is_int($key)) {
                if (!is_string($value)) {
                    $error = sprintf("Invalid contents for an integer index, received %s when expecting a string", Xi_Class::getType($value));
                    throw new Xi_Acl_Builder_Resource_Exception($error);
                }
                $this->addResource($value);
            } else {
                $resource = $this->addResource($key);
                $child = $this->_getChild()->setParentResource($resource);
                if ($value instanceof Zend_Config) {
                    $this->setAcl($child->build($value));
                } else {
                    $child->addResource($value);
                    $this->setAcl($child->getAcl());
                }
            }
        }
        
        return $this->getAcl();
    }
    
    /**
     * Add resource to Acl
     *
     * @param string $resource
     * @return Zend_Acl_Resource
     */
    public function addResource($resource)
    {
        $resource = $this->formatResource($resource);
        $this->getAcl()->add($resource, $this->getParentResource());
        return $resource;
    }
}
