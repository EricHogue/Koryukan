<?php
/**
 * News controller
 */
class NewsController extends Zend_Controller_Action
{
    public function indexAction() {
        $pageTitle = $this->view->translate('NewsPageTitle');
        $this->view->assign('pageTitle', $pageTitle);
        $this->view->assign('sideImage', 'Nouvelles.png');
        $this->view->assign('sideImageAlt', $pageTitle);
    }
}