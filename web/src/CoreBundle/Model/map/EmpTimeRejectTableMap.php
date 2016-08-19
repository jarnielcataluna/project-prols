<?php

namespace CoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'emp_time_reject' table.
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
class EmpTimeRejectTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CoreBundle.Model.map.EmpTimeRejectTableMap';

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
        $this->setName('emp_time_reject');
        $this->setPhpName('EmpTimeReject');
        $this->setClassname('CoreBundle\\Model\\EmpTimeReject');
        $this->setPackage('src.CoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('time_in', 'TimeIn', 'TIMESTAMP', false, null, null);
        $this->addColumn('time_out', 'TimeOut', 'TIMESTAMP', false, null, null);
        $this->addColumn('ip_add', 'IpAdd', 'VARCHAR', false, 45, null);
        $this->addColumn('date', 'Date', 'TIMESTAMP', false, null, null);
        $this->addForeignKey('emp_acc_acc_id', 'EmpAccAccId', 'INTEGER', 'emp_acc', 'id', false, null, null);
        $this->addColumn('manhours', 'Manhours', 'FLOAT', false, null, null);
        $this->addColumn('overtime', 'Overtime', 'FLOAT', false, null, null);
        $this->addColumn('check_ip', 'CheckIp', 'INTEGER', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('EmpAcc', 'CoreBundle\\Model\\EmpAcc', RelationMap::MANY_TO_ONE, array('emp_acc_acc_id' => 'id', ), null, null);
    } // buildRelations()

} // EmpTimeRejectTableMap
