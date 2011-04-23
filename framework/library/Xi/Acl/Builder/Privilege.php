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
class Xi_Acl_Builder_Privilege extends Xi_Acl_Builder 
{
    protected $_builders = array(
        'allow' => 'Xi_Acl_Builder_Privilege_Allow',
        'deny' => 'Xi_Acl_Builder_Privilege_Deny'
    );
    
    /**
     * Build an Acl object according to configuration
     * 
     * @param Zend_Config $config
     * @return Zend_Acl
     */
    public function build(Zend_Config $config)
    {
        foreach ($this->getBuilders() as $namespace => $builder) {
            $builder->setAcl($this->getAcl());
            if (isset($config->$namespace)) {
                if (!$config->$namespace instanceof Zend_Config) {
                    $builder->setRole($config->$namespace);
                    $builder->addPrivilege(null, null);
                } else {
                    foreach ($config->$namespace as $role => $resources) {
                        if (is_int($role)) {
                            $builder->setRole($resources);
                            $builder->addPrivilege(null, null);
                        } else {
                            $builder->setRole($role);
                            if ($resources instanceof Zend_Config) {
                                $builder->build($resources);
                            } elseif (false !== ($resource = $builder->formatResource($resources))) {
                                $builder->addPrivilege($resource, null);
                            } else {
                                $error = sprintf('Invalid value "%s", did not map to a resource', $resources);
                                throw new Xi_Acl_Builder_Privilege_Exception($error);
                            }
                        }
                    }
                }
                $this->setAcl($builder->getAcl());
            }
        }
        
        return $this->getAcl();
    }
}
