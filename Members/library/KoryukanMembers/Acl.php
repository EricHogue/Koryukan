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
     * Load the acl rules
     *
     * @return void
     */
    private function _loadAclRules()
    {
        $this->_loadRoles();
        $this->_loadResources();
        $this->_loadUsers();
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
            $this->_addGroup($subGroup);
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
            $parents[] = $group;
        }

        $this->addRole($user, $parents);
    }
}