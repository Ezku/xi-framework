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
class Xi_Acl_Builder extends Xi_Acl_Builder_Abstract
{
    /**
     * @var array Xi_Acl_Builder_Interface objects or strings
     */
    protected $_builders = array(
        'roles' => 'Xi_Acl_Builder_Role',
        'resources' => 'Xi_Acl_Builder_Resource',
        'privileges' => 'Xi_Acl_Builder_Privilege',
    );
    
    /**
     * Create a new Xi_Acl_Builder instance
     * 
     * @return Xi_Acl_Builder
     */
    public static function create()
    {
        $args = func_get_args();
        return Xi_Class::create(__CLASS__, $args);
    }
    
    /**
     * Set builder for configuration namespace. Accepts either a
     * Xi_Acl_Builder_Interface object or a class name.
     * 
     * @param string $namespace
     * @param string|Xi_Acl_Builder_Interface $builder
     * @return Xi_Acl_Builder
     */
    public function setBuilder($namespace, $builder)
    {
        $this->_builders[$namespace] = $builder;
        return $this;
    }
    
    /**
     * Get builders for namespaces
     *
     * @return array Xi_Acl_Builder_Interface objects
     */
    public function getBuilders()
    {
        foreach ($this->_builders as &$builder) {
            if (is_string($builder)) {
                $builder = new $builder;
            }
        }
        return $this->_builders;
    }
    
    /**
     * Set builders for namespaces
     * 
     * @param array Xi_Acl_Builder_Interface objects or class names
     * @return Xi_Acl_Builder
     */
    public function setBuilders(array $builders)
    {
        $this->_builders = $builders;
        return $this;
    }
    
    /**
     * Apply registered builders to configuration
     *
     * @param Zend_Config $config
     * @return Zend_Acl
     */
    public function build(Zend_Config $config)
    {
        foreach ($this->getBuilders() as $namespace => $builder) {
            if (isset($config->$namespace)) {
                $builder->setAcl($this->getAcl());
                $this->setAcl($builder->build($config->$namespace));
            }
        }
        
        return $this->getAcl();
    }
}
