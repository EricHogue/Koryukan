<?php

require_once BASE_PATH . '/application/KoryukanBootstrap.php';

/**
 * Bootstrap for the public koryukan site
 */
class PublicBootstrap extends KoryukanBootstrap
{

    protected function _initActionSetupPlugin()
    {
        $this->bootstrap('PublicAutoLoad');
        $this->bootstrap('frontController');
        $this->bootstrap('LanguagePlugin');

        $frontController = $this->getResource('frontController');
        $frontController->registerPlugin(new KoryukanPublic_Controller_Plugin_ActionSetup(), 98);
    }

    protected function _initPublicAutoLoad() {
        $this->bootstrap('AutoLoad');
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('KoryukanPublic_');
    }
}