<?php
/**
 * Koryukan_Model_Permission Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-06-26
 *
 */

/**
 * User's permissions
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class Koryukan_Model_Permission extends Koryukan_Model_Base
{
/**
     * The db class name
     *
     * @var string
     */
    const DB_CLASS_NAME = 'Koryukan_Db_Permission';


    /**
     * Return all the permission
     *
     * @return Koryukan_Helper_Collection
     */
    public static function getAllPermission()
    {
        $table = Doctrine_Core::getTable(self::DB_CLASS_NAME);
        $news = $table->getAllPermission();

        return new Koryukan_Helper_Collection($news, 'Koryukan_Model_Permission');
    }

    /**
     * Return the resource id
     *
     * @return integer
     */
    public function getId()
    {
        return (int) $this->_dbRecord->get('permissionId');
    }

    /**
     * Get the group id
     *
     * @return Koryukan_Model_UserGroup
     */
    public function getGroup()
    {
        return new Koryukan_Model_UserGroup($this->_dbRecord->get('UserGroup'));
    }

    /**
     * Get the resource id
     *
     * @return Koryukan_Model_Resource
     */
    public function getResource()
    {
        return new Koryukan_Model_Resource($this->_dbRecord->get('Resource'));
    }

    /**
     * Check if the permission is granted
     *
     * @return bool
     */
    public function isGranted()
    {
        return (1 === (int) $this->_dbRecord->get('granted'));
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