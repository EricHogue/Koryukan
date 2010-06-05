<?php
/**
 * OnohaittoryuController Class File
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
 * onohaittoryu
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Public
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class OnohaittoryuController extends Zend_Controller_Action
{
    /**
     * Index action
     *
     * @return void
     */
    public function indexAction()
    {
        $pageTitle = $this->view->translate('Ono Ha Itto Ryu Kenjutsu');
        $this->view->assign('pageTitle', $pageTitle);
        $this->view->assign('sideImage', 'onohaittoryu.jpg');
        $this->view->assign('sideImageAlt', $pageTitle);
    }
}