<?php
/**
 * KoryukanMembers_Acl Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Members
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-06-23
 *
 */

/**
 * Acl
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Members
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class KoryukanMembers_Acl extends Zend_Acl
{
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->_loadAclRules();
    }

    /**
     * Check if the user has permission to access the resource
     *
     * @return boolean
     */
    public function hasPermission(Koryukan_Model_User $user, $controllerName, $actionName)
    {
        $resource = Koryukan_Model_Resource::getByControllerAndActionName($controllerName, $actionName);
        /*echo $user->getRoleId() . '<br />';
        echo $resource->getResourceId() . '<br />';*/

        return $this->isAllowed($user, $resource);
    }

    /**
     * Load the acl rules
     *
     * @return void
     */
    private function _loadAclRules()
    {
        $this->_loadRoles();
        $this->_loadResources();
        $this->_loadUsers();
        $this->_loadPermissions();
    }

    /**
     * Load the roles
     *
     * @return void
     */
    private function _loadRoles()
    {
        $groups = Koryukan_Model_UserGroup::getAllUserGroups();

        foreach ($groups as $group) {
            $this->_addGroup($group);
        }
    }

    /**
     * Add a group to the acl
     *
     * @return void
     */
    private function _addGroup(Koryukan_Model_UserGroup $group, $parentGroup = null)
    {
        if ($this->hasRole($group)) return;

        $this->addRole($group, $parentGroup);

        $subGroups = $group->getSubGroups();
        foreach ($subGroups as $subGroup) {
            $this->_addGroup($subGroup, $group);
        }
    }

    /**
     * Load the resources
     *
     * @return void
     */
    private function _loadResources()
    {
        $resources = Koryukan_Model_Resource::getAllResources();

        foreach ($resources as $resource) {
            $this->addResource($resource);
        }
    }

    /**
     * Load the users
     *
     * @return void
     */
    private function _loadUsers()
    {
        $users = Koryukan_Model_User::getAllUsers();
        foreach ($users as $user) {
            $this->_addUser($user);
        }
    }

    /**
     * Add a user to the acl
     *
     * @return void
     */
    private function _addUser(Koryukan_Model_User $user)
    {
        $groups = $user->getGroups();
        $parents = array();
        foreach ($groups as $group) {
            $parents[] = $group->getRoleId();
        }

        $this->addRole($user, $parents);
    }

    /**
     * Load the permissions
     *
     * @return void
     */
    private function _loadPermissions()
    {
        $permissions = Koryukan_Model_Permission::getAllPermission();

        foreach ($permissions as $permission) {
            $userGroup = $permission->getGroup();
            $resource = $permission->getResource();

            if (true === $permission->isGranted()) {
                $this->allow($userGroup->getRoleId(), $resource->getResourceId());
            } else {
                $this->deny($userGroup->getRoleId(), $resource->getResourceId());
            }
        }
    }
}