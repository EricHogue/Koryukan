<?php
/**
 * Koryukan_Model_UserGroup Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-06-24
 *
 */

/**
 * User group
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class Koryukan_Model_UserGroup extends Koryukan_Model_Base implements Zend_Acl_Role_Interface
{
    /**
     * The db class name
     *
     * @var string
     */
    const DB_CLASS_NAME = 'Koryukan_Db_UserGroup';


    /**
     * Return all the user groups
     *
     * @return Koryukan_Helper_Collection
     */
    public static function getAllUserGroups()
    {
        $table = Doctrine_Core::getTable(self::DB_CLASS_NAME);
        $news = $table->getAllUserGroups();

        return new Koryukan_Helper_Collection($news, 'Koryukan_Model_UserGroup');
    }

    /**
     * Return the group id
     *
     * @return integer
     */
    public function getId()
    {
        return (int) $this->_dbRecord->get('groupId');
    }

    /**
     * Return the group name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_dbRecord->get('groupName');
    }

    /**
     * Return the role id (group id)
     *
     * @return integer
     */
    public function getRoleId()
    {
        return 'group' . $this->getId();
    }

    /**
     * Return the sub groups
     *
     * @return Koryukan_Helper_Collection
     */
    public function getSubGroups()
    {
        return new Koryukan_Helper_Collection($this->_dbRecord->get('SubGroups'), 'Koryukan_Model_UserGroup');
    }

    /**
     * Return the users that are members of this group
     *
     * @return Koryukan_Helper_Collection
     */
    public function getUsers()
    {
        return new Koryukan_Helper_Collection($this->_dbRecord->get('Users'), 'Koryukan_Model_User');
    }

    /**
     * Return the name of the db class
     *
     * @return string
     */
    protected function _getDbClassName()
    {
        return self::DB_CLASS_NAME;
    }
}