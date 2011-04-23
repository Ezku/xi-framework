<?php
$frameworkDirectory     = Xi_Environment::getFrameworkDirectory() . DIRECTORY_SEPARATOR;
$applicationDirectory   = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$projectDirectory       = dirname($applicationDirectory) . DIRECTORY_SEPARATOR;

$frameworkConfigDirectory   = $frameworkDirectory . 'config' . DIRECTORY_SEPARATOR;
$projectConfigDirectory     = $projectDirectory . 'config' . DIRECTORY_SEPARATOR;
$applicationConfigDirectory = $applicationDirectory . 'config' . DIRECTORY_SEPARATOR;

$cacheDirectory     = $applicationDirectory . 'data' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
$cacheTimeToLive    = 3600;

return array(
    'dir' => array(
        'framework'    => $frameworkDirectory,
        'project'      => $projectDirectory,
        'application'  => $applicationDirectory,
        'cache'        => $cacheDirectory
    ),

    'config' => array(
        'framework'     => array('directory' => $frameworkConfigDirectory),
        'project'       => array('directory' => $projectConfigDirectory, 'extension' => '.yml'),
        'application'   => array('directory' => $applicationConfigDirectory, 'extension' => '.yml')
    ),

    'cache' => array(
        'debug' => false,

        'class' => array(
            'enabled' => Xi_Environment::is(Xi_Environment::ENV_PRODUCTION),
            'path' => $cacheDirectory . 'classes.dat',
            'namespaces' => array('Xi', 'Doctrine'),
            'timeToLive' => $cacheTimeToLive
        ),
        'startup' => array(
            'enabled' => Xi_Environment::is(Xi_Environment::ENV_PRODUCTION),
            'path' => $cacheDirectory . 'startup.dat',
            'timeToLive' => $cacheTimeToLive
        ),
        'config' => array(
            'enabled' => Xi_Environment::is(Xi_Environment::ENV_PRODUCTION),
            'path' => $cacheDirectory . 'config.dat',
            'timeToLive' => $cacheTimeToLive
        ),
    ),

    'startupJobs' => array(
        'Xi_Bootstrap_Locator',
        'Xi_Bootstrap_Paths',
        'Xi_Bootstrap_Database',
        'Xi_Bootstrap_Doctrine',
        'Xi_Bootstrap_Settings',
        'Xi_Bootstrap_Controllers',
     )
);