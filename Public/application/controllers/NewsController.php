<?php
/**
 * News controller
 */
class NewsController extends Zend_Controller_Action
{
    public function indexAction() {
        $news = Doctrine_Core::getTable('Koryukan_Model_News')->findAll();

        echo nl2br(print_r($news->toArray(), true));


        $pageTitle = $this->view->translate('NewsPageTitle');
        $this->view->assign('pageTitle', $pageTitle);
        $this->view->assign('sideImage', 'Nouvelles.png');
        $this->view->assign('sideImageAlt', $pageTitle);
    }
}