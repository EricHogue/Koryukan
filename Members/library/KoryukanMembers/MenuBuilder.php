<?php
/**
 * Koryukan_Model_MenuBuilder Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Members
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-09-04
 *
 */

/**
 * Build the menu for the user
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Members
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class KoryukanMembers_MenuBuilder
{
    /**
     * Acl object
     *
     * @var KoryukanMembers_Acl
     */
    protected $_acl;

    /**
     * The user
     *
     * @var Koryukan_Model_User
     */
    protected $_user;

    /**
     * Language
     *
     * @var string
     */
    protected $_language;

    /**
     * The vies
     *
     * @var Zend_View
     */
    protected $_view;



    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(KoryukanMembers_Acl $acl, Koryukan_Model_User $user, $language, Zend_View $view)
    {
        $this->_acl      = $acl;
        $this->_user     = $user;
        $this->_language = $language;
        $this->_view     = $view;
    }

    /**
     * Build Menu
     *
     * @return array
     */
    public function buildMenu()
    {
        $lang   = $this->_language;
        $config = Zend_Registry::get('config');
        $forumConfig = $config->get('forum');
        $url = $forumConfig->get('url');

        $mainMenu = array(
            array('lang' => $lang, 'title' => $this->_view->translate('Forum'), 'url' => $url, 'controller' => 'forum', 'action' => 'index'),
            array('lang' => $lang, 'title' => $this->_view->translate('My Profile'), 'controller' => 'profile', 'action' => 'index'),
            array('lang' => $lang, 'title' => $this->_view->translate('Manage News'), 'controller' => 'administration', 'action' => 'news'),
            array('lang' => $lang, 'title' => $this->_view->translate('Manage Users'), 'controller' => 'administration', 'action' => 'users'),
        );

        return $this->_getAuthorizedMenus($mainMenu);
    }

    /**
     * Return the authorized menu from the passed in menu
     *
     * @return array
     */
    private function _getAuthorizedMenus(array $originalMenu)
    {
        $authorizedMenu = array();

        foreach ($originalMenu as $menuItem) {
            $validatedItem = $this->_validateMenuItem($menuItem);
            if (true === isset($validatedItem)) $authorizedMenu[] = $validatedItem;
        }

        return $authorizedMenu;
    }

    /**
     * Validate a menu item
     *
     * @return void
     */
    private function _validateMenuItem(array $menuItem)
    {
        $hasPermissionToSeeItem = $this->_isItemPermited($menuItem);

        $allowedSubMenu = null;
        if (array_key_exists('submenu', $menuItem) && is_array($menuItem['submenu'])) {
            $allowedSubMenu = $this->_getAuthorizedMenus($menuItem['submenu']);
            $menuItem['submenu'] = $allowedSubMenu;
        }

        if (true === $hasPermissionToSeeItem || count($allowedSubMenu) > 0) return $menuItem;
        else return null;
    }

    /**
     * Check if the item itself is permited
     *
     * @return boolean
     */
    private function _isItemPermited(array $menuItem)
    {
        $controller = '';
        if (array_key_exists('controller', $menuItem)) {
            $controller = $menuItem['controller'];
        }

        $action = '';
        if(array_key_exists('action', $menuItem)) {
            $action = $menuItem['action'];
        }

        if (strlen($controller) > 0 && strlen($action) > 0) {
            return $this->_acl->hasPermission($this->_user, $controller, $action);
        } else {
            return false;
        }
    }
}