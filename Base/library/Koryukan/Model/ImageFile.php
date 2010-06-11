<?php
/**
 * Koryukan_Model_ImageFile Class File
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
 * Koryukan_Model_ImageFile
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class Koryukan_Model_ImageFile extends Koryukan_Model_Base
{
    /**
     * The db class name
     *
     * @var string
     */
    const DB_CLASS_NAME = 'Koryukan_Db_ImageFile';

    /**
     * Return the file name
     *
     * @return void
     */
    public function getFileName()
    {
        return $this->_dbRecord->get('fileName');
    }

    /**
     * Return the description for a language
     *
     * @return void
     */
    public function getDescription($language)
    {
        $imageText = $this->_getImageTextForLanguage($language);
        return $imageText->get('description');
    }

    /**
     * Return the ImageText object for the selected language
     *
     * @return Koryukan_Db_ImageText
     */
    private function _getImageTextForLanguage($language)
    {
        $imageTexts = $this->_dbRecord->get('ImageText');
        foreach ($imageTexts as $imageText) {
            if (0 === strcasecmp($imageText->get('language'), $language)) {
                return $imageText;
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