<?php
/**
 * KoryukanMembers_Controller_Plugin_ActionSetup Class File
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
 * Set up the menu
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Members
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class KoryukanMembers_Controller_Plugin_ActionSetup extends Zend_Controller_Plugin_Abstract
{
    /**
     * Pre dispatch
     *
     * @return void
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        if ($request->isXmlHttpRequest()) return;

        $front = Zend_Controller_Front::getInstance();
        if (!$front->hasPlugin('Zend_Controller_Plugin_ActionStack')) {
            $actionStack = new Zend_Controller_Plugin_ActionStack();
            $front->registerPlugin($actionStack, 95);
        } else {
            $actionStack = $front->getPlugin('Zend_Controller_Plugin_ActionStack');
        }

        $controllerName = $request->getControllerName();

        if (0 !== strcasecmp('vanillaauth', $controllerName)) {
            $menuAction = clone($request);
            $menuAction->setActionName('menu')->setControllerName('menu');
            $actionStack->pushStack($menuAction);
        }
    }
}