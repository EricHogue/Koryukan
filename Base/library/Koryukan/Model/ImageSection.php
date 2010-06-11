<?php
/**
 * Koryukan_Model_ImageSection Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-06-10
 *
 */

/**
 * Koryukan_Model_ImageSection
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class Koryukan_Model_ImageSection extends Koryukan_Model_Base
{
    /**
     * The db class name
     *
     * @var string
     */
    const DB_CLASS_NAME = 'Koryukan_Db_ImageSection';

    /**
     * Returns all the sections with the images loaded
     *
     * @return void
     */
    public static function getAllSectionWithImages()
    {
        $table = Doctrine_Core::getTable(self::DB_CLASS_NAME);
        $sections = $table->getAllSectionWithImages();

        return new Koryukan_Helper_Collection($sections, 'Koryukan_Model_ImageSection');
    }

    /**
     * Return the section date
     *
     * @return void
     */
    public function getSectionDate()
    {
        return new Zend_Date($this->_dbRecord->get('sectionDate'), Zend_Date::ISO_8601);
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