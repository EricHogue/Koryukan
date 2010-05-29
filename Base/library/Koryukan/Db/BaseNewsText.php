<?php

/**
 * Koryukan_Db_BaseNewsText
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $newsId
 * @property enum $language
 * @property string $title
 * @property text $content
 * @property Koryukan_Db_News $News
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Koryukan_Db_BaseNewsText extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('news_text');
        $this->hasColumn('newsId', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'length' => '4',
             ));
        $this->hasColumn('language', 'enum', null, array(
             'type' => 'enum',
             'values' => 
             array(
              0 => 'fr',
              1 => 'en',
             ),
             'primary' => true,
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'notnull' => 'true;',
             'length' => '255',
             ));
        $this->hasColumn('content', 'text', null, array(
             'type' => 'text',
             'notnull' => true,
             ));

        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Koryukan_Db_News as News', array(
             'local' => 'newsId',
             'foreign' => 'id'));
    }
}