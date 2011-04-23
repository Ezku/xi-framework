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
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_Front
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Controller_Front_Factory extends Xi_Factory
{
    public function init()
    {
        $this->actAs('singleton');
    }

	public function create()
	{
		$fc = Xi_Controller_Front::getInstance();

		foreach (array('setRequest'    => 'controller.request',
		               'setResponse'   => 'controller.response',
		               'setRouter'     => 'controller.router',
		               'setDispatcher' => 'controller.dispatcher') as $method => $resource) {
			if (isset($this->_locator[$resource])) {
				$fc->$method($this->_locator[$resource]);
			}
		}

        /**
         * Layout
         */
        $options = $this->_locator->config->paths->layout->toArray();
        $this->_locator->controller->layout = Zend_Layout::startMvc($options);

        /**
         * Plugins
         */

        $plugins = $this->_locator['controller.plugins'];
        $callback = array($fc, 'registerPlugin');
		if (isset($plugins)) {
			foreach ($plugins as $plugin) {
                call_user_func_array($callback,
                                     is_array($plugin) ? $plugin : array($plugin));
			}
		}

        /**
         * Defaults
         */
        $params = $this->_locator['config.params'];
        foreach (array('setDefaultModule'         => 'defaultModuleName',
                       'setDefaultControllerName' => 'defaultControllerName',
                       'setDefaultAction'         => 'defaultActionName') as $method => $key) {
            if (isset($params->$key)) {
                $fc->$method($params->$key);
            }
        }

        $fc->setParams($params->toArray());
        $fc->throwExceptions($params->get('throwExceptions', true));

		return $fc;
	}
}
