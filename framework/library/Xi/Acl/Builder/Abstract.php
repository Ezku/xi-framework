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
abstract class Xi_Acl_Builder_Abstract implements Xi_Acl_Builder_Interface
{
    /**
     * Template Acl object 
     *
     * @var Zend_Acl
     */
    protected $_acl;
    
    /**
     * Provide a Zend_Acl instance to set the template Acl object
     * 
     * @param Zend_Acl $acl
     * @return void
     */
    public function __construct(Zend_Acl $acl = null)
    {
        if (null === $acl) {
            $acl = new Zend_Acl;
        }
        
        $this->setAcl($acl);
        $this->init();
    }
    
    /**
     * Template method triggered on construction
     * 
     * @return void
     */
    public function init()
    {}
    
    /**
     * Set the template Acl object
     * 
     * @param Zend_Acl $acl
     * @return Xi_Acl_Builder_Abstract
     */
    public function setAcl(Zend_Acl $acl)
    {
        $this->_acl = $acl;
        return $this;
    }
    
    /**
     * Retrieve the Acl template object
     *
     * @return Zend_Acl
     */
    public function getAcl()
    {
        return $this->_acl;
    }
    
    /**
     * Retrieve another instance of the current class with the same Acl template
     *
     * @return Xi_Acl_Builder_Abstract
     */
    protected function _getChild()
    {
        $class = get_class($this);
        return new $class($this->getAcl());
    }
}
