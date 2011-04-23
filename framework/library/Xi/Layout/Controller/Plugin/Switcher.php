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
 * Switches between layouts based on request parameters
 * 
 * @category    Xi
 * @package     Xi_Layout
 * @subpackage  Xi_Layout_Controller
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Layout_Controller_Plugin_Switcher extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var array
     */
    protected $_validators = array();
    
    /**
     * @var Zend_Layout
     */
    protected $_layout;
    
    /**
     * @param Zend_Config $config
     * @param Zend_Layout $layout
     * @return void
     */
    public function __construct(Zend_Config $config, Zend_Layout $layout = null)
    {
        $this->_layout = $layout;
        $validators = array();
        foreach ($config as $layout => $requirements) {
            if (is_scalar($requirements)) {
                $validators[$layout] = new Xi_Validate_Request($requirements);
            } else {
                $args = array(
                    'module' => $requirements->module,
                    'controller' => $requirements->controller,
                    'action' => $requirements->action,
                    'params' => $requirements->get('params', array())
                );
                
                foreach ($args as &$arg) {
                    if ($arg instanceof Zend_Config) {
                        $arg = $arg->toArray();
                    }
                }
                
                $validators[$layout] = new Xi_Validate_Request(
                    $args['module'],
                    $args['controller'],
                    $args['action'],
                    $args['params']
                );
            }
        }
        $this->_validators = $validators;
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        foreach ($this->_validators as $layout => $validator) {
            if ($validator->isValid($request)) {
                $this->getLayout()->setLayout($layout);
                break;
            }
        }
    }
    
    /**
     * @return Zend_Layout
     */
    public function getLayout()
    {
        if (null === $this->_layout) {
            $this->_layout = $this->getDefaultLayout();
        }
        return $this->_layout;
    }
    
    /**
     * @return Zend_Layout
     */
    public function getDefaultLayout()
    {
        return Zend_Layout::getMvcInstance();
    }
}
