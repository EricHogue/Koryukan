<?php
/**
 * VanillaauthController Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Members
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-09-06
 *
 */

/**
 * Show the page used to authenticate users in Vanilla Forum
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Members
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class VanillaauthController extends Zend_Controller_Action
{
    /**
     * Index action
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_helper->layout->disableLayout();
        $this->view->assign('user', $this->_getLogedInUser());
    }

    /**
     * Return the loged in user
     *
     * @return Koryukan_Model_User
     */
    private function _getLogedInUser()
    {
        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) {
            return $auth->getIdentity();
        } else {
            return null;
        }
    }
}