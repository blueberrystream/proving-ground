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
use app\model\Player as ChildPlayer;
use app\model\PlayerQuery as ChildPlayerQuery;
use app\model\Map\PlayerTableMap;

/**
 * Base class that represents a query for the 'player' table.
 *
 *
 *
 * @method     ChildPlayerQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPlayerQuery orderByName($order = Criteria::ASC) Order by the name column
 *
 * @method     ChildPlayerQuery groupById() Group by the id column
 * @method     ChildPlayerQuery groupByName() Group by the name column
 *
 * @method     ChildPlayerQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlayerQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlayerQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlayerQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPlayerQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPlayerQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPlayerQuery leftJoinPlayerItem($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerItem relation
 * @method     ChildPlayerQuery rightJoinPlayerItem($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerItem relation
 * @method     ChildPlayerQuery innerJoinPlayerItem($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerItem relation
 *
 * @method     ChildPlayerQuery joinWithPlayerItem($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerItem relation
 *
 * @method     ChildPlayerQuery leftJoinWithPlayerItem() Adds a LEFT JOIN clause and with to the query using the PlayerItem relation
 * @method     ChildPlayerQuery rightJoinWithPlayerItem() Adds a RIGHT JOIN clause and with to the query using the PlayerItem relation
 * @method     ChildPlayerQuery innerJoinWithPlayerItem() Adds a INNER JOIN clause and with to the query using the PlayerItem relation
 *
 * @method     ChildPlayerQuery leftJoinPlayerEquipment($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerEquipment relation
 * @method     ChildPlayerQuery rightJoinPlayerEquipment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerEquipment relation
 * @method     ChildPlayerQuery innerJoinPlayerEquipment($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerEquipment relation
 *
 * @method     ChildPlayerQuery joinWithPlayerEquipment($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerEquipment relation
 *
 * @method     ChildPlayerQuery leftJoinWithPlayerEquipment() Adds a LEFT JOIN clause and with to the query using the PlayerEquipment relation
 * @method     ChildPlayerQuery rightJoinWithPlayerEquipment() Adds a RIGHT JOIN clause and with to the query using the PlayerEquipment relation
 * @method     ChildPlayerQuery innerJoinWithPlayerEquipment() Adds a INNER JOIN clause and with to the query using the PlayerEquipment relation
 *
 * @method     ChildPlayerQuery leftJoinPlayerDeck($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerDeck relation
 * @method     ChildPlayerQuery rightJoinPlayerDeck($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerDeck relation
 * @method     ChildPlayerQuery innerJoinPlayerDeck($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerDeck relation
 *
 * @method     ChildPlayerQuery joinWithPlayerDeck($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerDeck relation
 *
 * @method     ChildPlayerQuery leftJoinWithPlayerDeck() Adds a LEFT JOIN clause and with to the query using the PlayerDeck relation
 * @method     ChildPlayerQuery rightJoinWithPlayerDeck() Adds a RIGHT JOIN clause and with to the query using the PlayerDeck relation
 * @method     ChildPlayerQuery innerJoinWithPlayerDeck() Adds a INNER JOIN clause and with to the query using the PlayerDeck relation
 *
 * @method     ChildPlayerQuery leftJoinPlayerBattleLogRelatedByPlayerId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerBattleLogRelatedByPlayerId relation
 * @method     ChildPlayerQuery rightJoinPlayerBattleLogRelatedByPlayerId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerBattleLogRelatedByPlayerId relation
 * @method     ChildPlayerQuery innerJoinPlayerBattleLogRelatedByPlayerId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerBattleLogRelatedByPlayerId relation
 *
 * @method     ChildPlayerQuery joinWithPlayerBattleLogRelatedByPlayerId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerBattleLogRelatedByPlayerId relation
 *
 * @method     ChildPlayerQuery leftJoinWithPlayerBattleLogRelatedByPlayerId() Adds a LEFT JOIN clause and with to the query using the PlayerBattleLogRelatedByPlayerId relation
 * @method     ChildPlayerQuery rightJoinWithPlayerBattleLogRelatedByPlayerId() Adds a RIGHT JOIN clause and with to the query using the PlayerBattleLogRelatedByPlayerId relation
 * @method     ChildPlayerQuery innerJoinWithPlayerBattleLogRelatedByPlayerId() Adds a INNER JOIN clause and with to the query using the PlayerBattleLogRelatedByPlayerId relation
 *
 * @method     ChildPlayerQuery leftJoinPlayerBattleLogRelatedByEnemyPlayerId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerBattleLogRelatedByEnemyPlayerId relation
 * @method     ChildPlayerQuery rightJoinPlayerBattleLogRelatedByEnemyPlayerId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerBattleLogRelatedByEnemyPlayerId relation
 * @method     ChildPlayerQuery innerJoinPlayerBattleLogRelatedByEnemyPlayerId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerBattleLogRelatedByEnemyPlayerId relation
 *
 * @method     ChildPlayerQuery joinWithPlayerBattleLogRelatedByEnemyPlayerId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerBattleLogRelatedByEnemyPlayerId relation
 *
 * @method     ChildPlayerQuery leftJoinWithPlayerBattleLogRelatedByEnemyPlayerId() Adds a LEFT JOIN clause and with to the query using the PlayerBattleLogRelatedByEnemyPlayerId relation
 * @method     ChildPlayerQuery rightJoinWithPlayerBattleLogRelatedByEnemyPlayerId() Adds a RIGHT JOIN clause and with to the query using the PlayerBattleLogRelatedByEnemyPlayerId relation
 * @method     ChildPlayerQuery innerJoinWithPlayerBattleLogRelatedByEnemyPlayerId() Adds a INNER JOIN clause and with to the query using the PlayerBattleLogRelatedByEnemyPlayerId relation
 *
 * @method     \app\model\PlayerItemQuery|\app\model\PlayerEquipmentQuery|\app\model\PlayerDeckQuery|\app\model\PlayerBattleLogQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPlayer findOne(ConnectionInterface $con = null) Return the first ChildPlayer matching the query
 * @method     ChildPlayer findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlayer matching the query, or a new ChildPlayer object populated from the query conditions when no match is found
 *
 * @method     ChildPlayer findOneById(int $id) Return the first ChildPlayer filtered by the id column
 * @method     ChildPlayer findOneByName(string $name) Return the first ChildPlayer filtered by the name column *

 * @method     ChildPlayer requirePk($key, ConnectionInterface $con = null) Return the ChildPlayer by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayer requireOne(ConnectionInterface $con = null) Return the first ChildPlayer matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayer requireOneById(int $id) Return the first ChildPlayer filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayer requireOneByName(string $name) Return the first ChildPlayer filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayer[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlayer objects based on current ModelCriteria
 * @method     ChildPlayer[]|ObjectCollection findById(int $id) Return ChildPlayer objects filtered by the id column
 * @method     ChildPlayer[]|ObjectCollection findByName(string $name) Return ChildPlayer objects filtered by the name column
 * @method     ChildPlayer[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlayerQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \app\model\Base\PlayerQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\app\\model\\Player', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlayerQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlayerQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlayerQuery) {
            return $criteria;
        }
        $query = new ChildPlayerQuery();
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
     * @return ChildPlayer|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlayerTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PlayerTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPlayer A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name FROM player WHERE id = :p0';
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
            /** @var ChildPlayer $obj */
            $obj = new ChildPlayer();
            $obj->hydrate($row);
            PlayerTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPlayer|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PlayerTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PlayerTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PlayerTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PlayerTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query by a related \app\model\PlayerItem object
     *
     * @param \app\model\PlayerItem|ObjectCollection $playerItem the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByPlayerItem($playerItem, $comparison = null)
    {
        if ($playerItem instanceof \app\model\PlayerItem) {
            return $this
                ->addUsingAlias(PlayerTableMap::COL_ID, $playerItem->getPlayerId(), $comparison);
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
     * @return $this|ChildPlayerQuery The current query, for fluid interface
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
     * Filter the query by a related \app\model\PlayerEquipment object
     *
     * @param \app\model\PlayerEquipment|ObjectCollection $playerEquipment the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByPlayerEquipment($playerEquipment, $comparison = null)
    {
        if ($playerEquipment instanceof \app\model\PlayerEquipment) {
            return $this
                ->addUsingAlias(PlayerTableMap::COL_ID, $playerEquipment->getPlayerId(), $comparison);
        } elseif ($playerEquipment instanceof ObjectCollection) {
            return $this
                ->usePlayerEquipmentQuery()
                ->filterByPrimaryKeys($playerEquipment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlayerEquipment() only accepts arguments of type \app\model\PlayerEquipment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerEquipment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function joinPlayerEquipment($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerEquipment');

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
            $this->addJoinObject($join, 'PlayerEquipment');
        }

        return $this;
    }

    /**
     * Use the PlayerEquipment relation PlayerEquipment object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerEquipmentQuery A secondary query class using the current class as primary query
     */
    public function usePlayerEquipmentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerEquipment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerEquipment', '\app\model\PlayerEquipmentQuery');
    }

    /**
     * Filter the query by a related \app\model\PlayerDeck object
     *
     * @param \app\model\PlayerDeck|ObjectCollection $playerDeck the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByPlayerDeck($playerDeck, $comparison = null)
    {
        if ($playerDeck instanceof \app\model\PlayerDeck) {
            return $this
                ->addUsingAlias(PlayerTableMap::COL_ID, $playerDeck->getPlayerId(), $comparison);
        } elseif ($playerDeck instanceof ObjectCollection) {
            return $this
                ->usePlayerDeckQuery()
                ->filterByPrimaryKeys($playerDeck->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlayerDeck() only accepts arguments of type \app\model\PlayerDeck or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerDeck relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function joinPlayerDeck($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerDeck');

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
            $this->addJoinObject($join, 'PlayerDeck');
        }

        return $this;
    }

    /**
     * Use the PlayerDeck relation PlayerDeck object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerDeckQuery A secondary query class using the current class as primary query
     */
    public function usePlayerDeckQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerDeck($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerDeck', '\app\model\PlayerDeckQuery');
    }

    /**
     * Filter the query by a related \app\model\PlayerBattleLog object
     *
     * @param \app\model\PlayerBattleLog|ObjectCollection $playerBattleLog the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByPlayerBattleLogRelatedByPlayerId($playerBattleLog, $comparison = null)
    {
        if ($playerBattleLog instanceof \app\model\PlayerBattleLog) {
            return $this
                ->addUsingAlias(PlayerTableMap::COL_ID, $playerBattleLog->getPlayerId(), $comparison);
        } elseif ($playerBattleLog instanceof ObjectCollection) {
            return $this
                ->usePlayerBattleLogRelatedByPlayerIdQuery()
                ->filterByPrimaryKeys($playerBattleLog->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlayerBattleLogRelatedByPlayerId() only accepts arguments of type \app\model\PlayerBattleLog or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerBattleLogRelatedByPlayerId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function joinPlayerBattleLogRelatedByPlayerId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerBattleLogRelatedByPlayerId');

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
            $this->addJoinObject($join, 'PlayerBattleLogRelatedByPlayerId');
        }

        return $this;
    }

    /**
     * Use the PlayerBattleLogRelatedByPlayerId relation PlayerBattleLog object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerBattleLogQuery A secondary query class using the current class as primary query
     */
    public function usePlayerBattleLogRelatedByPlayerIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerBattleLogRelatedByPlayerId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerBattleLogRelatedByPlayerId', '\app\model\PlayerBattleLogQuery');
    }

    /**
     * Filter the query by a related \app\model\PlayerBattleLog object
     *
     * @param \app\model\PlayerBattleLog|ObjectCollection $playerBattleLog the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByPlayerBattleLogRelatedByEnemyPlayerId($playerBattleLog, $comparison = null)
    {
        if ($playerBattleLog instanceof \app\model\PlayerBattleLog) {
            return $this
                ->addUsingAlias(PlayerTableMap::COL_ID, $playerBattleLog->getEnemyPlayerId(), $comparison);
        } elseif ($playerBattleLog instanceof ObjectCollection) {
            return $this
                ->usePlayerBattleLogRelatedByEnemyPlayerIdQuery()
                ->filterByPrimaryKeys($playerBattleLog->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlayerBattleLogRelatedByEnemyPlayerId() only accepts arguments of type \app\model\PlayerBattleLog or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerBattleLogRelatedByEnemyPlayerId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function joinPlayerBattleLogRelatedByEnemyPlayerId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerBattleLogRelatedByEnemyPlayerId');

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
            $this->addJoinObject($join, 'PlayerBattleLogRelatedByEnemyPlayerId');
        }

        return $this;
    }

    /**
     * Use the PlayerBattleLogRelatedByEnemyPlayerId relation PlayerBattleLog object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerBattleLogQuery A secondary query class using the current class as primary query
     */
    public function usePlayerBattleLogRelatedByEnemyPlayerIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerBattleLogRelatedByEnemyPlayerId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerBattleLogRelatedByEnemyPlayerId', '\app\model\PlayerBattleLogQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPlayer $player Object to remove from the list of results
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function prune($player = null)
    {
        if ($player) {
            $this->addUsingAlias(PlayerTableMap::COL_ID, $player->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the player table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlayerTableMap::clearInstancePool();
            PlayerTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlayerTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlayerTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlayerTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PlayerQuery
