<?php
try {
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'staging'));

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

Zend_Session::writeClose(true);

} catch (Exception $e) {
    /*echo $e->getCode() . '<br />';
    echo $e->getMessage() . '<br />';
    echo $e->getFile() . '<br />';
    echo $e->getLine() . '<br />';
    echo $e->getTraceAsString() . '<br />';*/
}