<?php

namespace CoreBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use CoreBundle\Model\EmpAcc;
use CoreBundle\Model\EmpAccPeer;
use CoreBundle\Model\map\EmpAccTableMap;

abstract class BaseEmpAccPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'emp_acc';

    /** the related Propel class for this table */
    const OM_CLASS = 'CoreBundle\\Model\\EmpAcc';

    /** the related TableMap class for this table */
    const TM_CLASS = 'CoreBundle\\Model\\map\\EmpAccTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 12;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 12;

    /** the column name for the id field */
    const ID = 'emp_acc.id';

    /** the column name for the username field */
    const USERNAME = 'emp_acc.username';

    /** the column name for the password field */
    const PASSWORD = 'emp_acc.password';

    /** the column name for the timestamp field */
    const TIMESTAMP = 'emp_acc.timestamp';

    /** the column name for the ip_add field */
    const IP_ADD = 'emp_acc.ip_add';

    /** the column name for the status field */
    const STATUS = 'emp_acc.status';

    /** the column name for the email field */
    const EMAIL = 'emp_acc.email';

    /** the column name for the role field */
    const ROLE = 'emp_acc.role';

    /** the column name for the team_role field */
    const TEAM_ROLE = 'emp_acc.team_role';

    /** the column name for the key field */
    const KEY = 'emp_acc.key';

    /** the column name for the created_by field */
    const CREATED_BY = 'emp_acc.created_by';

    /** the column name for the last_updated_by field */
    const LAST_UPDATED_BY = 'emp_acc.last_updated_by';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of EmpAcc objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array EmpAcc[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. EmpAccPeer::$fieldNames[EmpAccPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Username', 'Password', 'Timestamp', 'IpAdd', 'Status', 'Email', 'Role', 'TeamRole', 'Key', 'CreatedBy', 'LastUpdatedBy', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'username', 'password', 'timestamp', 'ipAdd', 'status', 'email', 'role', 'teamRole', 'key', 'createdBy', 'lastUpdatedBy', ),
        BasePeer::TYPE_COLNAME => array (EmpAccPeer::ID, EmpAccPeer::USERNAME, EmpAccPeer::PASSWORD, EmpAccPeer::TIMESTAMP, EmpAccPeer::IP_ADD, EmpAccPeer::STATUS, EmpAccPeer::EMAIL, EmpAccPeer::ROLE, EmpAccPeer::TEAM_ROLE, EmpAccPeer::KEY, EmpAccPeer::CREATED_BY, EmpAccPeer::LAST_UPDATED_BY, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'USERNAME', 'PASSWORD', 'TIMESTAMP', 'IP_ADD', 'STATUS', 'EMAIL', 'ROLE', 'TEAM_ROLE', 'KEY', 'CREATED_BY', 'LAST_UPDATED_BY', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'username', 'password', 'timestamp', 'ip_add', 'status', 'email', 'role', 'team_role', 'key', 'created_by', 'last_updated_by', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. EmpAccPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Username' => 1, 'Password' => 2, 'Timestamp' => 3, 'IpAdd' => 4, 'Status' => 5, 'Email' => 6, 'Role' => 7, 'TeamRole' => 8, 'Key' => 9, 'CreatedBy' => 10, 'LastUpdatedBy' => 11, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'username' => 1, 'password' => 2, 'timestamp' => 3, 'ipAdd' => 4, 'status' => 5, 'email' => 6, 'role' => 7, 'teamRole' => 8, 'key' => 9, 'createdBy' => 10, 'lastUpdatedBy' => 11, ),
        BasePeer::TYPE_COLNAME => array (EmpAccPeer::ID => 0, EmpAccPeer::USERNAME => 1, EmpAccPeer::PASSWORD => 2, EmpAccPeer::TIMESTAMP => 3, EmpAccPeer::IP_ADD => 4, EmpAccPeer::STATUS => 5, EmpAccPeer::EMAIL => 6, EmpAccPeer::ROLE => 7, EmpAccPeer::TEAM_ROLE => 8, EmpAccPeer::KEY => 9, EmpAccPeer::CREATED_BY => 10, EmpAccPeer::LAST_UPDATED_BY => 11, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'USERNAME' => 1, 'PASSWORD' => 2, 'TIMESTAMP' => 3, 'IP_ADD' => 4, 'STATUS' => 5, 'EMAIL' => 6, 'ROLE' => 7, 'TEAM_ROLE' => 8, 'KEY' => 9, 'CREATED_BY' => 10, 'LAST_UPDATED_BY' => 11, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'username' => 1, 'password' => 2, 'timestamp' => 3, 'ip_add' => 4, 'status' => 5, 'email' => 6, 'role' => 7, 'team_role' => 8, 'key' => 9, 'created_by' => 10, 'last_updated_by' => 11, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /**
     * Translates a fieldname to another type
     *
     * @param      string $name field name
     * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @param      string $toType   One of the class type constants
     * @return string          translated name of the field.
     * @throws PropelException - if the specified name could not be found in the fieldname mappings.
     */
    public static function translateFieldName($name, $fromType, $toType)
    {
        $toNames = EmpAccPeer::getFieldNames($toType);
        $key = isset(EmpAccPeer::$fieldKeys[$fromType][$name]) ? EmpAccPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(EmpAccPeer::$fieldKeys[$fromType], true));
        }

        return $toNames[$key];
    }

    /**
     * Returns an array of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @return array           A list of field names
     * @throws PropelException - if the type is not valid.
     */
    public static function getFieldNames($type = BasePeer::TYPE_PHPNAME)
    {
        if (!array_key_exists($type, EmpAccPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return EmpAccPeer::$fieldNames[$type];
    }

    /**
     * Convenience method which changes table.column to alias.column.
     *
     * Using this method you can maintain SQL abstraction while using column aliases.
     * <code>
     *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
     *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
     * </code>
     * @param      string $alias The alias for the current table.
     * @param      string $column The column name for current table. (i.e. EmpAccPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(EmpAccPeer::TABLE_NAME.'.', $alias.'.', $column);
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param      Criteria $criteria object containing the columns to add.
     * @param      string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(EmpAccPeer::ID);
            $criteria->addSelectColumn(EmpAccPeer::USERNAME);
            $criteria->addSelectColumn(EmpAccPeer::PASSWORD);
            $criteria->addSelectColumn(EmpAccPeer::TIMESTAMP);
            $criteria->addSelectColumn(EmpAccPeer::IP_ADD);
            $criteria->addSelectColumn(EmpAccPeer::STATUS);
            $criteria->addSelectColumn(EmpAccPeer::EMAIL);
            $criteria->addSelectColumn(EmpAccPeer::ROLE);
            $criteria->addSelectColumn(EmpAccPeer::TEAM_ROLE);
            $criteria->addSelectColumn(EmpAccPeer::KEY);
            $criteria->addSelectColumn(EmpAccPeer::CREATED_BY);
            $criteria->addSelectColumn(EmpAccPeer::LAST_UPDATED_BY);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.username');
            $criteria->addSelectColumn($alias . '.password');
            $criteria->addSelectColumn($alias . '.timestamp');
            $criteria->addSelectColumn($alias . '.ip_add');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.role');
            $criteria->addSelectColumn($alias . '.team_role');
            $criteria->addSelectColumn($alias . '.key');
            $criteria->addSelectColumn($alias . '.created_by');
            $criteria->addSelectColumn($alias . '.last_updated_by');
        }
    }

    /**
     * Returns the number of rows matching criteria.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @return int Number of matching rows.
     */
    public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
    {
        // we may modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(EmpAccPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            EmpAccPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(EmpAccPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(EmpAccPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        // BasePeer returns a PDOStatement
        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }
    /**
     * Selects one object from the DB.
     *
     * @param      Criteria $criteria object used to create the SELECT statement.
     * @param      PropelPDO $con
     * @return EmpAcc
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = EmpAccPeer::doSelect($critcopy, $con);
        if ($objects) {
            return $objects[0];
        }

        return null;
    }
    /**
     * Selects several row from the DB.
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con
     * @return array           Array of selected Objects
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        return EmpAccPeer::populateObjects(EmpAccPeer::doSelectStmt($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
     *
     * Use this method directly if you want to work with an executed statement directly (for example
     * to perform your own object hydration).
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con The connection to use
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return PDOStatement The executed PDOStatement object.
     * @see        BasePeer::doSelect()
     */
    public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(EmpAccPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            EmpAccPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(EmpAccPeer::DATABASE_NAME);

        // BasePeer returns a PDOStatement
        return BasePeer::doSelect($criteria, $con);
    }
    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doSelect*()
     * methods in your stub classes -- you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by doSelect*()
     * and retrieveByPK*() calls.
     *
     * @param EmpAcc $obj A EmpAcc object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            EmpAccPeer::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param      mixed $value A EmpAcc object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof EmpAcc) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or EmpAcc object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(EmpAccPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return EmpAcc Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(EmpAccPeer::$instances[$key])) {
                return EmpAccPeer::$instances[$key];
            }
        }

        return null; // just to be explicit
    }
    
    /**
     * Clear the instance pool.
     *
     * @return void
     */
    public static function clearInstancePool($and_clear_all_references = false)
    {
      if ($and_clear_all_references) {
        foreach (EmpAccPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        EmpAccPeer::$instances = array();
    }
    
    /**
     * Method to invalidate the instance pool of all tables related to emp_acc
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return string A string version of PK or null if the components of primary key in result array are all null.
     */
    public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
    {
        // If the PK cannot be derived from the row, return null.
        if ($row[$startcol] === null) {
            return null;
        }

        return (string) $row[$startcol];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $startcol = 0)
    {

        return (int) $row[$startcol];
    }
    
    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function populateObjects(PDOStatement $stmt)
    {
        $results = array();
    
        // set the class once to avoid overhead in the loop
        $cls = EmpAccPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = EmpAccPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = EmpAccPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                EmpAccPeer::addInstanceToPool($obj, $key);
            } // if key exists
        }
        $stmt->closeCursor();

        return $results;
    }
    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return array (EmpAcc object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = EmpAccPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = EmpAccPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + EmpAccPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = EmpAccPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            EmpAccPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * Returns the TableMap related to this peer.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getDatabaseMap(EmpAccPeer::DATABASE_NAME)->getTable(EmpAccPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseEmpAccPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseEmpAccPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \CoreBundle\Model\map\EmpAccTableMap());
      }
    }

    /**
     * The class that the Peer will make instances of.
     *
     *
     * @return string ClassName
     */
    public static function getOMClass($row = 0, $colnum = 0)
    {
        return EmpAccPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a EmpAcc or Criteria object.
     *
     * @param      mixed $values Criteria or EmpAcc object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(EmpAccPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from EmpAcc object
        }

        if ($criteria->containsKey(EmpAccPeer::ID) && $criteria->keyContainsValue(EmpAccPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.EmpAccPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(EmpAccPeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a EmpAcc or Criteria object.
     *
     * @param      mixed $values Criteria or EmpAcc object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(EmpAccPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(EmpAccPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(EmpAccPeer::ID);
            $value = $criteria->remove(EmpAccPeer::ID);
            if ($value) {
                $selectCriteria->add(EmpAccPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(EmpAccPeer::TABLE_NAME);
            }

        } else { // $values is EmpAcc object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(EmpAccPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the emp_acc table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(EmpAccPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(EmpAccPeer::TABLE_NAME, $con, EmpAccPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EmpAccPeer::clearInstancePool();
            EmpAccPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a EmpAcc or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or EmpAcc object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param      PropelPDO $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *				if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, PropelPDO $con = null)
     {
        if ($con === null) {
            $con = Propel::getConnection(EmpAccPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            EmpAccPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof EmpAcc) { // it's a model object
            // invalidate the cache for this single object
            EmpAccPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(EmpAccPeer::DATABASE_NAME);
            $criteria->add(EmpAccPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                EmpAccPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(EmpAccPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            
            $affectedRows += BasePeer::doDelete($criteria, $con);
            EmpAccPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given EmpAcc object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param EmpAcc $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(EmpAccPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(EmpAccPeer::TABLE_NAME);

            if (! is_array($cols)) {
                $cols = array($cols);
            }

            foreach ($cols as $colName) {
                if ($tableMap->hasColumn($colName)) {
                    $get = 'get' . $tableMap->getColumn($colName)->getPhpName();
                    $columns[$colName] = $obj->$get();
                }
            }
        } else {

        }

        return BasePeer::doValidate(EmpAccPeer::DATABASE_NAME, EmpAccPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return EmpAcc
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = EmpAccPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(EmpAccPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(EmpAccPeer::DATABASE_NAME);
        $criteria->add(EmpAccPeer::ID, $pk);

        $v = EmpAccPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return EmpAcc[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(EmpAccPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(EmpAccPeer::DATABASE_NAME);
            $criteria->add(EmpAccPeer::ID, $pks, Criteria::IN);
            $objs = EmpAccPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseEmpAccPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseEmpAccPeer::buildTableMap();

