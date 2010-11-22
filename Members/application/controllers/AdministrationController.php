<?php
/**
 * AdministrationController Class File
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
 * Administration controller
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Members
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class AdministrationController extends Zend_Controller_Action
{
    /**
     * User manager
     *
     * @return void
     */
    public function usersAction()
    {
        $this->view->assign('lang', Zend_Registry::get('lang'));
    }

    /**
     * Return the list of users
     *
     * @return void
     */
    public function getusersAction()
    {
        $userList = Koryukan_Model_User::getAllUsers();
        $count = $userList->count();

        $response = array();
        $response['page'] = 1;
        $response['total'] = 1;
        $response['records'] = $count;

        $index = 0;
        foreach ($userList as $user) {
            $group = $user->getGroups()->current();
            $groupText = $this->view->translate($group->getName());

            $response['rows'][$index]['id'] = $user->getId();
            $response['rows'][$index]['cell'] = array($user->getId(), $user->getUsername(), $user->getFirstName(),
                $user->getLastName(), $user->getEmail(), $this->view->translate($user->getStatus()), $groupText);
            $index++;
        }

        $jsonData = Zend_Json::encode($response);
        $this->getResponse()
            ->setHeader('Content-Type', 'text/json')
            ->setBody($jsonData);

        $this->getHelper('viewRenderer')->setNoRender();
        $this->_helper->layout->disableLayout();
    }

    /**
     * Edit a user
     *
     * @return void
     */
    public function edituserAction()
    {
        $request = $this->getRequest();

        $operation = $request->getParam('oper', 'edit');
        if (0 === strcasecmp('add', $operation)) {
            $isNew = true;
        } else {
            $isNew = false;
        }

        if ($isNew) {
            $user = new Koryukan_Model_User();
            $user->setUsername($request->getParam('username'));
        } else {
            $user = Koryukan_Model_User::getByUsername($request->getParam('username'));
        }

        $user->setFirstName($request->getParam('firstName'));
        $user->setLastName($request->getParam('lastName'));
        $user->setEmail($request->getParam('email'));
        $user->setStatus($request->getParam('status'));
        $user->addGroup($request->getParam('group'));

        if ($isNew) {
            $user->setPassword($request->getParam('password'));
        }

        $validator = new KoryukanMembers_UserValidator($this->view);
        $isValid = $validator->isValid($user);

        if ($isValid) {
        	$user->save();
        }

        $this->getResponse()
            ->setHeader('Content-Type', 'text/json')
            ->setBody('');

        $this->getHelper('viewRenderer')->setNoRender();
        $this->_helper->layout->disableLayout();
    }

}