<?php
Xi_Loader::loadClass('Xi_Factory');

/**
 * @category    Xi
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_Plugin
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Controller_Plugin_ErrorHandler_Factory extends Xi_Factory
{
    public function create($config)
    {
        $defaults = array(
            'module'     => isset($config->errorHandlerModule)     ? $config->errorHandlerModule     : 'default',
            'controller' => isset($config->errorHandlerController) ? $config->errorHandlerController : 'error',
            'action'     => isset($config->errorHandlerAction)     ? $config->errorHandlerAction     : 'error'
        );

        $handler = new Zend_Controller_Plugin_ErrorHandler($defaults);
        return array($handler, 100);
    }
}

