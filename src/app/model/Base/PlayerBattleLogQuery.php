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
use app\model\PlayerBattleLog as ChildPlayerBattleLog;
use app\model\PlayerBattleLogQuery as ChildPlayerBattleLogQuery;
use app\model\Map\PlayerBattleLogTableMap;

/**
 * Base class that represents a query for the 'player_battle_log' table.
 *
 *
 *
 * @method     ChildPlayerBattleLogQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPlayerBattleLogQuery orderByPlayerId($order = Criteria::ASC) Order by the player_id column
 * @method     ChildPlayerBattleLogQuery orderByEnemyPlayerId($order = Criteria::ASC) Order by the enemy_player_id column
 * @method     ChildPlayerBattleLogQuery orderByResult($order = Criteria::ASC) Order by the result column
 * @method     ChildPlayerBattleLogQuery orderByChallenged($order = Criteria::ASC) Order by the challenged column
 * @method     ChildPlayerBattleLogQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildPlayerBattleLogQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildPlayerBattleLogQuery groupById() Group by the id column
 * @method     ChildPlayerBattleLogQuery groupByPlayerId() Group by the player_id column
 * @method     ChildPlayerBattleLogQuery groupByEnemyPlayerId() Group by the enemy_player_id column
 * @method     ChildPlayerBattleLogQuery groupByResult() Group by the result column
 * @method     ChildPlayerBattleLogQuery groupByChallenged() Group by the challenged column
 * @method     ChildPlayerBattleLogQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildPlayerBattleLogQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildPlayerBattleLogQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlayerBattleLogQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlayerBattleLogQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlayerBattleLogQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPlayerBattleLogQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPlayerBattleLogQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPlayerBattleLogQuery leftJoinPlayerRelatedByPlayerId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerRelatedByPlayerId relation
 * @method     ChildPlayerBattleLogQuery rightJoinPlayerRelatedByPlayerId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerRelatedByPlayerId relation
 * @method     ChildPlayerBattleLogQuery innerJoinPlayerRelatedByPlayerId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerRelatedByPlayerId relation
 *
 * @method     ChildPlayerBattleLogQuery joinWithPlayerRelatedByPlayerId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerRelatedByPlayerId relation
 *
 * @method     ChildPlayerBattleLogQuery leftJoinWithPlayerRelatedByPlayerId() Adds a LEFT JOIN clause and with to the query using the PlayerRelatedByPlayerId relation
 * @method     ChildPlayerBattleLogQuery rightJoinWithPlayerRelatedByPlayerId() Adds a RIGHT JOIN clause and with to the query using the PlayerRelatedByPlayerId relation
 * @method     ChildPlayerBattleLogQuery innerJoinWithPlayerRelatedByPlayerId() Adds a INNER JOIN clause and with to the query using the PlayerRelatedByPlayerId relation
 *
 * @method     ChildPlayerBattleLogQuery leftJoinPlayerRelatedByEnemyPlayerId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerRelatedByEnemyPlayerId relation
 * @method     ChildPlayerBattleLogQuery rightJoinPlayerRelatedByEnemyPlayerId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerRelatedByEnemyPlayerId relation
 * @method     ChildPlayerBattleLogQuery innerJoinPlayerRelatedByEnemyPlayerId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerRelatedByEnemyPlayerId relation
 *
 * @method     ChildPlayerBattleLogQuery joinWithPlayerRelatedByEnemyPlayerId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerRelatedByEnemyPlayerId relation
 *
 * @method     ChildPlayerBattleLogQuery leftJoinWithPlayerRelatedByEnemyPlayerId() Adds a LEFT JOIN clause and with to the query using the PlayerRelatedByEnemyPlayerId relation
 * @method     ChildPlayerBattleLogQuery rightJoinWithPlayerRelatedByEnemyPlayerId() Adds a RIGHT JOIN clause and with to the query using the PlayerRelatedByEnemyPlayerId relation
 * @method     ChildPlayerBattleLogQuery innerJoinWithPlayerRelatedByEnemyPlayerId() Adds a INNER JOIN clause and with to the query using the PlayerRelatedByEnemyPlayerId relation
 *
 * @method     \app\model\PlayerQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPlayerBattleLog findOne(ConnectionInterface $con = null) Return the first ChildPlayerBattleLog matching the query
 * @method     ChildPlayerBattleLog findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlayerBattleLog matching the query, or a new ChildPlayerBattleLog object populated from the query conditions when no match is found
 *
 * @method     ChildPlayerBattleLog findOneById(int $id) Return the first ChildPlayerBattleLog filtered by the id column
 * @method     ChildPlayerBattleLog findOneByPlayerId(int $player_id) Return the first ChildPlayerBattleLog filtered by the player_id column
 * @method     ChildPlayerBattleLog findOneByEnemyPlayerId(int $enemy_player_id) Return the first ChildPlayerBattleLog filtered by the enemy_player_id column
 * @method     ChildPlayerBattleLog findOneByResult(int $result) Return the first ChildPlayerBattleLog filtered by the result column
 * @method     ChildPlayerBattleLog findOneByChallenged(boolean $challenged) Return the first ChildPlayerBattleLog filtered by the challenged column
 * @method     ChildPlayerBattleLog findOneByCreatedAt(string $created_at) Return the first ChildPlayerBattleLog filtered by the created_at column
 * @method     ChildPlayerBattleLog findOneByUpdatedAt(string $updated_at) Return the first ChildPlayerBattleLog filtered by the updated_at column *

 * @method     ChildPlayerBattleLog requirePk($key, ConnectionInterface $con = null) Return the ChildPlayerBattleLog by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerBattleLog requireOne(ConnectionInterface $con = null) Return the first ChildPlayerBattleLog matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayerBattleLog requireOneById(int $id) Return the first ChildPlayerBattleLog filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerBattleLog requireOneByPlayerId(int $player_id) Return the first ChildPlayerBattleLog filtered by the player_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerBattleLog requireOneByEnemyPlayerId(int $enemy_player_id) Return the first ChildPlayerBattleLog filtered by the enemy_player_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerBattleLog requireOneByResult(int $result) Return the first ChildPlayerBattleLog filtered by the result column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerBattleLog requireOneByChallenged(boolean $challenged) Return the first ChildPlayerBattleLog filtered by the challenged column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerBattleLog requireOneByCreatedAt(string $created_at) Return the first ChildPlayerBattleLog filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerBattleLog requireOneByUpdatedAt(string $updated_at) Return the first ChildPlayerBattleLog filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayerBattleLog[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlayerBattleLog objects based on current ModelCriteria
 * @method     ChildPlayerBattleLog[]|ObjectCollection findById(int $id) Return ChildPlayerBattleLog objects filtered by the id column
 * @method     ChildPlayerBattleLog[]|ObjectCollection findByPlayerId(int $player_id) Return ChildPlayerBattleLog objects filtered by the player_id column
 * @method     ChildPlayerBattleLog[]|ObjectCollection findByEnemyPlayerId(int $enemy_player_id) Return ChildPlayerBattleLog objects filtered by the enemy_player_id column
 * @method     ChildPlayerBattleLog[]|ObjectCollection findByResult(int $result) Return ChildPlayerBattleLog objects filtered by the result column
 * @method     ChildPlayerBattleLog[]|ObjectCollection findByChallenged(boolean $challenged) Return ChildPlayerBattleLog objects filtered by the challenged column
 * @method     ChildPlayerBattleLog[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildPlayerBattleLog objects filtered by the created_at column
 * @method     ChildPlayerBattleLog[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildPlayerBattleLog objects filtered by the updated_at column
 * @method     ChildPlayerBattleLog[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlayerBattleLogQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \app\model\Base\PlayerBattleLogQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\app\\model\\PlayerBattleLog', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlayerBattleLogQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlayerBattleLogQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlayerBattleLogQuery) {
            return $criteria;
        }
        $query = new ChildPlayerBattleLogQuery();
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
     * @return ChildPlayerBattleLog|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlayerBattleLogTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PlayerBattleLogTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPlayerBattleLog A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, player_id, enemy_player_id, result, challenged, created_at, updated_at FROM player_battle_log WHERE id = :p0';
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
            /** @var ChildPlayerBattleLog $obj */
            $obj = new ChildPlayerBattleLog();
            $obj->hydrate($row);
            PlayerBattleLogTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPlayerBattleLog|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPlayerBattleLogQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PlayerBattleLogTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlayerBattleLogQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PlayerBattleLogTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildPlayerBattleLogQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PlayerBattleLogTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PlayerBattleLogTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerBattleLogTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the player_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPlayerId(1234); // WHERE player_id = 1234
     * $query->filterByPlayerId(array(12, 34)); // WHERE player_id IN (12, 34)
     * $query->filterByPlayerId(array('min' => 12)); // WHERE player_id > 12
     * </code>
     *
     * @see       filterByPlayerRelatedByPlayerId()
     *
     * @param     mixed $playerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerBattleLogQuery The current query, for fluid interface
     */
    public function filterByPlayerId($playerId = null, $comparison = null)
    {
        if (is_array($playerId)) {
            $useMinMax = false;
            if (isset($playerId['min'])) {
                $this->addUsingAlias(PlayerBattleLogTableMap::COL_PLAYER_ID, $playerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerId['max'])) {
                $this->addUsingAlias(PlayerBattleLogTableMap::COL_PLAYER_ID, $playerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerBattleLogTableMap::COL_PLAYER_ID, $playerId, $comparison);
    }

    /**
     * Filter the query on the enemy_player_id column
     *
     * Example usage:
     * <code>
     * $query->filterByEnemyPlayerId(1234); // WHERE enemy_player_id = 1234
     * $query->filterByEnemyPlayerId(array(12, 34)); // WHERE enemy_player_id IN (12, 34)
     * $query->filterByEnemyPlayerId(array('min' => 12)); // WHERE enemy_player_id > 12
     * </code>
     *
     * @see       filterByPlayerRelatedByEnemyPlayerId()
     *
     * @param     mixed $enemyPlayerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerBattleLogQuery The current query, for fluid interface
     */
    public function filterByEnemyPlayerId($enemyPlayerId = null, $comparison = null)
    {
        if (is_array($enemyPlayerId)) {
            $useMinMax = false;
            if (isset($enemyPlayerId['min'])) {
                $this->addUsingAlias(PlayerBattleLogTableMap::COL_ENEMY_PLAYER_ID, $enemyPlayerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($enemyPlayerId['max'])) {
                $this->addUsingAlias(PlayerBattleLogTableMap::COL_ENEMY_PLAYER_ID, $enemyPlayerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerBattleLogTableMap::COL_ENEMY_PLAYER_ID, $enemyPlayerId, $comparison);
    }

    /**
     * Filter the query on the result column
     *
     * Example usage:
     * <code>
     * $query->filterByResult(1234); // WHERE result = 1234
     * $query->filterByResult(array(12, 34)); // WHERE result IN (12, 34)
     * $query->filterByResult(array('min' => 12)); // WHERE result > 12
     * </code>
     *
     * @param     mixed $result The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerBattleLogQuery The current query, for fluid interface
     */
    public function filterByResult($result = null, $comparison = null)
    {
        if (is_array($result)) {
            $useMinMax = false;
            if (isset($result['min'])) {
                $this->addUsingAlias(PlayerBattleLogTableMap::COL_RESULT, $result['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($result['max'])) {
                $this->addUsingAlias(PlayerBattleLogTableMap::COL_RESULT, $result['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerBattleLogTableMap::COL_RESULT, $result, $comparison);
    }

    /**
     * Filter the query on the challenged column
     *
     * Example usage:
     * <code>
     * $query->filterByChallenged(true); // WHERE challenged = true
     * $query->filterByChallenged('yes'); // WHERE challenged = true
     * </code>
     *
     * @param     boolean|string $challenged The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerBattleLogQuery The current query, for fluid interface
     */
    public function filterByChallenged($challenged = null, $comparison = null)
    {
        if (is_string($challenged)) {
            $challenged = in_array(strtolower($challenged), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PlayerBattleLogTableMap::COL_CHALLENGED, $challenged, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerBattleLogQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PlayerBattleLogTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PlayerBattleLogTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerBattleLogTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerBattleLogQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PlayerBattleLogTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PlayerBattleLogTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerBattleLogTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \app\model\Player object
     *
     * @param \app\model\Player|ObjectCollection $player The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerBattleLogQuery The current query, for fluid interface
     */
    public function filterByPlayerRelatedByPlayerId($player, $comparison = null)
    {
        if ($player instanceof \app\model\Player) {
            return $this
                ->addUsingAlias(PlayerBattleLogTableMap::COL_PLAYER_ID, $player->getId(), $comparison);
        } elseif ($player instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerBattleLogTableMap::COL_PLAYER_ID, $player->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPlayerRelatedByPlayerId() only accepts arguments of type \app\model\Player or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerRelatedByPlayerId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerBattleLogQuery The current query, for fluid interface
     */
    public function joinPlayerRelatedByPlayerId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerRelatedByPlayerId');

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
            $this->addJoinObject($join, 'PlayerRelatedByPlayerId');
        }

        return $this;
    }

    /**
     * Use the PlayerRelatedByPlayerId relation Player object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerQuery A secondary query class using the current class as primary query
     */
    public function usePlayerRelatedByPlayerIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerRelatedByPlayerId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerRelatedByPlayerId', '\app\model\PlayerQuery');
    }

    /**
     * Filter the query by a related \app\model\Player object
     *
     * @param \app\model\Player|ObjectCollection $player The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerBattleLogQuery The current query, for fluid interface
     */
    public function filterByPlayerRelatedByEnemyPlayerId($player, $comparison = null)
    {
        if ($player instanceof \app\model\Player) {
            return $this
                ->addUsingAlias(PlayerBattleLogTableMap::COL_ENEMY_PLAYER_ID, $player->getId(), $comparison);
        } elseif ($player instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerBattleLogTableMap::COL_ENEMY_PLAYER_ID, $player->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPlayerRelatedByEnemyPlayerId() only accepts arguments of type \app\model\Player or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerRelatedByEnemyPlayerId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerBattleLogQuery The current query, for fluid interface
     */
    public function joinPlayerRelatedByEnemyPlayerId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerRelatedByEnemyPlayerId');

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
            $this->addJoinObject($join, 'PlayerRelatedByEnemyPlayerId');
        }

        return $this;
    }

    /**
     * Use the PlayerRelatedByEnemyPlayerId relation Player object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerQuery A secondary query class using the current class as primary query
     */
    public function usePlayerRelatedByEnemyPlayerIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerRelatedByEnemyPlayerId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerRelatedByEnemyPlayerId', '\app\model\PlayerQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPlayerBattleLog $playerBattleLog Object to remove from the list of results
     *
     * @return $this|ChildPlayerBattleLogQuery The current query, for fluid interface
     */
    public function prune($playerBattleLog = null)
    {
        if ($playerBattleLog) {
            $this->addUsingAlias(PlayerBattleLogTableMap::COL_ID, $playerBattleLog->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the player_battle_log table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerBattleLogTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlayerBattleLogTableMap::clearInstancePool();
            PlayerBattleLogTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerBattleLogTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlayerBattleLogTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlayerBattleLogTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlayerBattleLogTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildPlayerBattleLogQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PlayerBattleLogTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildPlayerBattleLogQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PlayerBattleLogTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildPlayerBattleLogQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PlayerBattleLogTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildPlayerBattleLogQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PlayerBattleLogTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildPlayerBattleLogQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PlayerBattleLogTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildPlayerBattleLogQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PlayerBattleLogTableMap::COL_CREATED_AT);
    }

} // PlayerBattleLogQuery
