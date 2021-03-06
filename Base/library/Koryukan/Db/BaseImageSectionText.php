<?php

/**
 * Koryukan_Db_BaseImageSectionText
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $sectionId
 * @property enum $language
 * @property text $title
 * @property Koryukan_Db_ImageSection $ImageSection
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Koryukan_Db_BaseImageSectionText extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('image_section_text');
        $this->hasColumn('sectionId', 'integer', 4, array(
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
        $this->hasColumn('title', 'text', null, array(
             'type' => 'text',
             'notnull' => true,
             ));

        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Koryukan_Db_ImageSection as ImageSection', array(
             'local' => 'sectionId',
             'foreign' => 'sectionId'));
    }
}