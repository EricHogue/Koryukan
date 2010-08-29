<?php
/**
 * KoryukanMembers_Controller_Plugin_AccessControl Class File
 *
 * @category   PHP5
 * @package    package
 * @subpackage subPackage
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-06-13
 *
 */

/**
 * classDescription
 *
 * @category   PHP5
 * @package    package
 * @subpackage subPackage
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class KoryukanMembers_Controller_Plugin_AccessControl extends Zend_Controller_Plugin_Abstract
{
    /**
     * Make the user is loged in and has access
     *
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) {
            $user = $auth->getIdentity();
            $this->_validatePermission($user);
        } else {
            $request->setControllerName('Login');
            $request->setActionName('index');
        }
    }

    /**
     * Post dispatched
     *
     * @return void
     */
    public function postDispatch($request)
    {
        //print_r($this->getResponse());
    }

    /**
     * Validate that the user has permission to view the controller/action
     *
     * @return void
     */
    private function _validatePermission(Koryukan_Model_User $user)
    {
        $request = $this->getRequest();
        $controllerName = $request->getControllerName();
        $actionName = $request->getActionName();

        if (0 === strcasecmp('error', $controllerName)) {
            return;
        }



        $acl = Zend_Registry::get('acl');
        if ($acl->hasPermission($user, $controllerName, $actionName)) {
        } elseif (0 === strcasecmp('error', $controllerName)) {
            //Do nothing
        } else {
            throw new KoryukanMembers_AccessDeniedException('Access denied');
        }
    }
}