<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('DBModel_FileAccept', 'doctrine');

/**
 * DBModel_Base_FileAccept
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $xfile_id
 * @property integer $cms_catid
 * @property integer $accept_typeid
 * @property datetime $accept_time
 * @property DBModel_AcceptType $AcceptType
 * @property DBModel_XFile $XFile
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class DBModel_Base_FileAccept extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('v9_fileaccept');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('xfile_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('cms_catid', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('accept_typeid', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('accept_time', 'datetime', null, array(
             'type' => 'datetime',
             ));

        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('DBModel_AcceptType as AcceptType', array(
             'local' => 'accept_typeid',
             'foreign' => 'id'));

        $this->hasOne('DBModel_XFile as XFile', array(
             'local' => 'xfile_id',
             'foreign' => 'id'));
    }
}