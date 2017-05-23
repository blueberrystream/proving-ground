<?php

namespace app\model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use app\model\Item as ChildItem;
use app\model\ItemQuery as ChildItemQuery;
use app\model\Map\ItemTableMap;

/**
 * Base class that represents a query for the 'item' table.
 *
 *
 *
 * @method     ChildItemQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildItemQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildItemQuery orderByPropriumId($order = Criteria::ASC) Order by the proprium_id column
 * @method     ChildItemQuery orderByPartId($order = Criteria::ASC) Order by the part_id column
 * @method     ChildItemQuery orderByHitPoint($order = Criteria::ASC) Order by the hit_point column
 * @method     ChildItemQuery orderByAttackPoint($order = Criteria::ASC) Order by the attack_point column
 * @method     ChildItemQuery orderByDefensePoint($order = Criteria::ASC) Order by the defense_point column
 *
 * @method     ChildItemQuery groupById() Group by the id column
 * @method     ChildItemQuery groupByName() Group by the name column
 * @method     ChildItemQuery groupByPropriumId() Group by the proprium_id column
 * @method     ChildItemQuery groupByPartId() Group by the part_id column
 * @method     ChildItemQuery groupByHitPoint() Group by the hit_point column
 * @method     ChildItemQuery groupByAttackPoint() Group by the attack_point column
 * @method     ChildItemQuery groupByDefensePoint() Group by the defense_point column
 *
 * @method     ChildItemQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildItemQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildItemQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildItemQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildItemQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildItemQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildItemQuery leftJoinPart($relationAlias = null) Adds a LEFT JOIN clause to the query using the Part relation
 * @method     ChildItemQuery rightJoinPart($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Part relation
 * @method     ChildItemQuery innerJoinPart($relationAlias = null) Adds a INNER JOIN clause to the query using the Part relation
 *
 * @method     ChildItemQuery joinWithPart($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Part relation
 *
 * @method     ChildItemQuery leftJoinWithPart() Adds a LEFT JOIN clause and with to the query using the Part relation
 * @method     ChildItemQuery rightJoinWithPart() Adds a RIGHT JOIN clause and with to the query using the Part relation
 * @method     ChildItemQuery innerJoinWithPart() Adds a INNER JOIN clause and with to the query using the Part relation
 *
 * @method     ChildItemQuery leftJoinProprium($relationAlias = null) Adds a LEFT JOIN clause to the query using the Proprium relation
 * @method     ChildItemQuery rightJoinProprium($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Proprium relation
 * @method     ChildItemQuery innerJoinProprium($relationAlias = null) Adds a INNER JOIN clause to the query using the Proprium relation
 *
 * @method     ChildItemQuery joinWithProprium($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Proprium relation
 *
 * @method     ChildItemQuery leftJoinWithProprium() Adds a LEFT JOIN clause and with to the query using the Proprium relation
 * @method     ChildItemQuery rightJoinWithProprium() Adds a RIGHT JOIN clause and with to the query using the Proprium relation
 * @method     ChildItemQuery innerJoinWithProprium() Adds a INNER JOIN clause and with to the query using the Proprium relation
 *
 * @method     ChildItemQuery leftJoinPlayerItem($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerItem relation
 * @method     ChildItemQuery rightJoinPlayerItem($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerItem relation
 * @method     ChildItemQuery innerJoinPlayerItem($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerItem relation
 *
 * @method     ChildItemQuery joinWithPlayerItem($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerItem relation
 *
 * @method     ChildItemQuery leftJoinWithPlayerItem() Adds a LEFT JOIN clause and with to the query using the PlayerItem relation
 * @method     ChildItemQuery rightJoinWithPlayerItem() Adds a RIGHT JOIN clause and with to the query using the PlayerItem relation
 * @method     ChildItemQuery innerJoinWithPlayerItem() Adds a INNER JOIN clause and with to the query using the PlayerItem relation
 *
 * @method     \app\model\PartQuery|\app\model\PropriumQuery|\app\model\PlayerItemQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildItem findOne(ConnectionInterface $con = null) Return the first ChildItem matching the query
 * @method     ChildItem findOneOrCreate(ConnectionInterface $con = null) Return the first ChildItem matching the query, or a new ChildItem object populated from the query conditions when no match is found
 *
 * @method     ChildItem findOneById(int $id) Return the first ChildItem filtered by the id column
 * @method     ChildItem findOneByName(string $name) Return the first ChildItem filtered by the name column
 * @method     ChildItem findOneByPropriumId(int $proprium_id) Return the first ChildItem filtered by the proprium_id column
 * @method     ChildItem findOneByPartId(int $part_id) Return the first ChildItem filtered by the part_id column
 * @method     ChildItem findOneByHitPoint(int $hit_point) Return the first ChildItem filtered by the hit_point column
 * @method     ChildItem findOneByAttackPoint(int $attack_point) Return the first ChildItem filtered by the attack_point column
 * @method     ChildItem findOneByDefensePoint(int $defense_point) Return the first ChildItem filtered by the defense_point column *

 * @method     ChildItem requirePk($key, ConnectionInterface $con = null) Return the ChildItem by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildItem requireOne(ConnectionInterface $con = null) Return the first ChildItem matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildItem requireOneById(int $id) Return the first ChildItem filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildItem requireOneByName(string $name) Return the first ChildItem filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildItem requireOneByPropriumId(int $proprium_id) Return the first ChildItem filtered by the proprium_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildItem requireOneByPartId(int $part_id) Return the first ChildItem filtered by the part_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildItem requireOneByHitPoint(int $hit_point) Return the first ChildItem filtered by the hit_point column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildItem requireOneByAttackPoint(int $attack_point) Return the first ChildItem filtered by the attack_point column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildItem requireOneByDefensePoint(int $defense_point) Return the first ChildItem filtered by the defense_point column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildItem[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildItem objects based on current ModelCriteria
 * @method     ChildItem[]|ObjectCollection findById(int $id) Return ChildItem objects filtered by the id column
 * @method     ChildItem[]|ObjectCollection findByName(string $name) Return ChildItem objects filtered by the name column
 * @method     ChildItem[]|ObjectCollection findByPropriumId(int $proprium_id) Return ChildItem objects filtered by the proprium_id column
 * @method     ChildItem[]|ObjectCollection findByPartId(int $part_id) Return ChildItem objects filtered by the part_id column
 * @method     ChildItem[]|ObjectCollection findByHitPoint(int $hit_point) Return ChildItem objects filtered by the hit_point column
 * @method     ChildItem[]|ObjectCollection findByAttackPoint(int $attack_point) Return ChildItem objects filtered by the attack_point column
 * @method     ChildItem[]|ObjectCollection findByDefensePoint(int $defense_point) Return ChildItem objects filtered by the defense_point column
 * @method     ChildItem[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ItemQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \app\model\Base\ItemQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\app\\model\\Item', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildItemQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildItemQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildItemQuery) {
            return $criteria;
        }
        $query = new ChildItemQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
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
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildItem|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ItemTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ItemTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildItem A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, proprium_id, part_id, hit_point, attack_point, defense_point FROM item WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildItem $obj */
            $obj = new ChildItem();
            $obj->hydrate($row);
            ItemTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildItem|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildItemQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ItemTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildItemQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ItemTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildItemQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ItemTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ItemTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ItemTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildItemQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ItemTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the proprium_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPropriumId(1234); // WHERE proprium_id = 1234
     * $query->filterByPropriumId(array(12, 34)); // WHERE proprium_id IN (12, 34)
     * $query->filterByPropriumId(array('min' => 12)); // WHERE proprium_id > 12
     * </code>
     *
     * @see       filterByProprium()
     *
     * @param     mixed $propriumId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildItemQuery The current query, for fluid interface
     */
    public function filterByPropriumId($propriumId = null, $comparison = null)
    {
        if (is_array($propriumId)) {
            $useMinMax = false;
            if (isset($propriumId['min'])) {
                $this->addUsingAlias(ItemTableMap::COL_PROPRIUM_ID, $propriumId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($propriumId['max'])) {
                $this->addUsingAlias(ItemTableMap::COL_PROPRIUM_ID, $propriumId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ItemTableMap::COL_PROPRIUM_ID, $propriumId, $comparison);
    }

    /**
     * Filter the query on the part_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPartId(1234); // WHERE part_id = 1234
     * $query->filterByPartId(array(12, 34)); // WHERE part_id IN (12, 34)
     * $query->filterByPartId(array('min' => 12)); // WHERE part_id > 12
     * </code>
     *
     * @see       filterByPart()
     *
     * @param     mixed $partId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildItemQuery The current query, for fluid interface
     */
    public function filterByPartId($partId = null, $comparison = null)
    {
        if (is_array($partId)) {
            $useMinMax = false;
            if (isset($partId['min'])) {
                $this->addUsingAlias(ItemTableMap::COL_PART_ID, $partId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($partId['max'])) {
                $this->addUsingAlias(ItemTableMap::COL_PART_ID, $partId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ItemTableMap::COL_PART_ID, $partId, $comparison);
    }

    /**
     * Filter the query on the hit_point column
     *
     * Example usage:
     * <code>
     * $query->filterByHitPoint(1234); // WHERE hit_point = 1234
     * $query->filterByHitPoint(array(12, 34)); // WHERE hit_point IN (12, 34)
     * $query->filterByHitPoint(array('min' => 12)); // WHERE hit_point > 12
     * </code>
     *
     * @param     mixed $hitPoint The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildItemQuery The current query, for fluid interface
     */
    public function filterByHitPoint($hitPoint = null, $comparison = null)
    {
        if (is_array($hitPoint)) {
            $useMinMax = false;
            if (isset($hitPoint['min'])) {
                $this->addUsingAlias(ItemTableMap::COL_HIT_POINT, $hitPoint['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($hitPoint['max'])) {
                $this->addUsingAlias(ItemTableMap::COL_HIT_POINT, $hitPoint['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ItemTableMap::COL_HIT_POINT, $hitPoint, $comparison);
    }

    /**
     * Filter the query on the attack_point column
     *
     * Example usage:
     * <code>
     * $query->filterByAttackPoint(1234); // WHERE attack_point = 1234
     * $query->filterByAttackPoint(array(12, 34)); // WHERE attack_point IN (12, 34)
     * $query->filterByAttackPoint(array('min' => 12)); // WHERE attack_point > 12
     * </code>
     *
     * @param     mixed $attackPoint The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildItemQuery The current query, for fluid interface
     */
    public function filterByAttackPoint($attackPoint = null, $comparison = null)
    {
        if (is_array($attackPoint)) {
            $useMinMax = false;
            if (isset($attackPoint['min'])) {
                $this->addUsingAlias(ItemTableMap::COL_ATTACK_POINT, $attackPoint['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($attackPoint['max'])) {
                $this->addUsingAlias(ItemTableMap::COL_ATTACK_POINT, $attackPoint['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ItemTableMap::COL_ATTACK_POINT, $attackPoint, $comparison);
    }

    /**
     * Filter the query on the defense_point column
     *
     * Example usage:
     * <code>
     * $query->filterByDefensePoint(1234); // WHERE defense_point = 1234
     * $query->filterByDefensePoint(array(12, 34)); // WHERE defense_point IN (12, 34)
     * $query->filterByDefensePoint(array('min' => 12)); // WHERE defense_point > 12
     * </code>
     *
     * @param     mixed $defensePoint The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildItemQuery The current query, for fluid interface
     */
    public function filterByDefensePoint($defensePoint = null, $comparison = null)
    {
        if (is_array($defensePoint)) {
            $useMinMax = false;
            if (isset($defensePoint['min'])) {
                $this->addUsingAlias(ItemTableMap::COL_DEFENSE_POINT, $defensePoint['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($defensePoint['max'])) {
                $this->addUsingAlias(ItemTableMap::COL_DEFENSE_POINT, $defensePoint['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ItemTableMap::COL_DEFENSE_POINT, $defensePoint, $comparison);
    }

    /**
     * Filter the query by a related \app\model\Part object
     *
     * @param \app\model\Part|ObjectCollection $part The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildItemQuery The current query, for fluid interface
     */
    public function filterByPart($part, $comparison = null)
    {
        if ($part instanceof \app\model\Part) {
            return $this
                ->addUsingAlias(ItemTableMap::COL_PART_ID, $part->getId(), $comparison);
        } elseif ($part instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ItemTableMap::COL_PART_ID, $part->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPart() only accepts arguments of type \app\model\Part or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Part relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildItemQuery The current query, for fluid interface
     */
    public function joinPart($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Part');

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
            $this->addJoinObject($join, 'Part');
        }

        return $this;
    }

    /**
     * Use the Part relation Part object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PartQuery A secondary query class using the current class as primary query
     */
    public function usePartQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPart($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Part', '\app\model\PartQuery');
    }

    /**
     * Filter the query by a related \app\model\Proprium object
     *
     * @param \app\model\Proprium|ObjectCollection $proprium The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildItemQuery The current query, for fluid interface
     */
    public function filterByProprium($proprium, $comparison = null)
    {
        if ($proprium instanceof \app\model\Proprium) {
            return $this
                ->addUsingAlias(ItemTableMap::COL_PROPRIUM_ID, $proprium->getId(), $comparison);
        } elseif ($proprium instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ItemTableMap::COL_PROPRIUM_ID, $proprium->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByProprium() only accepts arguments of type \app\model\Proprium or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Proprium relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildItemQuery The current query, for fluid interface
     */
    public function joinProprium($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Proprium');

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
            $this->addJoinObject($join, 'Proprium');
        }

        return $this;
    }

    /**
     * Use the Proprium relation Proprium object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PropriumQuery A secondary query class using the current class as primary query
     */
    public function usePropriumQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinProprium($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Proprium', '\app\model\PropriumQuery');
    }

    /**
     * Filter the query by a related \app\model\PlayerItem object
     *
     * @param \app\model\PlayerItem|ObjectCollection $playerItem the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildItemQuery The current query, for fluid interface
     */
    public function filterByPlayerItem($playerItem, $comparison = null)
    {
        if ($playerItem instanceof \app\model\PlayerItem) {
            return $this
                ->addUsingAlias(ItemTableMap::COL_ID, $playerItem->getItemId(), $comparison);
        } elseif ($playerItem instanceof ObjectCollection) {
            return $this
                ->usePlayerItemQuery()
                ->filterByPrimaryKeys($playerItem->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlayerItem() only accepts arguments of type \app\model\PlayerItem or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerItem relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildItemQuery The current query, for fluid interface
     */
    public function joinPlayerItem($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerItem');

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
            $this->addJoinObject($join, 'PlayerItem');
        }

        return $this;
    }

    /**
     * Use the PlayerItem relation PlayerItem object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerItemQuery A secondary query class using the current class as primary query
     */
    public function usePlayerItemQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerItem($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerItem', '\app\model\PlayerItemQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildItem $item Object to remove from the list of results
     *
     * @return $this|ChildItemQuery The current query, for fluid interface
     */
    public function prune($item = null)
    {
        if ($item) {
            $this->addUsingAlias(ItemTableMap::COL_ID, $item->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the item table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ItemTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ItemTableMap::clearInstancePool();
            ItemTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ItemTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ItemTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ItemTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ItemTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ItemQuery
