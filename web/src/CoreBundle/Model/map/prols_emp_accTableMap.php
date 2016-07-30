<?php

namespace CoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'emp_acc' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.CoreBundle.Model.map
 */
class prols_emp_accTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CoreBundle.Model.map.prols_emp_accTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('emp_acc');
        $this->setPhpName('prols_emp_acc');
        $this->setClassname('CoreBundle\\Model\\prols_emp_acc');
        $this->setPackage('src.CoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('email', 'Email', 'VARCHAR', true, 45, null);
        $this->addColumn('username', 'Username', 'VARCHAR', true, 45, null);
        $this->addColumn('password', 'Password', 'VARCHAR', true, 45, null);
        $this->addColumn('authkey', 'Authkey', 'VARCHAR', true, 45, null);
        $this->addColumn('timestamp', 'Timestamp', 'VARCHAR', true, 255, null);
        $this->addColumn('role_id', 'RoleId', 'INTEGER', true, null, null);
        $this->addColumn('ip_add', 'IpAdd', 'VARCHAR', true, 45, null);
        $this->addColumn('status', 'Status', 'VARCHAR', true, 45, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

} // prols_emp_accTableMap
