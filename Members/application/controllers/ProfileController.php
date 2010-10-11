<?php
/**
 * ProfileController Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Members
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-10-11
 *
 */

/**
 * Manage users profile
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Members
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class ProfileController extends Zend_Controller_Action
{
    /**
     * Init
     *
     * @return void
     */
    public function init()
    {
        $this->view->assign('lang', Zend_Registry::get('lang'));
    }

    /**
     * Show the user profile
     *
     * @return void
     */
    public function indexAction()
    {
        $this->view->assign('user', Zend_Auth::getInstance()->getIdentity());
    }

    /**
     * Save the user profile changes
     *
     * @return void
     */
    public function saveAction()
    {
        $this->getHelper('viewRenderer')->setNoRender();
        $this->_helper->layout->disableLayout();
    }
}