<?php
/**
 * LoginController Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Members
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-06-20
 *
 */

/**
 * Login controller
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Members
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class LoginController extends Zend_Controller_Action
{
    /**
     * The username
     *
     * @var string
     */
    protected $_username = '';

    /**
     * The password
     *
     * @var string
     */
    protected $_password = '';


    /**
     * Index action
     *
     * @return void
     */
    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            $this->_username = $this->getRequest()->getParam('username', '');
            $this->_password = $this->getRequest()->getParam('password', '');

            if ($this->_checkLoginInfo()) {
                $lang = $this->getRequest()->getParam('lang', 'en');
                $this->_helper->redirector->gotoSimple('index', 'index', null, array('lang' => $lang));
            }
        }

        $this->view->assign('username', $this->_username);
        $this->view->assign('password', $this->_password);
    }

    /**
     * Logout the user
     *
     * @return void
     */
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_forumLogout();

        $lang = $this->getRequest()->getParam('lang', 'en');
        $this->_helper->redirector->gotoSimple('index', 'index', null, array('lang' => $lang));
    }

    /**
     * Verify the login info
     *
     * @return bool
     */
    private function _checkLoginInfo()
    {
        $adapter = new KoryukanMembers_AuthAdapter($this->_username, $this->_password);
        $authResult = Zend_Auth::getInstance()->authenticate($adapter);

        return $authResult->isValid();
    }

    /**
     * Logout of the forum
     *
     * @return void
     */
    private function _forumLogout()
    {
        $config        = Zend_Registry::get('config');
        $cookiesDomain = $config->get('cookiesDomain');

        setcookie('Vanilla', ' ', time() - 3600, '/', '.' . $cookiesDomain);
        unset($_COOKIE['Vanilla']);
    }
}