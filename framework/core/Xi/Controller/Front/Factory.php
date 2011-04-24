<?php
/**
 * @category    Xi
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_Front
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
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
