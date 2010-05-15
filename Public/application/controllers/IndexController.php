<?php
/**
 * Index controller
 */
class IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $pageTitle = $this->view->translate('Welcome to Koryukan');
        $this->view->assign('pageTitle', $pageTitle);
        $this->view->assign('sideImage', 'medhat.jpg');
        $this->view->assign('sideImageAlt', $pageTitle);
    }
}