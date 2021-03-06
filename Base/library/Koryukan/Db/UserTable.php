<?php

/**
 * Koryukan_Db_UserTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Koryukan_Db_UserTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object Koryukan_Db_UserTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Koryukan_Db_User');
    }

    /**
     * Return a user by username
     *
     * @return Koryukan_Db_User
     */
    public function getByUsername($username)
    {
        $query = $this->createQuery('');
        $query->addWhere('username = ?', $username);

        return $query->fetchOne();
    }

    /**
     * Return all the users
     *
     * @return Doctrine_Query
     */
    public function getAllUsers()
    {
        $query = $this->createQuery('user');
        $query->leftJoin('user.UserGroups');
        $query->addWhere('status = ?', 'active');

        return $query;
    }
}