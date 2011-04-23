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
 * Handles redirecting the user to login or access forbidden pages when trying
 * to access resources restricted by the Acl
 * 
 * @category    Xi
 * @package     Xi_User
 * @subpackage  Xi_User_Controller
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_User_Controller_Plugin_Auth extends Xi_User_Controller_Plugin_Abstract 
{
    /**
     * The redirection to apply when trying to access a resource to which the
     * user does not have access
     * 
     * @var Xi_Controller_Request_Redirection_Interface
     */
    protected $_accessForbiddenRedirection;
    
    /**
     * The redirection to apply when trying to access a resource that requires
     * authentication as an unauthenticated user
     * 
     * @var Xi_Controller_Request_Redirection_Interface
     */
    protected $_notAuthenticatedRedirection;
    
    /**
     * Whether to check for access rights on dispatch loop startup only
     * ({@link dispatchLoopStartup()} or on each individual request passed
     * through the front controller ({@link preDispatch()}).
     *
     * @var boolean
     */
    protected $_onlyOnStartup = true;
    
    /**
     * Target module, controller and action for the default access forbidden
     * redirection
     * 
     * @var array
     */
    protected $_defaultAccessForbiddenTarget = array(
        'module' => 'default',
        'controller' => 'error',
        'action' => 'forbidden'
    );
    
    /**
     * Target module, controller and action for the default not authenticated
     * redirection
     * 
     * @var array
     */
    protected $_defaultNotAuthenticatedTarget = array(
        'module' => 'default',
        'controller' => 'user',
        'action' => 'login'
    );
    
    /**
     * Provide redirection objects or Xi_Controller_Request_Redirection
     * constructor argument arrays to set default redirections
     * 
     * @see Xi_Controller_Request_Redirection
     * @param Xi_Controller_Request_Redirection_Interface|array $notAuthenticated
     * @param Xi_Controller_Request_Redirection_Interface|array $accessForbidden
     * @return void
     */
    public function __construct($notAuthenticated = null, $accessForbidden = null)
    {
        if (null !== $notAuthenticated) {
            if (is_array($notAuthenticated)) {
                $notAuthenticated = Xi_Class::create('Xi_Controller_Request_Redirection', $notAuthenticated);
            }
            $this->setNotAuthenticatedRedirection($notAuthenticated);
        }
        if (null !== $accessForbidden) {
            if (is_array($accessForbidden)) {
                $accessForbidden = Xi_Class::create('Xi_Controller_Request_Redirection', $accessForbidden);
            }
            $this->setAccessForbiddenRedirection($accessForbidden);
        }
    }
    
    /**
     * @return Xi_Controller_Request_Redirection_Interface
     */
    public function getAccessForbiddenRedirection()
    {
        if (null === $this->_accessForbiddenRedirection) {
            $this->_accessForbiddenRedirection = $this->getDefaultAccessForbiddenRedirection();
        }
        return $this->_accessForbiddenRedirection;
    }
    
    /**
     * @return Xi_Controller_Request_Redirection_Interface
     */
    public function getDefaultAccessForbiddenRedirection()
    {
        $module = $this->_defaultAccessForbiddenTarget['module'];
        $controller = $this->_defaultAccessForbiddenTarget['controller'];
        $action = $this->_defaultAccessForbiddenTarget['action'];
        
        return $this->_createRedirection($action, $controller, $module);
    }
    
    /**
     * @param string $action
     * @param string $controller
     * @param string $module
     * @return Xi_Controller_Request_Redirection_Interface
     */
    protected function _createRedirection($action, $controller, $module)
    {
        return new Xi_Controller_Request_Redirection($action, $controller, $module);
    }
    
    /**
     * @param Xi_Controller_Request_Redirection_Interface $redirection
     * @return Xi_User_Controller_Plugin
     */
    public function setAccessForbiddenRedirection(Xi_Controller_Request_Redirection_Interface $redirection)
    {
        $this->_accessForbiddenRedirection = $redirection;
        return $this;
    }
    
    /**
     * @return Xi_Controller_Request_Redirection_Interface
     */
    public function getNotAuthenticatedRedirection()
    {
        if (null === $this->_notAuthenticatedRedirection) {
            $this->_notAuthenticatedRedirection = $this->getDefaultNotAuthenticatedRedirection();
        }
        return $this->_notAuthenticatedRedirection;
    }
    
    /**
     * @return Xi_Controller_Request_Redirection_Interface
     */
    public function getDefaultNotAuthenticatedRedirection()
    {
        $module = $this->_defaultNotAuthenticatedTarget['module'];
        $controller = $this->_defaultNotAuthenticatedTarget['controller'];
        $action = $this->_defaultNotAuthenticatedTarget['action'];
        
        return $this->_createRedirection($action, $controller, $module);
    }
    
    /**
     * @param Xi_Controller_Request_Redirection_Interface $redirection
     * @return Xi_User_Controller_Plugin
     */
    public function setNotAuthenticatedRedirection(Xi_Controller_Request_Redirection_Interface $redirection)
    {
        $this->_notAuthenticatedRedirection = $redirection;
        return $this;
    }
    
    /**
     * @param boolean $flag
     * @return Xi_User_Controller_Plugin
     */
    public function setOnlyOnStartup($flag)
    {
        $this->_onlyOnStartup = (boolean) $flag;
        return $this;
    }
    
    /**
     * @return boolean
     */
    public function getOnlyOnStartup()
    {
        return $this->_onlyOnStartup;
    }
    
    /**
     * Trigger {@link check()} if {@link $_onlyOnStartup} is enabled.
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        if ($this->getOnlyOnStartup()) {
            $this->check($request);
        }
    }
    
    /**
     * Trigger {@link check()} if {@link $_onlyOnStartup} is disabled.
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
	    if (!$this->getOnlyOnStartup()) {
	        $this->check($request);
	    }
	}
    
	/**
	 * Apply relevant redirection in case access to current request target is
	 * forbidden for user
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 * @return
	 */
	public function check(Zend_Controller_Request_Abstract $request)
	{
	    $user = $this->getUser();
    	
    	if ($user->isAllowed($this->getAclAction($request))) {
    	    return;
    	}
    	
    	if ($user->isAuthed()) {
    	    $this->getAccessForbiddenRedirection()->apply($request);
    	} else {
    	    $this->getNotAuthenticatedRedirection()->apply($request);
    	}
	}
	
	/**
	 * Retrieve Xi_Acl_Action_Interface object based on request parameters
	 * 
	 * @param Zend_Controller_Request_Abstract $request
	 * @return Xi_Acl_Action_Interface
	 */
	public function getAclAction(Zend_Controller_Request_Abstract $request)
	{
	    return Xi_Acl_Action_Fallback::createFromRequest($request);
	}
}
