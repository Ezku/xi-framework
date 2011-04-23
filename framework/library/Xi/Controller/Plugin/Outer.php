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
 * Decorates a controller plugin
 * 
 * @category    Xi
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_Plugin
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Controller_Plugin_Outer extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zend_Controller_Plugin_Abstract
     */
    protected $_plugin;
    
    /**
     * @param Zend_Controller_Plugin_Abstract $plugin
     * @return void
     */
    public function __construct(Zend_Controller_Plugin_Abstract $plugin)
    {
        $this->_plugin = $plugin;
    }
    
    /**
     * @return Zend_Controller_Plugin_Abstract
     */
    public function getPlugin()
    {
        return $this->_plugin;
    }

    /**
     * @return void
     */
    public function dispatchLoopShutdown()
    {
        return $this->getPlugin()->dispatchLoopShutdown();
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function dispatchLoopStartup($request)
    {
        return $this->getPlugin()->dispatchLoopStartup($request);
    }

    /**
     * @return Zend_Controller_Request_Abstract
     */
    public function getRequest()
    {
        return $this->getPlugin()->getRequest();
    }

    /**
     * @return Zend_Controller_Response_Abstract
     */
    public function getResponse()
    {
        return $this->getPlugin()->getResponse();
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function postDispatch($request)
    {
        return $this->getPlugin()->postDispatch($request);
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function preDispatch($request)
    {
        return $this->getPlugin()->preDispatch($request);
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function routeShutdown($request)
    {
        return $this->getPlugin()->routeShutdown($request);
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function routeStartup($request)
    {
        return $this->getPlugin()->routeStartup($request);
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return Zend_Controller_Plugin_Abstract
     */
    public function setRequest($request)
    {
        $this->getPlugin()->setRequest($request);
        return $this;
    }

    /**
     * @param Zend_Controller_Response_Abstract $response
     * @return Zend_Controller_Plugin_Abstract
     */
    public function setResponse($response)
    {
        $this->getPlugin()->setResponse($response);
        return $this;
    }

}
