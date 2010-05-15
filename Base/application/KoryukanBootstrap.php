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

        $frontController = Zend_Controller_Front::getInstance();
        $frontController->registerPlugin($plugin);
    }



    protected function _initTranslation() {
$english = array('message1' => 'message1',
                 'message2' => 'message2',
                 'message3' => 'message3');
$french = array('message1' => 'premier',
                'message2' => 'deuxieme',
                'message3' => 'troisieme');

$translate = new Zend_Translate('array', $english, 'en');
$translate->addTranslation($french, 'fr');


Zend_Controller_Router_Route::setDefaultTranslator($translate);

/*
$router = Zend_Controller_Front::getInstance()->getRouter();
$langRoute = new Zend_Controller_Router_Route(
        ':lang/',
        array(
            'lang' => 'en'
        )
    );

    $defaultRoute = new Zend_Controller_Router_Route(
        ':controller/:action',
        array(
            'module'=>'default',
            'controller'=>'index',
            'action'=>'index'
        )
    );
     $defaultRoute = $langRoute->chain($defaultRoute);

$router->addRoute('langRoute', $langRoute);
 $router->addRoute('defaultRoute', $defaultRoute);

print_r(Zend_Controller_Front::getInstance()->getParams());
 echo $translate->translate('message1');
 */
    }
}