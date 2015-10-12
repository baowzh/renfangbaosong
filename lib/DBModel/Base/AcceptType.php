<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('DBModel_AcceptType', 'doctrine');

/**
 * DBModel_Base_AcceptType
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property integer $atype
 * @property Doctrine_Collection $FileAccept
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class DBModel_Base_AcceptType extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('v9_accepttype');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('name', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('atype', 'integer', 2, array(
             'type' => 'integer',
             'length' => '2',
             ));

        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('DBModel_FileAccept as FileAccept', array(
             'local' => 'id',
             'foreign' => 'accept_typeid'));
    }
}