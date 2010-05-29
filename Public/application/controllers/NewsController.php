<?php
/**
 * News controller
 */
class NewsController extends Zend_Controller_Action
{
    public function indexAction() {
        $news = Doctrine_Core::getTable('Koryukan_Db_News')->getNews('online');

        $col = new Koryukan_Helper_Collection($news);


        foreach ($col as $a) {
            echo $a['id'] . '<br />';
            foreach ($a['NewsText'] as $b) {
                echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $b['language'] . '<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $b['title'] . '<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $b['content'] . '<br />';
            }
        }

        //echo nl2br(print_r($news->toArray(), true));

        $pageTitle = $this->view->translate('NewsPageTitle');
        $this->view->assign('pageTitle', $pageTitle);
        $this->view->assign('sideImage', 'Nouvelles.png');
        $this->view->assign('sideImageAlt', $pageTitle);
    }
}