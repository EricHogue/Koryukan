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
class Koryukan_Model_User extends Koryukan_Model_Base
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
     * Return the name of the db class
     *
     * @return string
     */
    protected function _getDbClassName()
    {
        return self::DB_CLASS_NAME;
    }

}