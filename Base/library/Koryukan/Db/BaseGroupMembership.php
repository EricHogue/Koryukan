<?php

/**
 * Koryukan_Db_BaseGroupMembership
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $userId
 * @property integer $groupId
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Koryukan_Db_BaseGroupMembership extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('group_membership');
        $this->hasColumn('userId', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'length' => '4',
             ));
        $this->hasColumn('groupId', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'length' => '4',
             ));

        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}