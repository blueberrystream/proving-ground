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
use app\model\PlayerDeck as ChildPlayerDeck;
use app\model\PlayerDeckQuery as ChildPlayerDeckQuery;
use app\model\Map\PlayerDeckTableMap;

/**
 * Base class that represents a query for the 'player_deck' table.
 *
 *
 *
 * @method     ChildPlayerDeckQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPlayerDeckQuery orderByPlayerId($order = Criteria::ASC) Order by the player_id column
 * @method     ChildPlayerDeckQuery orderByHeadPlayerItemId($order = Criteria::ASC) Order by the head_player_item_id column
 * @method     ChildPlayerDeckQuery orderByLeftArmPlayerItemId($order = Criteria::ASC) Order by the left_arm_player_item_id column
 * @method     ChildPlayerDeckQuery orderByRightArmPlayerItemId($order = Criteria::ASC) Order by the right_arm_player_item_id column
 * @method     ChildPlayerDeckQuery orderByLeftLegPlayerItemId($order = Criteria::ASC) Order by the left_leg_player_item_id column
 * @method     ChildPlayerDeckQuery orderByRightLegPlayerItemId($order = Criteria::ASC) Order by the right_leg_player_item_id column
 * @method     ChildPlayerDeckQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildPlayerDeckQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildPlayerDeckQuery groupById() Group by the id column
 * @method     ChildPlayerDeckQuery groupByPlayerId() Group by the player_id column
 * @method     ChildPlayerDeckQuery groupByHeadPlayerItemId() Group by the head_player_item_id column
 * @method     ChildPlayerDeckQuery groupByLeftArmPlayerItemId() Group by the left_arm_player_item_id column
 * @method     ChildPlayerDeckQuery groupByRightArmPlayerItemId() Group by the right_arm_player_item_id column
 * @method     ChildPlayerDeckQuery groupByLeftLegPlayerItemId() Group by the left_leg_player_item_id column
 * @method     ChildPlayerDeckQuery groupByRightLegPlayerItemId() Group by the right_leg_player_item_id column
 * @method     ChildPlayerDeckQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildPlayerDeckQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildPlayerDeckQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlayerDeckQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlayerDeckQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlayerDeckQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPlayerDeckQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPlayerDeckQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPlayerDeckQuery leftJoinPlayer($relationAlias = null) Adds a LEFT JOIN clause to the query using the Player relation
 * @method     ChildPlayerDeckQuery rightJoinPlayer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Player relation
 * @method     ChildPlayerDeckQuery innerJoinPlayer($relationAlias = null) Adds a INNER JOIN clause to the query using the Player relation
 *
 * @method     ChildPlayerDeckQuery joinWithPlayer($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Player relation
 *
 * @method     ChildPlayerDeckQuery leftJoinWithPlayer() Adds a LEFT JOIN clause and with to the query using the Player relation
 * @method     ChildPlayerDeckQuery rightJoinWithPlayer() Adds a RIGHT JOIN clause and with to the query using the Player relation
 * @method     ChildPlayerDeckQuery innerJoinWithPlayer() Adds a INNER JOIN clause and with to the query using the Player relation
 *
 * @method     ChildPlayerDeckQuery leftJoinPlayerItemRelatedByHeadPlayerItemId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerItemRelatedByHeadPlayerItemId relation
 * @method     ChildPlayerDeckQuery rightJoinPlayerItemRelatedByHeadPlayerItemId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerItemRelatedByHeadPlayerItemId relation
 * @method     ChildPlayerDeckQuery innerJoinPlayerItemRelatedByHeadPlayerItemId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerItemRelatedByHeadPlayerItemId relation
 *
 * @method     ChildPlayerDeckQuery joinWithPlayerItemRelatedByHeadPlayerItemId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerItemRelatedByHeadPlayerItemId relation
 *
 * @method     ChildPlayerDeckQuery leftJoinWithPlayerItemRelatedByHeadPlayerItemId() Adds a LEFT JOIN clause and with to the query using the PlayerItemRelatedByHeadPlayerItemId relation
 * @method     ChildPlayerDeckQuery rightJoinWithPlayerItemRelatedByHeadPlayerItemId() Adds a RIGHT JOIN clause and with to the query using the PlayerItemRelatedByHeadPlayerItemId relation
 * @method     ChildPlayerDeckQuery innerJoinWithPlayerItemRelatedByHeadPlayerItemId() Adds a INNER JOIN clause and with to the query using the PlayerItemRelatedByHeadPlayerItemId relation
 *
 * @method     ChildPlayerDeckQuery leftJoinPlayerItemRelatedByLeftArmPlayerItemId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerItemRelatedByLeftArmPlayerItemId relation
 * @method     ChildPlayerDeckQuery rightJoinPlayerItemRelatedByLeftArmPlayerItemId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerItemRelatedByLeftArmPlayerItemId relation
 * @method     ChildPlayerDeckQuery innerJoinPlayerItemRelatedByLeftArmPlayerItemId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerItemRelatedByLeftArmPlayerItemId relation
 *
 * @method     ChildPlayerDeckQuery joinWithPlayerItemRelatedByLeftArmPlayerItemId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerItemRelatedByLeftArmPlayerItemId relation
 *
 * @method     ChildPlayerDeckQuery leftJoinWithPlayerItemRelatedByLeftArmPlayerItemId() Adds a LEFT JOIN clause and with to the query using the PlayerItemRelatedByLeftArmPlayerItemId relation
 * @method     ChildPlayerDeckQuery rightJoinWithPlayerItemRelatedByLeftArmPlayerItemId() Adds a RIGHT JOIN clause and with to the query using the PlayerItemRelatedByLeftArmPlayerItemId relation
 * @method     ChildPlayerDeckQuery innerJoinWithPlayerItemRelatedByLeftArmPlayerItemId() Adds a INNER JOIN clause and with to the query using the PlayerItemRelatedByLeftArmPlayerItemId relation
 *
 * @method     ChildPlayerDeckQuery leftJoinPlayerItemRelatedByRightArmPlayerItemId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerItemRelatedByRightArmPlayerItemId relation
 * @method     ChildPlayerDeckQuery rightJoinPlayerItemRelatedByRightArmPlayerItemId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerItemRelatedByRightArmPlayerItemId relation
 * @method     ChildPlayerDeckQuery innerJoinPlayerItemRelatedByRightArmPlayerItemId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerItemRelatedByRightArmPlayerItemId relation
 *
 * @method     ChildPlayerDeckQuery joinWithPlayerItemRelatedByRightArmPlayerItemId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerItemRelatedByRightArmPlayerItemId relation
 *
 * @method     ChildPlayerDeckQuery leftJoinWithPlayerItemRelatedByRightArmPlayerItemId() Adds a LEFT JOIN clause and with to the query using the PlayerItemRelatedByRightArmPlayerItemId relation
 * @method     ChildPlayerDeckQuery rightJoinWithPlayerItemRelatedByRightArmPlayerItemId() Adds a RIGHT JOIN clause and with to the query using the PlayerItemRelatedByRightArmPlayerItemId relation
 * @method     ChildPlayerDeckQuery innerJoinWithPlayerItemRelatedByRightArmPlayerItemId() Adds a INNER JOIN clause and with to the query using the PlayerItemRelatedByRightArmPlayerItemId relation
 *
 * @method     ChildPlayerDeckQuery leftJoinPlayerItemRelatedByLeftLegPlayerItemId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerItemRelatedByLeftLegPlayerItemId relation
 * @method     ChildPlayerDeckQuery rightJoinPlayerItemRelatedByLeftLegPlayerItemId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerItemRelatedByLeftLegPlayerItemId relation
 * @method     ChildPlayerDeckQuery innerJoinPlayerItemRelatedByLeftLegPlayerItemId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerItemRelatedByLeftLegPlayerItemId relation
 *
 * @method     ChildPlayerDeckQuery joinWithPlayerItemRelatedByLeftLegPlayerItemId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerItemRelatedByLeftLegPlayerItemId relation
 *
 * @method     ChildPlayerDeckQuery leftJoinWithPlayerItemRelatedByLeftLegPlayerItemId() Adds a LEFT JOIN clause and with to the query using the PlayerItemRelatedByLeftLegPlayerItemId relation
 * @method     ChildPlayerDeckQuery rightJoinWithPlayerItemRelatedByLeftLegPlayerItemId() Adds a RIGHT JOIN clause and with to the query using the PlayerItemRelatedByLeftLegPlayerItemId relation
 * @method     ChildPlayerDeckQuery innerJoinWithPlayerItemRelatedByLeftLegPlayerItemId() Adds a INNER JOIN clause and with to the query using the PlayerItemRelatedByLeftLegPlayerItemId relation
 *
 * @method     ChildPlayerDeckQuery leftJoinPlayerItemRelatedByRightLegPlayerItemId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerItemRelatedByRightLegPlayerItemId relation
 * @method     ChildPlayerDeckQuery rightJoinPlayerItemRelatedByRightLegPlayerItemId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerItemRelatedByRightLegPlayerItemId relation
 * @method     ChildPlayerDeckQuery innerJoinPlayerItemRelatedByRightLegPlayerItemId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerItemRelatedByRightLegPlayerItemId relation
 *
 * @method     ChildPlayerDeckQuery joinWithPlayerItemRelatedByRightLegPlayerItemId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerItemRelatedByRightLegPlayerItemId relation
 *
 * @method     ChildPlayerDeckQuery leftJoinWithPlayerItemRelatedByRightLegPlayerItemId() Adds a LEFT JOIN clause and with to the query using the PlayerItemRelatedByRightLegPlayerItemId relation
 * @method     ChildPlayerDeckQuery rightJoinWithPlayerItemRelatedByRightLegPlayerItemId() Adds a RIGHT JOIN clause and with to the query using the PlayerItemRelatedByRightLegPlayerItemId relation
 * @method     ChildPlayerDeckQuery innerJoinWithPlayerItemRelatedByRightLegPlayerItemId() Adds a INNER JOIN clause and with to the query using the PlayerItemRelatedByRightLegPlayerItemId relation
 *
 * @method     \app\model\PlayerQuery|\app\model\PlayerItemQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPlayerDeck findOne(ConnectionInterface $con = null) Return the first ChildPlayerDeck matching the query
 * @method     ChildPlayerDeck findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlayerDeck matching the query, or a new ChildPlayerDeck object populated from the query conditions when no match is found
 *
 * @method     ChildPlayerDeck findOneById(int $id) Return the first ChildPlayerDeck filtered by the id column
 * @method     ChildPlayerDeck findOneByPlayerId(int $player_id) Return the first ChildPlayerDeck filtered by the player_id column
 * @method     ChildPlayerDeck findOneByHeadPlayerItemId(int $head_player_item_id) Return the first ChildPlayerDeck filtered by the head_player_item_id column
 * @method     ChildPlayerDeck findOneByLeftArmPlayerItemId(int $left_arm_player_item_id) Return the first ChildPlayerDeck filtered by the left_arm_player_item_id column
 * @method     ChildPlayerDeck findOneByRightArmPlayerItemId(int $right_arm_player_item_id) Return the first ChildPlayerDeck filtered by the right_arm_player_item_id column
 * @method     ChildPlayerDeck findOneByLeftLegPlayerItemId(int $left_leg_player_item_id) Return the first ChildPlayerDeck filtered by the left_leg_player_item_id column
 * @method     ChildPlayerDeck findOneByRightLegPlayerItemId(int $right_leg_player_item_id) Return the first ChildPlayerDeck filtered by the right_leg_player_item_id column
 * @method     ChildPlayerDeck findOneByCreatedAt(string $created_at) Return the first ChildPlayerDeck filtered by the created_at column
 * @method     ChildPlayerDeck findOneByUpdatedAt(string $updated_at) Return the first ChildPlayerDeck filtered by the updated_at column *

 * @method     ChildPlayerDeck requirePk($key, ConnectionInterface $con = null) Return the ChildPlayerDeck by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerDeck requireOne(ConnectionInterface $con = null) Return the first ChildPlayerDeck matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayerDeck requireOneById(int $id) Return the first ChildPlayerDeck filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerDeck requireOneByPlayerId(int $player_id) Return the first ChildPlayerDeck filtered by the player_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerDeck requireOneByHeadPlayerItemId(int $head_player_item_id) Return the first ChildPlayerDeck filtered by the head_player_item_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerDeck requireOneByLeftArmPlayerItemId(int $left_arm_player_item_id) Return the first ChildPlayerDeck filtered by the left_arm_player_item_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerDeck requireOneByRightArmPlayerItemId(int $right_arm_player_item_id) Return the first ChildPlayerDeck filtered by the right_arm_player_item_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerDeck requireOneByLeftLegPlayerItemId(int $left_leg_player_item_id) Return the first ChildPlayerDeck filtered by the left_leg_player_item_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerDeck requireOneByRightLegPlayerItemId(int $right_leg_player_item_id) Return the first ChildPlayerDeck filtered by the right_leg_player_item_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerDeck requireOneByCreatedAt(string $created_at) Return the first ChildPlayerDeck filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerDeck requireOneByUpdatedAt(string $updated_at) Return the first ChildPlayerDeck filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayerDeck[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlayerDeck objects based on current ModelCriteria
 * @method     ChildPlayerDeck[]|ObjectCollection findById(int $id) Return ChildPlayerDeck objects filtered by the id column
 * @method     ChildPlayerDeck[]|ObjectCollection findByPlayerId(int $player_id) Return ChildPlayerDeck objects filtered by the player_id column
 * @method     ChildPlayerDeck[]|ObjectCollection findByHeadPlayerItemId(int $head_player_item_id) Return ChildPlayerDeck objects filtered by the head_player_item_id column
 * @method     ChildPlayerDeck[]|ObjectCollection findByLeftArmPlayerItemId(int $left_arm_player_item_id) Return ChildPlayerDeck objects filtered by the left_arm_player_item_id column
 * @method     ChildPlayerDeck[]|ObjectCollection findByRightArmPlayerItemId(int $right_arm_player_item_id) Return ChildPlayerDeck objects filtered by the right_arm_player_item_id column
 * @method     ChildPlayerDeck[]|ObjectCollection findByLeftLegPlayerItemId(int $left_leg_player_item_id) Return ChildPlayerDeck objects filtered by the left_leg_player_item_id column
 * @method     ChildPlayerDeck[]|ObjectCollection findByRightLegPlayerItemId(int $right_leg_player_item_id) Return ChildPlayerDeck objects filtered by the right_leg_player_item_id column
 * @method     ChildPlayerDeck[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildPlayerDeck objects filtered by the created_at column
 * @method     ChildPlayerDeck[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildPlayerDeck objects filtered by the updated_at column
 * @method     ChildPlayerDeck[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlayerDeckQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \app\model\Base\PlayerDeckQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\app\\model\\PlayerDeck', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlayerDeckQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlayerDeckQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlayerDeckQuery) {
            return $criteria;
        }
        $query = new ChildPlayerDeckQuery();
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
     * @return ChildPlayerDeck|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlayerDeckTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PlayerDeckTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPlayerDeck A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, player_id, head_player_item_id, left_arm_player_item_id, right_arm_player_item_id, left_leg_player_item_id, right_leg_player_item_id, created_at, updated_at FROM player_deck WHERE id = :p0';
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
            /** @var ChildPlayerDeck $obj */
            $obj = new ChildPlayerDeck();
            $obj->hydrate($row);
            PlayerDeckTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPlayerDeck|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PlayerDeckTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PlayerDeckTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerDeckTableMap::COL_ID, $id, $comparison);
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
     * @see       filterByPlayer()
     *
     * @param     mixed $playerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByPlayerId($playerId = null, $comparison = null)
    {
        if (is_array($playerId)) {
            $useMinMax = false;
            if (isset($playerId['min'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_PLAYER_ID, $playerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerId['max'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_PLAYER_ID, $playerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerDeckTableMap::COL_PLAYER_ID, $playerId, $comparison);
    }

    /**
     * Filter the query on the head_player_item_id column
     *
     * Example usage:
     * <code>
     * $query->filterByHeadPlayerItemId(1234); // WHERE head_player_item_id = 1234
     * $query->filterByHeadPlayerItemId(array(12, 34)); // WHERE head_player_item_id IN (12, 34)
     * $query->filterByHeadPlayerItemId(array('min' => 12)); // WHERE head_player_item_id > 12
     * </code>
     *
     * @see       filterByPlayerItemRelatedByHeadPlayerItemId()
     *
     * @param     mixed $headPlayerItemId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByHeadPlayerItemId($headPlayerItemId = null, $comparison = null)
    {
        if (is_array($headPlayerItemId)) {
            $useMinMax = false;
            if (isset($headPlayerItemId['min'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_HEAD_PLAYER_ITEM_ID, $headPlayerItemId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($headPlayerItemId['max'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_HEAD_PLAYER_ITEM_ID, $headPlayerItemId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerDeckTableMap::COL_HEAD_PLAYER_ITEM_ID, $headPlayerItemId, $comparison);
    }

    /**
     * Filter the query on the left_arm_player_item_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLeftArmPlayerItemId(1234); // WHERE left_arm_player_item_id = 1234
     * $query->filterByLeftArmPlayerItemId(array(12, 34)); // WHERE left_arm_player_item_id IN (12, 34)
     * $query->filterByLeftArmPlayerItemId(array('min' => 12)); // WHERE left_arm_player_item_id > 12
     * </code>
     *
     * @see       filterByPlayerItemRelatedByLeftArmPlayerItemId()
     *
     * @param     mixed $leftArmPlayerItemId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByLeftArmPlayerItemId($leftArmPlayerItemId = null, $comparison = null)
    {
        if (is_array($leftArmPlayerItemId)) {
            $useMinMax = false;
            if (isset($leftArmPlayerItemId['min'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_LEFT_ARM_PLAYER_ITEM_ID, $leftArmPlayerItemId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($leftArmPlayerItemId['max'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_LEFT_ARM_PLAYER_ITEM_ID, $leftArmPlayerItemId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerDeckTableMap::COL_LEFT_ARM_PLAYER_ITEM_ID, $leftArmPlayerItemId, $comparison);
    }

    /**
     * Filter the query on the right_arm_player_item_id column
     *
     * Example usage:
     * <code>
     * $query->filterByRightArmPlayerItemId(1234); // WHERE right_arm_player_item_id = 1234
     * $query->filterByRightArmPlayerItemId(array(12, 34)); // WHERE right_arm_player_item_id IN (12, 34)
     * $query->filterByRightArmPlayerItemId(array('min' => 12)); // WHERE right_arm_player_item_id > 12
     * </code>
     *
     * @see       filterByPlayerItemRelatedByRightArmPlayerItemId()
     *
     * @param     mixed $rightArmPlayerItemId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByRightArmPlayerItemId($rightArmPlayerItemId = null, $comparison = null)
    {
        if (is_array($rightArmPlayerItemId)) {
            $useMinMax = false;
            if (isset($rightArmPlayerItemId['min'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_RIGHT_ARM_PLAYER_ITEM_ID, $rightArmPlayerItemId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rightArmPlayerItemId['max'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_RIGHT_ARM_PLAYER_ITEM_ID, $rightArmPlayerItemId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerDeckTableMap::COL_RIGHT_ARM_PLAYER_ITEM_ID, $rightArmPlayerItemId, $comparison);
    }

    /**
     * Filter the query on the left_leg_player_item_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLeftLegPlayerItemId(1234); // WHERE left_leg_player_item_id = 1234
     * $query->filterByLeftLegPlayerItemId(array(12, 34)); // WHERE left_leg_player_item_id IN (12, 34)
     * $query->filterByLeftLegPlayerItemId(array('min' => 12)); // WHERE left_leg_player_item_id > 12
     * </code>
     *
     * @see       filterByPlayerItemRelatedByLeftLegPlayerItemId()
     *
     * @param     mixed $leftLegPlayerItemId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByLeftLegPlayerItemId($leftLegPlayerItemId = null, $comparison = null)
    {
        if (is_array($leftLegPlayerItemId)) {
            $useMinMax = false;
            if (isset($leftLegPlayerItemId['min'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_LEFT_LEG_PLAYER_ITEM_ID, $leftLegPlayerItemId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($leftLegPlayerItemId['max'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_LEFT_LEG_PLAYER_ITEM_ID, $leftLegPlayerItemId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerDeckTableMap::COL_LEFT_LEG_PLAYER_ITEM_ID, $leftLegPlayerItemId, $comparison);
    }

    /**
     * Filter the query on the right_leg_player_item_id column
     *
     * Example usage:
     * <code>
     * $query->filterByRightLegPlayerItemId(1234); // WHERE right_leg_player_item_id = 1234
     * $query->filterByRightLegPlayerItemId(array(12, 34)); // WHERE right_leg_player_item_id IN (12, 34)
     * $query->filterByRightLegPlayerItemId(array('min' => 12)); // WHERE right_leg_player_item_id > 12
     * </code>
     *
     * @see       filterByPlayerItemRelatedByRightLegPlayerItemId()
     *
     * @param     mixed $rightLegPlayerItemId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByRightLegPlayerItemId($rightLegPlayerItemId = null, $comparison = null)
    {
        if (is_array($rightLegPlayerItemId)) {
            $useMinMax = false;
            if (isset($rightLegPlayerItemId['min'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_RIGHT_LEG_PLAYER_ITEM_ID, $rightLegPlayerItemId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rightLegPlayerItemId['max'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_RIGHT_LEG_PLAYER_ITEM_ID, $rightLegPlayerItemId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerDeckTableMap::COL_RIGHT_LEG_PLAYER_ITEM_ID, $rightLegPlayerItemId, $comparison);
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
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerDeckTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerDeckTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \app\model\Player object
     *
     * @param \app\model\Player|ObjectCollection $player The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByPlayer($player, $comparison = null)
    {
        if ($player instanceof \app\model\Player) {
            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_PLAYER_ID, $player->getId(), $comparison);
        } elseif ($player instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_PLAYER_ID, $player->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPlayer() only accepts arguments of type \app\model\Player or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Player relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function joinPlayer($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Player');

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
            $this->addJoinObject($join, 'Player');
        }

        return $this;
    }

    /**
     * Use the Player relation Player object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerQuery A secondary query class using the current class as primary query
     */
    public function usePlayerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Player', '\app\model\PlayerQuery');
    }

    /**
     * Filter the query by a related \app\model\PlayerItem object
     *
     * @param \app\model\PlayerItem|ObjectCollection $playerItem The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByPlayerItemRelatedByHeadPlayerItemId($playerItem, $comparison = null)
    {
        if ($playerItem instanceof \app\model\PlayerItem) {
            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_HEAD_PLAYER_ITEM_ID, $playerItem->getId(), $comparison);
        } elseif ($playerItem instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_HEAD_PLAYER_ITEM_ID, $playerItem->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPlayerItemRelatedByHeadPlayerItemId() only accepts arguments of type \app\model\PlayerItem or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerItemRelatedByHeadPlayerItemId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function joinPlayerItemRelatedByHeadPlayerItemId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerItemRelatedByHeadPlayerItemId');

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
            $this->addJoinObject($join, 'PlayerItemRelatedByHeadPlayerItemId');
        }

        return $this;
    }

    /**
     * Use the PlayerItemRelatedByHeadPlayerItemId relation PlayerItem object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerItemQuery A secondary query class using the current class as primary query
     */
    public function usePlayerItemRelatedByHeadPlayerItemIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPlayerItemRelatedByHeadPlayerItemId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerItemRelatedByHeadPlayerItemId', '\app\model\PlayerItemQuery');
    }

    /**
     * Filter the query by a related \app\model\PlayerItem object
     *
     * @param \app\model\PlayerItem|ObjectCollection $playerItem The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByPlayerItemRelatedByLeftArmPlayerItemId($playerItem, $comparison = null)
    {
        if ($playerItem instanceof \app\model\PlayerItem) {
            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_LEFT_ARM_PLAYER_ITEM_ID, $playerItem->getId(), $comparison);
        } elseif ($playerItem instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_LEFT_ARM_PLAYER_ITEM_ID, $playerItem->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPlayerItemRelatedByLeftArmPlayerItemId() only accepts arguments of type \app\model\PlayerItem or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerItemRelatedByLeftArmPlayerItemId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function joinPlayerItemRelatedByLeftArmPlayerItemId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerItemRelatedByLeftArmPlayerItemId');

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
            $this->addJoinObject($join, 'PlayerItemRelatedByLeftArmPlayerItemId');
        }

        return $this;
    }

    /**
     * Use the PlayerItemRelatedByLeftArmPlayerItemId relation PlayerItem object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerItemQuery A secondary query class using the current class as primary query
     */
    public function usePlayerItemRelatedByLeftArmPlayerItemIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPlayerItemRelatedByLeftArmPlayerItemId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerItemRelatedByLeftArmPlayerItemId', '\app\model\PlayerItemQuery');
    }

    /**
     * Filter the query by a related \app\model\PlayerItem object
     *
     * @param \app\model\PlayerItem|ObjectCollection $playerItem The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByPlayerItemRelatedByRightArmPlayerItemId($playerItem, $comparison = null)
    {
        if ($playerItem instanceof \app\model\PlayerItem) {
            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_RIGHT_ARM_PLAYER_ITEM_ID, $playerItem->getId(), $comparison);
        } elseif ($playerItem instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_RIGHT_ARM_PLAYER_ITEM_ID, $playerItem->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPlayerItemRelatedByRightArmPlayerItemId() only accepts arguments of type \app\model\PlayerItem or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerItemRelatedByRightArmPlayerItemId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function joinPlayerItemRelatedByRightArmPlayerItemId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerItemRelatedByRightArmPlayerItemId');

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
            $this->addJoinObject($join, 'PlayerItemRelatedByRightArmPlayerItemId');
        }

        return $this;
    }

    /**
     * Use the PlayerItemRelatedByRightArmPlayerItemId relation PlayerItem object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerItemQuery A secondary query class using the current class as primary query
     */
    public function usePlayerItemRelatedByRightArmPlayerItemIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPlayerItemRelatedByRightArmPlayerItemId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerItemRelatedByRightArmPlayerItemId', '\app\model\PlayerItemQuery');
    }

    /**
     * Filter the query by a related \app\model\PlayerItem object
     *
     * @param \app\model\PlayerItem|ObjectCollection $playerItem The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByPlayerItemRelatedByLeftLegPlayerItemId($playerItem, $comparison = null)
    {
        if ($playerItem instanceof \app\model\PlayerItem) {
            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_LEFT_LEG_PLAYER_ITEM_ID, $playerItem->getId(), $comparison);
        } elseif ($playerItem instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_LEFT_LEG_PLAYER_ITEM_ID, $playerItem->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPlayerItemRelatedByLeftLegPlayerItemId() only accepts arguments of type \app\model\PlayerItem or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerItemRelatedByLeftLegPlayerItemId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function joinPlayerItemRelatedByLeftLegPlayerItemId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerItemRelatedByLeftLegPlayerItemId');

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
            $this->addJoinObject($join, 'PlayerItemRelatedByLeftLegPlayerItemId');
        }

        return $this;
    }

    /**
     * Use the PlayerItemRelatedByLeftLegPlayerItemId relation PlayerItem object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerItemQuery A secondary query class using the current class as primary query
     */
    public function usePlayerItemRelatedByLeftLegPlayerItemIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPlayerItemRelatedByLeftLegPlayerItemId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerItemRelatedByLeftLegPlayerItemId', '\app\model\PlayerItemQuery');
    }

    /**
     * Filter the query by a related \app\model\PlayerItem object
     *
     * @param \app\model\PlayerItem|ObjectCollection $playerItem The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByPlayerItemRelatedByRightLegPlayerItemId($playerItem, $comparison = null)
    {
        if ($playerItem instanceof \app\model\PlayerItem) {
            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_RIGHT_LEG_PLAYER_ITEM_ID, $playerItem->getId(), $comparison);
        } elseif ($playerItem instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_RIGHT_LEG_PLAYER_ITEM_ID, $playerItem->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPlayerItemRelatedByRightLegPlayerItemId() only accepts arguments of type \app\model\PlayerItem or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerItemRelatedByRightLegPlayerItemId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function joinPlayerItemRelatedByRightLegPlayerItemId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerItemRelatedByRightLegPlayerItemId');

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
            $this->addJoinObject($join, 'PlayerItemRelatedByRightLegPlayerItemId');
        }

        return $this;
    }

    /**
     * Use the PlayerItemRelatedByRightLegPlayerItemId relation PlayerItem object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerItemQuery A secondary query class using the current class as primary query
     */
    public function usePlayerItemRelatedByRightLegPlayerItemIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPlayerItemRelatedByRightLegPlayerItemId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerItemRelatedByRightLegPlayerItemId', '\app\model\PlayerItemQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPlayerDeck $playerDeck Object to remove from the list of results
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function prune($playerDeck = null)
    {
        if ($playerDeck) {
            $this->addUsingAlias(PlayerDeckTableMap::COL_ID, $playerDeck->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the player_deck table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerDeckTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlayerDeckTableMap::clearInstancePool();
            PlayerDeckTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerDeckTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlayerDeckTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlayerDeckTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlayerDeckTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PlayerDeckTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PlayerDeckTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PlayerDeckTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PlayerDeckTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PlayerDeckTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PlayerDeckTableMap::COL_CREATED_AT);
    }

} // PlayerDeckQuery
