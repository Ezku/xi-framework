<?php
/**
 * This is the Xi bootstrap file used to launch the framework from the web.
 *
 * @category    Xi_App
 * @package     Xi_Bootstrap
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */

/**
 * Maximum level error reporting
 */
error_reporting(E_ALL | E_STRICT);

$root = dirname(dirname(__FILE__));
require_once $root .'/framework/core/Xi/Environment.php';

try {
    Xi_Environment::set(Xi_Environment::ENV_DEVELOPMENT);
    require_once $root . '/project/application/bootstrap.php';
    Zend_Registry::getInstance()->controller->front->dispatch();
} catch (Exception $e) {
    echo Xi_Debug::applyPreTags($e->__toString());
}

