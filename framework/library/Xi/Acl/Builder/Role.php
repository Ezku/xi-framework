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
class Xi_Acl_Builder_Role extends Xi_Acl_Builder_Abstract 
{
    /**
     * Parent role to inherit added roles from
     *
     * @var Zend_Acl_Role
     */
    protected $_role;
    
    /**
     * Set parent role
     *
     * @param Zend_Acl_Role $parent
     * @return Xi_Acl_Builder_Role
     */
    public function setParentRole(Zend_Acl_Role $parent)
    {
        $this->_role = $parent;
        return $this;
    }
    
    /**
     * Get parent role
     *
     * @return Zend_Acl_Role
     */
    public function getParentRole()
    {
        return $this->_role;
    }
    
    /**
     * @param string $role
     * @return Zend_Acl_Role
     */
    public function formatRole($role)
    {
        return new Zend_Acl_Role($role);
    }
    
    /**
     * Build an Acl object according to configuration
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
                    throw new Xi_Acl_Builder_Role_Exception($error);
                }
                $this->addRole($value);
            } else {
                $role = $this->addRole($key);
                $child = $this->_getChild()->setParentRole($role);
                if ($value instanceof Zend_Config) {
                    $this->setAcl($child->build($value));
                } else {
                    $child->addRole($value);
                    $this->setAcl($child->getAcl());
                }
            }
        }
        
        return $this->getAcl();
    }
    
    /**
     * Add role to Acl
     *
     * @param string $role
     * @return Zend_Acl_Role
     */
    public function addRole($role)
    {
        $role = $this->formatRole($role);
        $this->getAcl()->addRole($role, $this->getParentRole());
        return $role;
    }
}