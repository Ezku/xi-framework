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
 * Only provide contextual events to the inner plugin if the request is valid
 * 
 * @category    Xi
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_Plugin
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Controller_Plugin_Validate extends Xi_Controller_Plugin_Outer implements Xi_Validate_Aggregate
{
    /**
     * @var Zend_Validate_Interface
     */
    protected $_validator;
    
    /**
     * @param Zend_Controller_Plugin_Abstract $plugin
     * @param Zend_Validate_Interface $validator
     * @return void
     */
    public function __construct(Zend_Controller_Plugin_Abstract $plugin, Zend_Validate_Interface $validator)
    {
        parent::__construct($plugin);
        $this->_validator = $validator;
    }
    
    /**
     * @return Zend_Validate_Interface $validator
     */
    public function getValidator()
    {
        return $this->_validator;
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function dispatchLoopStartup($request)
    {
        if (!$this->isValid($request)) {
            return;
        }
        return parent::dispatchLoopStartup($request);
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function postDispatch($request)
    {
        if (!$this->isValid($request)) {
            return;
        }
        return parent::postDispatch($request);
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function preDispatch($request)
    {
        if (!$this->isValid($request)) {
            return;
        }
        return parent::preDispatch($request);
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function routeShutdown($request)
    {
        if (!$this->isValid($request)) {
            return;
        }
        return parent::routeShutdown($request);
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function routeStartup($request)
    {
        if (!$this->isValid($request)) {
            return;
        }
        return parent::routeStartup($request);
    }
    
}
