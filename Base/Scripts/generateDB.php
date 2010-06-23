<?php

if (array_key_exists(1, $argv)) {
    $env = $argv[1];
} else {
    $env = 'production';
}

defined('BASE_PATH')
    || define('BASE_PATH', realpath(dirname(__FILE__) . '/..'));


// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(BASE_PATH . '/library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$config = array('config' => array(realpath(BASE_PATH . '/configs/application.ini')));
$application = new Zend_Application($env, $config);


$dbConnection = $application->getBootstrap()->bootstrap('Doctrine');
try {
    Doctrine_Core::dropDatabases();
} catch (Doctrine_Connection_Mysql_Exception $e) {
    error_log('The database did not exists');
}
Doctrine_Core::createDatabases();


$options = $application->getOptions();
$ymlPath = realpath(BASE_PATH . '/Scripts/Yaml/Schema/Koryukan.yml');

$modelsOptions = array(
    'suffix'          =>  '.php',
    'generateTableClasses' => true,
    'classPrefix' => 'Koryukan_Db_',
    'classPrefixFiles' => false,
    'baseClassPrefix' => 'Base',
    'baseClassesDirectory' => ''
);

Doctrine_Core::generateModelsFromYaml($ymlPath, $options['db']['objectsPath'], $modelsOptions);
Doctrine_Core::createTablesFromModels($options['db']['objectsPath']);

Doctrine_Core::loadData(BASE_PATH . '/Scripts/Yaml/Data/news.yml');
Doctrine_Core::loadData(BASE_PATH . '/Scripts/Yaml/Data/storeItems.yml');
Doctrine_Core::loadData(BASE_PATH . '/Scripts/Yaml/Data/images.yml');
Doctrine_Core::loadData(BASE_PATH . '/Scripts/Yaml/Data/users.yml');

