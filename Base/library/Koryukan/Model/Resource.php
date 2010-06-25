<?php
/**
 * Koryukan_Model_Resource Class File
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
 * Acl resource
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class Koryukan_Model_Resource extends Koryukan_Model_Base implements Zend_Acl_Resource_Interface
{
    /**
     * The db class name
     *
     * @var string
     */
    const DB_CLASS_NAME = 'Koryukan_Db_Resource';


    /**
     * Return all the resources
     *
     * @return Koryukan_Helper_Collection
     */
    public static function getAllResources()
    {
        $table = Doctrine_Core::getTable(self::DB_CLASS_NAME);
        $news = $table->getAllResources();

        return new Koryukan_Helper_Collection($news, 'Koryukan_Model_Resource');
    }

    /**
     * Return the resource id
     *
     * @return integer
     */
    public function getId()
    {
        return (int) $this->_dbRecord->get('resourceId');
    }

    /**
     * Return the controller name
     *
     * @return string
     */
    public function getControllerName()
    {
        return $this->_dbRecord->get('controllerName');
    }

    /**
     * Return the action name
     *
     * @return string
     */
    public function getActionName()
    {
        return $this->_dbRecord->get('actionName');
    }

    /**
     * Return the role id (group id)
     *
     * @return integer
     */
    public function getResourceId()
    {
        return $this->getId();
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