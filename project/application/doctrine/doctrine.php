<?php
chdir(dirname(__FILE__));

require_once dirname(dirname(__FILE__)) . '/bootstrap.php';

$registry = Zend_Registry::getInstance();
$paths = $registry->config->paths->doctrine;

// Configure Doctrine Cli
// Normally these are arguments to the cli tasks but if they are set here the arguments will be auto-filled
$config = array('data_fixtures_path'  =>  $paths->fixtures,
                'models_path'         =>  $paths->models,
                'migrations_path'     =>  $paths->migrations,
                'sql_path'            =>  $paths->sql,
                'yaml_schema_path'    =>  $paths->schema);

$cli = new Doctrine_Cli($config);
$cli->run($_SERVER['argv']);
