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
    public function preDispatch($request)
    {
        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) {
        } else {
            $this->getRequest()->setControllerName('Login');
            $this->getRequest()->setActionName('index');
        }
    }
}