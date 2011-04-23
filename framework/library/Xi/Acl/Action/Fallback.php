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
 * Assumes that a resource prefixed with another resource and an inheritance
 * separator character inherits said resource. If an unknown resource is
 * encountered, will fall back to the parent resource until a resource is found
 * or there is no parent resource, in which case a null value will be used.
 * 
 * @category    Xi
 * @package     Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Acl_Action_Fallback extends Xi_Acl_Action
{
    /**
     * Separator character for Acl resources
     */
    const INHERITANCE_SEPARATOR = '.';
    
	/**
	 * Create Acl action based on request parameters
	 * 
	 * @param Zend_Controller_Request_Abstract $request
	 * @return Xi_Acl_Action_Interface
	 */
    public static function createFromRequest(Zend_Controller_Request_Abstract $request)
    {
   	 	$module = $request->getModuleName();
   	 	$controller = $request->getControllerName();
    	$action = $request->getActionName();
    	
    	return new self($action, $module . '.' . $controller, null, $request->getParams());
    }
    
    /**
     * Check whether action is valid against an Acl
     *
     * @param Zend_Acl $acl
     * @return boolean
     */
    public function isAllowed(Zend_Acl $acl)
    {
        $resource = $this->getResource();
        while (!$acl->has($resource)) {
            $pos = strrpos($resource, self::INHERITANCE_SEPARATOR);
            if (!$pos) {
                $resource = null;
                break;
            }
            $resource = substr($resource, 0, $pos);
        }
        return $acl->isAllowed($this->getRole(), $resource, $this->getPrivilege(), $this->getParams());
    }
}