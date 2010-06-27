<?php

/**
 * Koryukan_Db_BasePermission
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $permissionId
 * @property integer $groupId
 * @property integer $resourceId
 * @property integer $granted
 * @property Koryukan_Db_UserGroup $UserGroup
 * @property Koryukan_Db_Resource $Resource
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Koryukan_Db_BasePermission extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('permission');
        $this->hasColumn('permissionId', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('groupId', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('resourceId', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('granted', 'integer', 1, array(
             'type' => 'integer',
             'notnull' => true,
             'unsigned' => true,
             'default' => 0,
             'length' => '1',
             ));


        $this->index('user_resourceIdx', array(
             'fields' => 
             array(
              'groupId' => 
              array(
              ),
              'resourceId' => 
              array(
              ),
             ),
             'type' => 'unique',
             ));
        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Koryukan_Db_UserGroup as UserGroup', array(
             'local' => 'groupId',
             'foreign' => 'groupId'));

        $this->hasOne('Koryukan_Db_Resource as Resource', array(
             'local' => 'resourceId',
             'foreign' => 'resourceId'));
    }
}