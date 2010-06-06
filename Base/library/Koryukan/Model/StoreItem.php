<?php
/**
 * Koryukan_Model_StoreItem Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-06-06
 *
 */

/**
 * Store items bz
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class Koryukan_Model_StoreItem extends Koryukan_Model_Base
{
    /**
     * The db class name
     *
     * @var string
     */
    const DB_CLASS_NAME = 'Koryukan_Db_StoreItem';


    /**
     * Return the items
     *
     * @return void
     */
    public static function getItems()
    {
        $table = Doctrine_Core::getTable(self::DB_CLASS_NAME);
        $items = $table->getItems(self::STATUS_ONLINE);

        return new Koryukan_Helper_Collection($items, 'Koryukan_Model_StoreItem');
    }

    /**
     * Return the name
     *
     * @return string
     */
    public function getName($language)
    {
        $detail = $this->_getDetailsForLanguage($language);
        return $detail->get('name');
    }

    /**
     * Return the image name
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->_dbRecord->get('imageName');
    }

    /**
     * Return the description
     *
     * @return string
     */
    public function getDescription($language)
    {
        $detail = $this->_getDetailsForLanguage($language);
        return $detail->get('description');
    }

    /**
     * Return the price
     *
     * @return Zend_Currency
     */
    public function getPrice()
    {
        return new Zend_Currency(array('value' => $this->_dbRecord->get('price')));
    }


    /**
     * Return the details for a language
     *
     * @return void
     */
    private function _getDetailsForLanguage($language)
    {
        $details = $this->_dbRecord->get('StoreItemDetail');
        foreach($details as $detail) {
            if (0 === strcasecmp($detail->get('language'), $language)) {
                return $detail;
            }
        }

        return null;
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