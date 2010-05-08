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
}