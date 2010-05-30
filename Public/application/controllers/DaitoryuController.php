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