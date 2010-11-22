<?php
/**
 * Plugin for languages
 */
class Koryukan_Controller_Plugin_LanguageSetup extends Zend_Controller_Plugin_Abstract
{
	protected $_languages;
	protected $_directory;

	/**
	 * @var string
	 */
	protected $_cookiesDomain;



	public function __construct($directory, $languages, $cookiesDomain)
	{
		$this->_dir = $directory;
		$this->_languages = $languages;
		$this->_cookiesDomain = $cookiesDomain;
	}

	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
	{
		$lang = $this->getRequest()->getParam('lang');
		if (!in_array($lang, array_keys($this->_languages))) {
			$lang = 'en';
		}

		$this->_persistUserPreferedLanguage($lang);

		$localeString = $this->_languages[$lang];
		$locale = new Zend_Locale($localeString);
		Zend_Locale::setDefault($localeString);
		$file = $this->_dir . '/'. $localeString . '.mo';


		if (!file_exists($file)) {
			throw new Exception('Missing $translationStrings in language file ' . $file);
		}

		$translate = new Zend_Translate('gettext', $file, $localeString);

		Zend_Registry::set('lang', $lang);
		Zend_Registry::set('localeString', $localeString);
		Zend_Registry::set('Zend_Locale', $locale);
		Zend_Registry::set('Zend_Translate', $translate);
	}

	/**
	 * Write the user choosed language in a cookie
	 *
	 * @return void
	 */
	private function _persistUserPreferedLanguage($language)
	{
		if (!in_array($language, array_keys($this->_languages))) {
			$language = 'en';
		}

		setcookie('language', $language, (time() + 3600 * 24 * 365), '/', $this->_cookiesDomain);
	}

}