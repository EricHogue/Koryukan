<?php
/**
 * MenuController Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Members
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-08-29
 *
 */

/**
 * Menu builder
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Members
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class MenuController extends Zend_Controller_Action
{
    /**
     * Menu action
     *
     * @return void
     */
    public function menuAction()
    {
        $connectedUser = $this->_getUser();
        if (true !== isset($connectedUser)) $mainMenu = array();
        else {
            $menuBuilder = new KoryukanMembers_MenuBuilder(Zend_Registry::get('acl'), $connectedUser,
                Zend_Registry::get('lang'), $this->view);
            $mainMenu = $menuBuilder->buildMenu();
        }

        $this->view->menu = $mainMenu;
        $this->_helper->viewRenderer->setResponseSegment('menu');
        $this->view->lang = Zend_Registry::get('lang');
    }

    /**
     * Get user
     *
     * @return Koryukan_Model_User
     */
    private function _getUser()
    {
        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) return $auth->getIdentity();
        else return null;
    }
}