<?php
/**
 * News controller
 */
class NewsController extends Zend_Controller_Action
{
    public function indexAction() {
        $newsCollection = Koryukan_Model_News::getNews();

        $pageTitle = $this->view->translate('NewsPageTitle');
        $this->view->assign('pageTitle', $pageTitle);
        $this->view->assign('sideImage', 'Nouvelles.png');
        $this->view->assign('sideImageAlt', $pageTitle);
        $this->view->assign('newsCollection', $newsCollection);
        $this->view->assign('lang', $this->getRequest()->getParam('lang', 'en'));
    }
}