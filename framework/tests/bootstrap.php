<?php
/**
 * Set current directory
 */
chdir(dirname(__FILE__));

/**
 * Maximum level error reporting
 */
error_reporting(E_ALL | E_STRICT);

/**
 * Set up include path
 */
set_include_path(
    dirname(__FILE__) . PATH_SEPARATOR .
    dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'core' . PATH_SEPARATOR .
    dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'library' . PATH_SEPARATOR .
    dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor'
);

/**
 * Set up autoloading
 */
require_once 'Xi/Loader.php';
spl_autoload_register(array('Xi_Loader', 'autoload'));
