<?php
/**
 * ArticlesController Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Public
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-06-04
 *
 */

/**
 * Articles
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Public
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class ArticlesController extends Zend_Controller_Action
{
    /**
     * Init
     *
     * @return void
     */
    public function init()
    {
        $view = $this->view;
        $submenu = array(
            array('name' => $view->translate('September 2005 Training Camp Summary'), 'action' => 'september_2005_training_camp_summary'),
            array('name' => $view->translate('The Benefits of AaOoMm'), 'action' => 'the_benefits_of_aaOoMm'),
            array('name' => $view->translate('Interview with Okabayashi Sensei'), 'action'  => 'interview_with_okabayashi_sensei'),
            array('name' => $view->translate('Training In Japan'), 'action' => 'training_in_japan'),
            array('name' => $view->translate('Body Movements, by Russel Haskin'), 'action' => 'body_movements_by_russel_haskin')
        );
        $view->assign('submenu', $submenu);
        $view->assign('sideImage', 'Articles.jpg');
    }

    /**
     * Show the trainning camp summary
     *
     * @return void
     */
    public function september2005trainingcampsummaryAction()
    {
        $pageTitle = $this->view->translate('September 2005 Training Camp Summary');
        $this->view->assign('pageTitle', $pageTitle);
        $this->view->assign('sideImageAlt', $pageTitle);
    }

    /**
     * Aoooum action
     *
     * @return void
     */
    public function thebenefitsofaaoommAction()
    {
        $pageTitle = $this->view->translate('The Benefits of AaOoMm');
        $this->view->assign('pageTitle', $pageTitle);
        $this->view->assign('sideImageAlt', $pageTitle);
    }

    /**
     * Interview with sensei action
     *
     * @return void
     */
    public function interviewwithokabayashisenseiAction()
    {
        $pageTitle = $this->view->translate('Interview with Okabayashi Sensei');
        $this->view->assign('pageTitle', $pageTitle);
        $this->view->assign('sideImageAlt', $pageTitle);
    }

    /**
     * Training in japan
     *
     * @return void
     */
    public function traininginjapanAction()
    {
        $pageTitle = $this->view->translate('Training In Japan');
        $this->view->assign('pageTitle', $pageTitle);
        $this->view->assign('sideImageAlt', $pageTitle);
    }

    /**
     * Body movements
     *
     * @return void
     */
    public function bodymovementsbyrusselhaskinAction()
    {
        $pageTitle = $this->view->translate('Body Movements, by Russel Haskin');
        $this->view->assign('pageTitle', $pageTitle);
        $this->view->assign('sideImageAlt', $pageTitle);
    }
}