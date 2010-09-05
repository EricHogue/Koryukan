<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
$environment = getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production';

if (array_key_exists('SERVER_NAME', $_SERVER) && 0 === strpos($_SERVER['SERVER_NAME'], 'staging')) {
    $environment = 'staging';
}

define('APPLICATION_ENV', $environment);

defined('BASE_PATH')
    || define('BASE_PATH', realpath(APPLICATION_PATH . '/../../Base'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(BASE_PATH . '/library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$config = array('config' => array(realpath(BASE_PATH . '/configs/application.ini'),
    realpath(APPLICATION_PATH . '/../configs/application.ini')));

$application = new Zend_Application(
    APPLICATION_ENV,
    $config
);

$application->bootstrap()
            ->run();

//print nl2br(print_r($application->getOptions(), true));
//echo '<br />APPLICATION_ENV: ' . APPLICATION_ENV . '<br />';
