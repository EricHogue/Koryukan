<?php

/**
 * Koryukan_Db_BaseStoreItem
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $imageName
 * @property decimal $price
 * @property enum $status
 * @property Doctrine_Collection $StoreItemDetail
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Koryukan_Db_BaseStoreItem extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('store_item');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('imageName', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('price', 'decimal', 8, array(
             'type' => 'decimal',
             'notnull' => true,
             'unsigned' => true,
             'length' => '8',
             'scale' => '2',
             ));
        $this->hasColumn('status', 'enum', null, array(
             'type' => 'enum',
             'notnull' => true,
             'values' => 
             array(
              0 => 'online',
              1 => 'deleted',
             ),
             'default' => 'online',
             ));

        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Koryukan_Db_StoreItemDetail as StoreItemDetail', array(
             'local' => 'id',
             'foreign' => 'itemId'));
    }
}