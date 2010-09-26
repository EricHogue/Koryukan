<?php
/**
 * Koryukan_Model_User Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-06-20
 *
 */

/**
 * User
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class Koryukan_Model_User extends Koryukan_Model_Base implements Zend_Acl_Role_Interface
{
    const HASH_ITERATION_COUNT = 15;

    /**
     * The db class name
     *
     * @var string
     */
    const DB_CLASS_NAME = 'Koryukan_Db_User';


    /**
     * Return a user by username
     *
     * @return Koryukan_Model_User
     */
    public static function getByUsername($username)
    {
        $table = Doctrine_Core::getTable(self::DB_CLASS_NAME);
        $user = $table->getByUsername($username);

        if (true === isset($user) && false !== $user) {
            return new self($user);
        }

        return null;
    }

    /**
     * Return all the users
     *
     * @return Koryukan_Helper_Collection
     */
    public static function getAllUsers()
    {
        $table = Doctrine_Core::getTable(self::DB_CLASS_NAME);
        $users = $table->getAllUsers();

        return new Koryukan_Helper_Collection($users, 'Koryukan_Model_User');
    }

    /**
     * Validate the password
     *
     * @return void
     */
    public function validatePassword($password)
    {
        $hasher = new Phpass_PasswordHash(self::HASH_ITERATION_COUNT, true);
        return $hasher->CheckPassword($password, $this->_dbRecord->get('password'));
    }

    /**
     * Return the user id
     *
     * @return void
     */
    public function getId()
    {
        return (int) $this->_dbRecord->get('userId');
    }

    /**
     * Return the role id (group id)
     *
     * @return integer
     */
    public function getRoleId()
    {
        return 'user' . $this->getId();
    }

    /**
     * Return the user name
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->_dbRecord->get('username');
    }

    /**
     * Return the groups that the user is member of
     *
     * @return Koryukan_Helper_Collection
     */
    public function getGroups()
    {
        return new Koryukan_Helper_Collection($this->_dbRecord->get('UserGroups'), 'Koryukan_Model_UserGroup');
    }

    /**
     * Return the user full name
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->_dbRecord->get('firstName') . ' ' . $this->_dbRecord->get('lastName');
    }

    /**
     * Return the user email address
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->_dbRecord->get('email');
    }

    /**
     * Return the user first name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->_dbRecord->get('firstName');
    }

    /**
     * Return the user last name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->_dbRecord->get('lastName');
    }

    /**
     * Return the status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->_dbRecord->get('status');
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