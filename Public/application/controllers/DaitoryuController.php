<?php
/**
 * DaitoryuController Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Public
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-05-29
 *
 */

/**
 * Daito ryu controller
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Public
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class DaitoryuController extends Zend_Controller_Action
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
            array('name' => $view->translate('History of Daito Ryu'), 'action' => 'history_of_daitoryu'),
            array('name' => $view->translate('Takeda Sokaku'), 'action' => 'takeda_sokaku'),
            array('name' => $view->translate('Takeda Tokimune'), 'action'  => 'takeda_tokimune'),
            array('name' => $view->translate('Hisa Takuma'), 'action' => 'hisa_takuma')
        );
        $view->assign('submenu', $submenu);
    }

    /**
     * Index action
     *
     * @return void
     */
    public function historyofdaitoryuAction()
    {
        $pageTitle = $this->view->translate('HistoryOfDaitoRyuPageTitle');
        $this->view->assign('pageTitle', $pageTitle);
        $this->view->assign('sideImage', 'sokaku.jpg');
        $this->view->assign('sideImageAlt', $pageTitle);
    }
}