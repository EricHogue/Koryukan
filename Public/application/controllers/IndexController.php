<?php
/**
 * Index controller
 */
class IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        error_reporting(-1);
        print date('Ymd');
        print '<br />' . date_default_timezone_get();

        print '<br />';


        $locale = Zend_Registry::get('locale');
        $language = $locale->getLanguage();
        $region = $locale->getRegion();

        print("<br />$language  -  $region");


    }
}