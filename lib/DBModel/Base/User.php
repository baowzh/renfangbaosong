<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('DBModel_User', 'doctrine');

/**
 * DBModel_Base_User
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property text $role
 * @property integer $group_id
 * @property integer $department_id
 * @property datetime $lastlogin
 * @property DBModel_Department $Department
 * @property DBModel_Group $Group
 * @property Doctrine_Collection $XFile
 * @property Doctrine_Collection $GovPublic
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class DBModel_Base_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('v9_user');
        $this->hasColumn('id', 'integer', 10, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '10',
             ));
        $this->hasColumn('username', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('password', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('role', 'text', 5000, array(
             'type' => 'text',
             'length' => '5000',
             ));
        $this->hasColumn('group_id', 'integer', 10, array(
             'type' => 'integer',
             'length' => '10',
             ));
        $this->hasColumn('department_id', 'integer', 10, array(
             'type' => 'integer',
             'length' => '10',
             ));
        $this->hasColumn('lastlogin', 'datetime', null, array(
             'type' => 'datetime',
             ));

        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('DBModel_Department as Department', array(
             'local' => 'department_id',
             'foreign' => 'id'));

        $this->hasOne('DBModel_Group as Group', array(
             'local' => 'group_id',
             'foreign' => 'id'));

        $this->hasMany('DBModel_XFile as XFile', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('DBModel_GovPublic as GovPublic', array(
             'local' => 'id',
             'foreign' => 'user_id'));
    }
}