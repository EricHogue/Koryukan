<?php
/**
 * Koryukan_Model_News Class File
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
 * News model
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class Koryukan_Model_News extends Koryukan_Model_Base
{
    /**
     * The db class name
     *
     * @var string
     */
    const DB_CLASS_NAME = 'Koryukan_Db_News';


    /**
     * Return the news
     *
     * @return void
     */
    public static function getNews()
    {
        $table = Doctrine_Core::getTable(self::DB_CLASS_NAME);
        $news = $table->getNews(self::STATUS_ONLINE);

        return new Koryukan_Helper_Collection($news, 'Koryukan_Model_News');
    }

    /**
     * Return the news publication date
     *
     * @return Zend_Date
     */
    public function getPublicationDate()
    {
        return new Zend_Date($this->_dbRecord->get('publishedDate'));
    }

    /**
     * Return the title
     *
     * @return string
     */
    public function getTitle($language)
    {
        $newsText = $this->_getNewsTextForLanguage($language);
        return $newsText->get('title');
    }

    /**
     * Return the content
     *
     * @return string
     */
    public function getContent($language)
    {
        $newsText = $this->_getNewsTextForLanguage($language);
        return $newsText->get('content');
    }


    /**
     * Return the NewsText object for the selected language
     *
     * @return Koryukan_Db_NewsText
     */
    private function _getNewsTextForLanguage($language)
    {
        $newsTexts = $this->_dbRecord->get('NewsText');
        foreach ($newsTexts as $newsText) {
            if (0 === strcasecmp($newsText->get('language'), $language)) {
                return $newsText;
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