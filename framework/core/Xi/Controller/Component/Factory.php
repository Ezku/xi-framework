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

Xi_Loader::loadClass('Xi_Factory');

/**
 * @category    Xi
 * @package     Xi_Controller
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
abstract class Xi_Controller_Component_Factory extends Xi_Factory
{
    protected $_enableDefaultComponentClass = false;
    protected $_defaultComponentClass;
    protected $_componentName;

    public function init()
    {
        $this->actAs('cached');
    }

    public function create(Xi_Controller_Action $controller)
    {
        $class = $this->getComponentClass($controller);
        if (null === $class) {
            if (!$this->getEnableDefaultComponentClass()) {
                $message = sprintf('Could not find component for controller class %s', get_class($controller));
                throw new Xi_Controller_Exception($message);
            }
            $class = $this->getDefaultComponentClass();
        }

        if (!class_exists($class)) {
            $parameters = $this->getPathParameters($controller);
            $paths      = $this->getComponentPath($controller->getPaths(), $parameters);

            foreach ((array) $paths as $path) {
                if (is_readable($path)) {
                    require_once $path;
                    break;
                }
            }
            if (!class_exists($class)) {
                if (!$this->getEnableDefaultComponentClass()) {
                    $message = sprintf('Could not find definition for component class %s in %s', $class, implode(', ', (array) $paths));
                    throw new Xi_Controller_Exception($message);
                }
                $class = $this->getDefaultComponentClass();
            }
        }

        return $this->createComponent($class, $controller);
    }

    public function createComponent($class, $controller)
    {
        return new $class($controller);
    }

    public function getEnableDefaultComponentClass()
    {
        return $this->_enableDefaultComponentClass;
    }

    public function getDefaultComponentClass()
    {
        return $this->_defaultComponentClass;
    }

    /**
     * Get component class name
     *
     * @param Xi_Controller_Action
     * @return null|string
     */
    public function getComponentClass($controller)
    {
        $class = get_class($controller);
        if (0 === strpos($class, 'Xi_Controller_Action')) {
            return;
        }
        return str_replace('Controller', $this->getComponentName(), $class);
    }

    /**
     * Get component name (eg. 'View', 'Model')
     *
     * @return string
     */
    public function getComponentName()
    {
        if (null === $this->_componentName) {
            throw new Xi_Controller_Exception('Component name not set');
        }
        return $this->_componentName;
    }

    /**
     * Get path for component class definition
     *
     * @param Xi_Inflector
     * @param array
     * @return array|string
     */
    abstract public function getComponentPath($paths, $parameters);

    /**
     * Get path parameters to provide to {@link getComponentPath()}
     *
     * @param Xi_Controller_Action
     * @return array
     */
    public function getPathParameters($controller)
    {
        return array('controllerName' => $controller->getRequest()->getControllerName());
    }
}