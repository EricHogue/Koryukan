<?php
/**
 * Koryukan_Model_Base Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-05-29
 *
 */

/**
 * Base class for models
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 */
abstract class Koryukan_Model_Base
{
    const STATUS_ONLINE = 'online';
    const STATUS_DELETED = 'deleted';

    /**
     * Doctrine_Record
     *
     * @var Doctrine_Record
     */
    protected $_dbRecord;

    /**
     * Return the name of the db class
     *
     * @return string
     */
    protected abstract function _getDbClassName();

    /**
     * Class constructor
     *
     * @return void
     */
    public function __construct(Doctrine_Record $dbRecord = null)
    {
        if (true !== isset($dbRecord)) {
            $dbClassName = $this->_getDbClassName();
            $dbRecord = new $dbClassName();
        }

        $this->_dbRecord = $dbRecord;
    }

    /**
     * Save the changes
     *
     * @return void
     */
    public function save()
    {
        $this->_dbRecord->save();
    }

    /**
     * Return the db record
     *
     * @return Doctrine_Record
     */
    public function getDbRecord()
    {
        return $this->_dbRecord;
    }

    /**
     * Check if it's a new record
     *
     * @return boolean
     */
    public function isNew()
    {
        return !$this->getDbRecord()->exists();
    }
}