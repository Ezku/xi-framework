<?php
Xi_Loader::loadClass('Xi_Factory');

/**
 * @category    Xi
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_Model
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Controller_Router_Factory extends Xi_Factory
{
    public function create($args)
    {
        $class  = $this->getOption('class', 'Zend_Controller_Router_Rewrite');
    	$router = Xi_Class::create($class, $args);

        if ($this->hasOption('routes')) {
            $router->addConfig($this->getOption('routes'), Xi_Environment::get());
        } elseif (isset($this->_locator['config.routes'])) {
        	$router->addConfig($this->_locator['config.routes'], Xi_Environment::get());
    	}
    	return $router;
    }

    public function mapCreationArguments($args)
    {
        return array($args);
    }
}
