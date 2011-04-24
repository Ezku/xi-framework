<?php
/**
 * This is the Xi bootstrap file used to launch the framework from the web.
 *
 * @category    Xi_App
 * @package     Xi_Bootstrap
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */


$root = dirname(dirname(__FILE__));
require_once $root .'/framework/core/Xi/Environment.php';

try {
    Xi_Environment::set(Xi_Environment::ENV_PRODUCTION);
    require_once $root . '/project/application/bootstrap.php';
    Zend_Registry::getInstance()->controller->front->dispatch();
} catch (Exception $e) {

    header('HTTP/1.1 500 Internal Server Error');
    echo '<h1>Internal server error</h1><p>An internal server error occurred while processing this page.</p>';

}

