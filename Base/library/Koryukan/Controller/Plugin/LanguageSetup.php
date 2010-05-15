<?php
/**
 * Plugin for languages
 */
class Koryukan_Controller_Plugin_LanguageSetup extends Zend_Controller_Plugin_Abstract
{
    protected $_languages;
    protected $_directory;


    public function __construct($directory, $languages)
    {
        $this->_dir = $directory;
        $this->_languages = $languages;
    }

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $lang = $this->getRequest()->getParam('lang');
        if (!in_array($lang, array_keys($this->_languages))) {
            $lang = 'en';
        }

        $localeString = $this->_languages[$lang];
        $locale = new Zend_Locale($localeString);
        $file = $this->_dir . '/'. $localeString . '.php';

        if (file_exists($file)) {
            $translationStrings = include $file;
        } else {
            $translationStrings = include $this->_dir . '/en_US.php';
        }

        if (empty($translationStrings)) {
            throw new Exception('Missing $translationStrings in language file');
        }

        $translate = new Zend_Translate('array', $translationStrings, $localeString);

        Zend_Registry::set('lang', $lang);
        Zend_Registry::set('localeString', $localeString);
        Zend_Registry::set('locale', $locale);
        Zend_Registry::set('Zend_Translate', $translate);
    }

}