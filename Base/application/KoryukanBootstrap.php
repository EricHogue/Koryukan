<?php
/**
 * Bootstrap for koryukan
 */
class KoryukanBootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initTimezone()
    {
        $date = $this->getOption('date');
        if (isset($date) && array_key_exists('timezone', $date)) {
            date_default_timezone_set($date['timezone']);
        }
    }

    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }


    protected function _initLanguageRoute() {
        $config = $this->getOptions();
        $languages = array_keys($config['languages']);

        $zl = new Zend_Locale();
        $lang = in_array($zl->getLanguage(), $languages)? $zl->getLanguage() : 'en';

        $frontController = Zend_Controller_Front::getInstance();
        $route = new Zend_Controller_Router_Route(
            ':lang/:controller/:action/*',
            array('controller'=>'index',
                'action' => 'index',
                'module'=>'default',
                'lang'=>$lang)
        );

        $router = $frontController->getRouter();
        $router->addRoute('default', $route);
        $frontController->setRouter($router);
    }

    protected function _initAutoLoad() {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('Koryukan_');
    }

    protected function _initLanguagePlugin() {
        $this->bootstrap('AutoLoad');

        $config = $this->getOptions();
        $languages = $config['languages'];
        $languagePath = $config['languagePath'];

        $plugin = new Koryukan_Controller_Plugin_LanguageSetup($languagePath, $languages);

        $this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');
        $frontController->registerPlugin($plugin);
    }

    protected function _initZFDebug()
    {
        $config = $this->getOptions();
        if (array_key_exists('enable_zf_debug', $config) && true === (bool) $config['enable_zf_debug']) {
            $autoloader = Zend_Loader_Autoloader::getInstance();
            $autoloader->registerNamespace('ZFDebug');

            $options = array(
                'plugins' => array('Variables',
                                   'File' => array('base_path' => realpath(APPLICATION_PATH . '/../..')),
                                   'Memory',
                                   'Time',
                                   'Registry',
                                   'Exception')
            );

            /*# Instantiate the database adapter and setup the plugin.
            # Alternatively just add the plugin like above and rely on the autodiscovery feature.
            if ($this->hasPluginResource('db')) {
                $this->bootstrap('db');
                $db = $this->getPluginResource('db')->getDbAdapter();
                $options['plugins']['Database']['adapter'] = $db;
            }*/

            # Setup the cache plugin
            $this->bootstrap('mainCache');
            $cache = $this->getResource('mainCache');
            $options['plugins']['Cache']['backend'] = $cache->getBackend();

            $debug = new ZFDebug_Controller_Plugin_Debug($options);

            $this->bootstrap('frontController');
            $frontController = $this->getResource('frontController');
            $frontController->registerPlugin($debug);
        }
    }

    protected function _initMainCache()
    {
        $config = $this->getOptions();

        $cacheTtl = 0;
        if (array_key_exists('cache', $config) && array_key_exists('main', $config['cache'])) {
            $mainCacheOptions = $config['cache']['main'];
             $cacheTtl = (array_key_exists('ttl', $mainCacheOptions))? (int) $mainCacheOptions['ttl']: 30;
             $cachIdPrefix = (array_key_exists('cache_id_prefix', $mainCacheOptions))? $mainCacheOptions['cache_id_prefix']: '';
        }

        $frontendOptions = array(
            'caching' => true,
            'cache_id_prefix' => $cachIdPrefix,
            'lifetime' => $cacheTtl,
            'automatic_serialization' => true
        );

        // getting a Zend_Cache_Frontend_Page object
        $cache = Zend_Cache::factory('Core', 'Apc', $frontendOptions);

        return $cache;
    }

    protected function _initPageCache()
    {
        $config = $this->getOptions();

        $cachePages = false;
        $cacheTtl = 0;
        $showDebugHeader = false;
        if (array_key_exists('cache', $config) && array_key_exists('page', $config['cache'])) {
            $pageCacheOptions = $config['cache']['page'];
             $cachePages = (array_key_exists('enabled', $pageCacheOptions))? (bool) $pageCacheOptions['enabled']: false;
             $cacheTtl = (array_key_exists('ttl', $pageCacheOptions))? (int) $pageCacheOptions['ttl']: 0;
             $showDebugHeader = (array_key_exists('debug_header', $pageCacheOptions))? (bool) $pageCacheOptions['debug_header']: 0;
        }

        if ($cacheTtl < 1) {
            $cachePages = false;
        }

        $frontendOptions = array(
            'lifetime' => $cacheTtl,
            'debug_header' => $showDebugHeader, // for debugging
            'default_options' => array(
                'cache' => $cachePages,
                'cache_with_cookie_variables' => true
            )
        );

        // getting a Zend_Cache_Frontend_Page object
        $cache = Zend_Cache::factory('Page', 'Apc', $frontendOptions);

        $cache->start();

        return $cache;
    }
}