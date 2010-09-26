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
            $response['rows'][$index]['id'] = $user->getId();
            $response['rows'][$index]['cell'] = array($user->getId(), $user->getUsername(), $user->getFirstName(),
                $user->getLastName(), $user->getEmail(), $user->getStatus());
        }

        $jsonData = Zend_Json::encode($response);
        $this->getResponse()
            ->setHeader('Content-Type', 'text/json')
            ->setBody($jsonData);

        $this->getHelper('viewRenderer')->setNoRender();
        $this->_helper->layout->disableLayout();
    }

}