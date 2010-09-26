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
        $frontController->registerPlugin(new KoryukanMembers_Controller_Plugin_AccessControl(), 97);
    }

    /**
     * Initialize the Acl
     *
     * @return void
     */
    protected function _initAcl()
    {
        $this->bootstrap('Doctrine');
        $this->bootstrap('MainCache');
        $this->bootstrap('ZFDebug');

        $mainCache = $this->getResource('MainCache');
        $cacheKey = 'KoryukanMembersAcl';

        if ($mainCache->test($cacheKey)) {
            $aclString = $mainCache->load($cacheKey);
            $acl = unserialize($aclString);
        } else {
            $acl = new KoryukanMembers_Acl();
            $aclString = serialize($acl);
            $mainCache->save($aclString, $cacheKey);
        }

        Zend_Registry::set('acl', $acl);

        return $acl;
    }

    protected function _initActionSetupPlugin()
    {
        $this->bootstrap('MembersAutoLoad');
        $this->bootstrap('frontController');

        $frontController = $this->getResource('frontController');
        $frontController->registerPlugin(new KoryukanMembers_Controller_Plugin_ActionSetup(), 96);
    }

    /**
     * Init the session
     *
     * @return void
     */
    protected function _initSession()
    {
        $config        = $this->getOptions();
        $cookiesDomain = $config['cookiesDomain'];
        $cookiesName   = $config['cookieName'];

        Zend_Session::setOptions(array('cookie_domain' => $cookiesDomain, 'name' => $cookiesName));
    }

}