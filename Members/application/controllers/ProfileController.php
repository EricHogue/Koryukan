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
    	$response = array('success' => false);

        $request = $this->getRequest();
        $username = $request->getParam('username', '');

        if ($this->_isCurrentLogedInUser($username)) {
        	$user = Koryukan_Model_User::getByUsername($username);

	        $user->setFirstName($request->getParam('firstName', ''));
	        $user->setLastName($request->getParam('lastName', ''));
	        $user->setEmail($request->getParam('email', ''));


	        $validator = new KoryukanMembers_UserValidator($this->view);
        	$isValid = $validator->isValid($user);

        	if ($isValid) {
	        	$user->save();
	        	$response['success'] = true;
	        	$response['messages'] = array($this->view->translate('Your profile has been updated') => "\n");
        	} else {
        		$response['messages'] = $validator->getMessages();
        	}
        }

        $response['title'] = $this->view->translate('Profile Update');


        $this->getResponse()
            ->setHeader('Content-Type', 'text/json')
            ->setBody(Zend_Json::encode($response));
        $this->getHelper('viewRenderer')->setNoRender();
        $this->_helper->layout->disableLayout();
    }

    /**
     * Make sure we are updating the curent user
     *
     * @return boolean
     */
    private function _isCurrentLogedInUser($username)
    {
        return (0 === strcasecmp($username, Zend_Auth::getInstance()->getIdentity()->getUsername()));
    }
}