<?php

namespace CoreBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use CoreBundle\Model\EmpLeave;
use CoreBundle\Model\ListLeaveType;
use CoreBundle\Model\ListLeaveTypePeer;
use CoreBundle\Model\ListLeaveTypeQuery;

/**
 * @method ListLeaveTypeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method ListLeaveTypeQuery orderByLeaveType($order = Criteria::ASC) Order by the leave_type column
 *
 * @method ListLeaveTypeQuery groupById() Group by the id column
 * @method ListLeaveTypeQuery groupByLeaveType() Group by the leave_type column
 *
 * @method ListLeaveTypeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ListLeaveTypeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ListLeaveTypeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ListLeaveTypeQuery leftJoinEmpLeave($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmpLeave relation
 * @method ListLeaveTypeQuery rightJoinEmpLeave($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmpLeave relation
 * @method ListLeaveTypeQuery innerJoinEmpLeave($relationAlias = null) Adds a INNER JOIN clause to the query using the EmpLeave relation
 *
 * @method ListLeaveType findOne(PropelPDO $con = null) Return the first ListLeaveType matching the query
 * @method ListLeaveType findOneOrCreate(PropelPDO $con = null) Return the first ListLeaveType matching the query, or a new ListLeaveType object populated from the query conditions when no match is found
 *
 * @method ListLeaveType findOneByLeaveType(string $leave_type) Return the first ListLeaveType filtered by the leave_type column
 *
 * @method array findById(int $id) Return ListLeaveType objects filtered by the id column
 * @method array findByLeaveType(string $leave_type) Return ListLeaveType objects filtered by the leave_type column
 */
abstract class BaseListLeaveTypeQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseListLeaveTypeQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'default';
        }
        if (null === $modelName) {
            $modelName = 'CoreBundle\\Model\\ListLeaveType';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ListLeaveTypeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ListLeaveTypeQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ListLeaveTypeQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ListLeaveTypeQuery) {
            return $criteria;
        }
        $query = new ListLeaveTypeQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   ListLeaveType|ListLeaveType[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ListLeaveTypePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ListLeaveTypePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 ListLeaveType A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 ListLeaveType A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `leave_type` FROM `list_leave_type` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new ListLeaveType();
            $obj->hydrate($row);
            ListLeaveTypePeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return ListLeaveType|ListLeaveType[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|ListLeaveType[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ListLeaveTypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ListLeaveTypePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ListLeaveTypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ListLeaveTypePeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListLeaveTypeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ListLeaveTypePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ListLeaveTypePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListLeaveTypePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the leave_type column
     *
     * Example usage:
     * <code>
     * $query->filterByLeaveType('fooValue');   // WHERE leave_type = 'fooValue'
     * $query->filterByLeaveType('%fooValue%'); // WHERE leave_type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $leaveType The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ListLeaveTypeQuery The current query, for fluid interface
     */
    public function filterByLeaveType($leaveType = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($leaveType)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $leaveType)) {
                $leaveType = str_replace('*', '%', $leaveType);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ListLeaveTypePeer::LEAVE_TYPE, $leaveType, $comparison);
    }

    /**
     * Filter the query by a related EmpLeave object
     *
     * @param   EmpLeave|PropelObjectCollection $empLeave  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ListLeaveTypeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEmpLeave($empLeave, $comparison = null)
    {
        if ($empLeave instanceof EmpLeave) {
            return $this
                ->addUsingAlias(ListLeaveTypePeer::ID, $empLeave->getListLeaveTypeId(), $comparison);
        } elseif ($empLeave instanceof PropelObjectCollection) {
            return $this
                ->useEmpLeaveQuery()
                ->filterByPrimaryKeys($empLeave->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEmpLeave() only accepts arguments of type EmpLeave or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EmpLeave relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ListLeaveTypeQuery The current query, for fluid interface
     */
    public function joinEmpLeave($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EmpLeave');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'EmpLeave');
        }

        return $this;
    }

    /**
     * Use the EmpLeave relation EmpLeave object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CoreBundle\Model\EmpLeaveQuery A secondary query class using the current class as primary query
     */
    public function useEmpLeaveQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEmpLeave($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EmpLeave', '\CoreBundle\Model\EmpLeaveQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ListLeaveType $listLeaveType Object to remove from the list of results
     *
     * @return ListLeaveTypeQuery The current query, for fluid interface
     */
    public function prune($listLeaveType = null)
    {
        if ($listLeaveType) {
            $this->addUsingAlias(ListLeaveTypePeer::ID, $listLeaveType->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
