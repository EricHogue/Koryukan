<?php

/**
 * Koryukan_Db_BaseImageSection
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $sectionId
 * @property date $sectionDate
 * @property Doctrine_Collection $ImageSectionText
 * @property Doctrine_Collection $ImageFile
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Koryukan_Db_BaseImageSection extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('image_section');
        $this->hasColumn('sectionId', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('sectionDate', 'date', null, array(
             'type' => 'date',
             'notnull' => true,
             ));

        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Koryukan_Db_ImageSectionText as ImageSectionText', array(
             'local' => 'sectionId',
             'foreign' => 'sectionId'));

        $this->hasMany('Koryukan_Db_ImageFile as ImageFile', array(
             'local' => 'sectionId',
             'foreign' => 'sectionId'));
    }
}