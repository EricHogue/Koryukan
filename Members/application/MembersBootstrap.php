<?php

require_once BASE_PATH . '/application/KoryukanBootstrap.php';

/**
 * Bootstrap for the members koryukan site
 */
class MembersBootstrap extends KoryukanBootstrap
{
    protected function _initMembersAutoLoad() {
        $this->bootstrap('AutoLoad');
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('KoryukanMembers_');
    }

    protected function _initAccessControlPlugin()
    {
        $this->bootstrap('MembersAutoLoad');
        $this->bootstrap('frontController');

        $frontController = $this->getResource('frontController');
        $frontController->registerPlugin(new KoryukanMembers_Controller_Plugin_AccessControl(), 98);
    }

}