<?php
/**
 * ChildrenController Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Public
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-06-05
 *
 */

/**
 * Children page
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Public
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class ChildrenController extends Zend_Controller_Action
{
    /**
     * Index Action
     *
     * @return void
     */
    public function indexAction()
    {
        $pageTitle = $this->view->translate('Children - Family');
        $this->view->assign('pageTitle', $pageTitle);
        $this->view->assign('sideImage', 'Children.jpg');
        $this->view->assign('sideImageAlt', $pageTitle);
    }
}