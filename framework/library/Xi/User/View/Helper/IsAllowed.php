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
 * @package     Xi_User
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_User_View_Helper_IsAllowed extends Xi_User_View_Helper_Abstract
{
    /**
     * Get request object
     *
     * @return Zend_Controller_Request_Abstract
     */
    public function getRequest()
    {
        return Zend_Controller_Front::getInstance()->getRequest();
    }
    
    /**
     * Check whether user is allowed to perform action. Action, controller and
     * module default to current request values if not provided.
     *
     * @param string $action
     * @param string $controller
     * @param string $module
     * @param array $params
     * @return boolean
     */
    public function isAllowed($action = null, $controller = null, $module = null, $params = array())
    {
        $request = $this->getRequest(); 
        
        if (null === $module) {
            $module = $request->getModuleName();
        }
        if (null === $controller) {
            $controller = $request->getControllerName();
        }
        if (null === $action) {
            $action = $request->getActionName();
        }
        
        return $this->getUser()->isAllowed($this->getAclAction($action, $controller, $module, $params));
    }
    
    /**
     * Get Xi_Acl_Action_Interface object based on action, controller and
     * module names
     * 
     * @param string $action
     * @param string $controller
     * @param string $module
     * @param array $params
     * @return Xi_Acl_Action_Interface
     */
    public function getAclAction($action, $controller, $module, $params)
    {
        return new Xi_Acl_Action_Fallback($action, $module . '.' . $controller, null, $params);
    }
}
