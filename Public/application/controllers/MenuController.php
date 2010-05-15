<?php
/**
 * Menu controller
 */
class MenuController extends Zend_Controller_Action
{
    public function menuAction()
    {
        $lang = Zend_Registry::get('lang');

        $mainMenu = array(
            array('title'=>$this->view->translate('Home'),
                'url'=>$this->view->url(array('lang'=>$lang, 'controller'=>'index', 'action'=>'index'), null, true)),
            array('title'=>$this->view->translate('news'),
                'url'=>$this->view->url(array('lang'=>$lang, 'controller'=>'news','action'=>'index'),null, true)),
            array('title'=>$this->view->translate('daitoryu'),
                'url'=>$this->view->url(array('lang'=>$lang, 'controller'=>'daitoryu','action'=>'index'),null, true)),
            array('title'=>$this->view->translate('onohaittoryu'),
                'url'=>$this->view->url( array('lang'=>$lang, 'controller'=>'onohaittoryu','action'=>'index'), null, true)),
            array('title'=>$this->view->translate('articles'),
                'url'=>$this->view->url(array('lang'=>$lang, 'controller'=>'articles','action'=>'index'),null, true)),
            array('title'=>$this->view->translate('multimedia'),
                'url'=>$this->view->url(array('lang'=>$lang, 'controller'=>'multimedia','action'=>'index'),null, true)),
            array('title'=>$this->view->translate('children'),
                'url'=>$this->view->url(array('lang'=>$lang, 'controller'=>'children','action'=>'index'),null, true)),
            array('title'=>$this->view->translate('store'),
                'url'=>$this->view->url(array('lang'=>$lang, 'controller'=>'store','action'=>'index'),null, true)),
            array('title'=>$this->view->translate('contacts'),
                'url'=>$this->view->url(array('lang'=>$lang, 'controller'=>'contacts','action'=>'index'),null, true)),
        );

        if (0 === strcasecmp('en', $lang)) {
            $mainMenu[] = array('title'=>$this->view->translate('French', 'fr_CA'),
                'url'=>$this->view->url(array('lang'=>'fr'), null, false));
        } else {
            $mainMenu[] = array('title'=>$this->view->translate('English', 'en_US'),
                'url'=>$this->view->url(array('lang'=>'en'), null, false));
        }

        $this->view->menu = $mainMenu;
        $this->_helper->viewRenderer->setResponseSegment('menu');
    }
}