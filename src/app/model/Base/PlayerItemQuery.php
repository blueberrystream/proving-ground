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
use app\model\PlayerItem as ChildPlayerItem;
use app\model\PlayerItemQuery as ChildPlayerItemQuery;
use app\model\Map\PlayerItemTableMap;

/**
 * Base class that represents a query for the 'player_item' table.
 *
 *
 *
 * @method     ChildPlayerItemQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPlayerItemQuery orderByPlayerId($order = Criteria::ASC) Order by the player_id column
 * @method     ChildPlayerItemQuery orderByItemId($order = Criteria::ASC) Order by the item_id column
 * @method     ChildPlayerItemQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildPlayerItemQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildPlayerItemQuery groupById() Group by the id column
 * @method     ChildPlayerItemQuery groupByPlayerId() Group by the player_id column
 * @method     ChildPlayerItemQuery groupByItemId() Group by the item_id column
 * @method     ChildPlayerItemQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildPlayerItemQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildPlayerItemQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlayerItemQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlayerItemQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlayerItemQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPlayerItemQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPlayerItemQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPlayerItemQuery leftJoinPlayer($relationAlias = null) Adds a LEFT JOIN clause to the query using the Player relation
 * @method     ChildPlayerItemQuery rightJoinPlayer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Player relation
 * @method     ChildPlayerItemQuery innerJoinPlayer($relationAlias = null) Adds a INNER JOIN clause to the query using the Player relation
 *
 * @method     ChildPlayerItemQuery joinWithPlayer($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Player relation
 *
 * @method     ChildPlayerItemQuery leftJoinWithPlayer() Adds a LEFT JOIN clause and with to the query using the Player relation
 * @method     ChildPlayerItemQuery rightJoinWithPlayer() Adds a RIGHT JOIN clause and with to the query using the Player relation
 * @method     ChildPlayerItemQuery innerJoinWithPlayer() Adds a INNER JOIN clause and with to the query using the Player relation
 *
 * @method     ChildPlayerItemQuery leftJoinItem($relationAlias = null) Adds a LEFT JOIN clause to the query using the Item relation
 * @method     ChildPlayerItemQuery rightJoinItem($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Item relation
 * @method     ChildPlayerItemQuery innerJoinItem($relationAlias = null) Adds a INNER JOIN clause to the query using the Item relation
 *
 * @method     ChildPlayerItemQuery joinWithItem($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Item relation
 *
 * @method     ChildPlayerItemQuery leftJoinWithItem() Adds a LEFT JOIN clause and with to the query using the Item relation
 * @method     ChildPlayerItemQuery rightJoinWithItem() Adds a RIGHT JOIN clause and with to the query using the Item relation
 * @method     ChildPlayerItemQuery innerJoinWithItem() Adds a INNER JOIN clause and with to the query using the Item relation
 *
 * @method     ChildPlayerItemQuery leftJoinPlayerEquipmentRelatedByWeapon1PlayerItemId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerEquipmentRelatedByWeapon1PlayerItemId relation
 * @method     ChildPlayerItemQuery rightJoinPlayerEquipmentRelatedByWeapon1PlayerItemId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerEquipmentRelatedByWeapon1PlayerItemId relation
 * @method     ChildPlayerItemQuery innerJoinPlayerEquipmentRelatedByWeapon1PlayerItemId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerEquipmentRelatedByWeapon1PlayerItemId relation
 *
 * @method     ChildPlayerItemQuery joinWithPlayerEquipmentRelatedByWeapon1PlayerItemId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerEquipmentRelatedByWeapon1PlayerItemId relation
 *
 * @method     ChildPlayerItemQuery leftJoinWithPlayerEquipmentRelatedByWeapon1PlayerItemId() Adds a LEFT JOIN clause and with to the query using the PlayerEquipmentRelatedByWeapon1PlayerItemId relation
 * @method     ChildPlayerItemQuery rightJoinWithPlayerEquipmentRelatedByWeapon1PlayerItemId() Adds a RIGHT JOIN clause and with to the query using the PlayerEquipmentRelatedByWeapon1PlayerItemId relation
 * @method     ChildPlayerItemQuery innerJoinWithPlayerEquipmentRelatedByWeapon1PlayerItemId() Adds a INNER JOIN clause and with to the query using the PlayerEquipmentRelatedByWeapon1PlayerItemId relation
 *
 * @method     ChildPlayerItemQuery leftJoinPlayerEquipmentRelatedByWeapon2PlayerItemId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerEquipmentRelatedByWeapon2PlayerItemId relation
 * @method     ChildPlayerItemQuery rightJoinPlayerEquipmentRelatedByWeapon2PlayerItemId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerEquipmentRelatedByWeapon2PlayerItemId relation
 * @method     ChildPlayerItemQuery innerJoinPlayerEquipmentRelatedByWeapon2PlayerItemId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerEquipmentRelatedByWeapon2PlayerItemId relation
 *
 * @method     ChildPlayerItemQuery joinWithPlayerEquipmentRelatedByWeapon2PlayerItemId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerEquipmentRelatedByWeapon2PlayerItemId relation
 *
 * @method     ChildPlayerItemQuery leftJoinWithPlayerEquipmentRelatedByWeapon2PlayerItemId() Adds a LEFT JOIN clause and with to the query using the PlayerEquipmentRelatedByWeapon2PlayerItemId relation
 * @method     ChildPlayerItemQuery rightJoinWithPlayerEquipmentRelatedByWeapon2PlayerItemId() Adds a RIGHT JOIN clause and with to the query using the PlayerEquipmentRelatedByWeapon2PlayerItemId relation
 * @method     ChildPlayerItemQuery innerJoinWithPlayerEquipmentRelatedByWeapon2PlayerItemId() Adds a INNER JOIN clause and with to the query using the PlayerEquipmentRelatedByWeapon2PlayerItemId relation
 *
 * @method     ChildPlayerItemQuery leftJoinPlayerEquipmentRelatedByHeadPlayerItemId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerEquipmentRelatedByHeadPlayerItemId relation
 * @method     ChildPlayerItemQuery rightJoinPlayerEquipmentRelatedByHeadPlayerItemId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerEquipmentRelatedByHeadPlayerItemId relation
 * @method     ChildPlayerItemQuery innerJoinPlayerEquipmentRelatedByHeadPlayerItemId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerEquipmentRelatedByHeadPlayerItemId relation
 *
 * @method     ChildPlayerItemQuery joinWithPlayerEquipmentRelatedByHeadPlayerItemId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerEquipmentRelatedByHeadPlayerItemId relation
 *
 * @method     ChildPlayerItemQuery leftJoinWithPlayerEquipmentRelatedByHeadPlayerItemId() Adds a LEFT JOIN clause and with to the query using the PlayerEquipmentRelatedByHeadPlayerItemId relation
 * @method     ChildPlayerItemQuery rightJoinWithPlayerEquipmentRelatedByHeadPlayerItemId() Adds a RIGHT JOIN clause and with to the query using the PlayerEquipmentRelatedByHeadPlayerItemId relation
 * @method     ChildPlayerItemQuery innerJoinWithPlayerEquipmentRelatedByHeadPlayerItemId() Adds a INNER JOIN clause and with to the query using the PlayerEquipmentRelatedByHeadPlayerItemId relation
 *
 * @method     ChildPlayerItemQuery leftJoinPlayerEquipmentRelatedByLeftArmPlayerItemId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerEquipmentRelatedByLeftArmPlayerItemId relation
 * @method     ChildPlayerItemQuery rightJoinPlayerEquipmentRelatedByLeftArmPlayerItemId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerEquipmentRelatedByLeftArmPlayerItemId relation
 * @method     ChildPlayerItemQuery innerJoinPlayerEquipmentRelatedByLeftArmPlayerItemId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerEquipmentRelatedByLeftArmPlayerItemId relation
 *
 * @method     ChildPlayerItemQuery joinWithPlayerEquipmentRelatedByLeftArmPlayerItemId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerEquipmentRelatedByLeftArmPlayerItemId relation
 *
 * @method     ChildPlayerItemQuery leftJoinWithPlayerEquipmentRelatedByLeftArmPlayerItemId() Adds a LEFT JOIN clause and with to the query using the PlayerEquipmentRelatedByLeftArmPlayerItemId relation
 * @method     ChildPlayerItemQuery rightJoinWithPlayerEquipmentRelatedByLeftArmPlayerItemId() Adds a RIGHT JOIN clause and with to the query using the PlayerEquipmentRelatedByLeftArmPlayerItemId relation
 * @method     ChildPlayerItemQuery innerJoinWithPlayerEquipmentRelatedByLeftArmPlayerItemId() Adds a INNER JOIN clause and with to the query using the PlayerEquipmentRelatedByLeftArmPlayerItemId relation
 *
 * @method     ChildPlayerItemQuery leftJoinPlayerEquipmentRelatedByRightArmPlayerItemId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerEquipmentRelatedByRightArmPlayerItemId relation
 * @method     ChildPlayerItemQuery rightJoinPlayerEquipmentRelatedByRightArmPlayerItemId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerEquipmentRelatedByRightArmPlayerItemId relation
 * @method     ChildPlayerItemQuery innerJoinPlayerEquipmentRelatedByRightArmPlayerItemId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerEquipmentRelatedByRightArmPlayerItemId relation
 *
 * @method     ChildPlayerItemQuery joinWithPlayerEquipmentRelatedByRightArmPlayerItemId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerEquipmentRelatedByRightArmPlayerItemId relation
 *
 * @method     ChildPlayerItemQuery leftJoinWithPlayerEquipmentRelatedByRightArmPlayerItemId() Adds a LEFT JOIN clause and with to the query using the PlayerEquipmentRelatedByRightArmPlayerItemId relation
 * @method     ChildPlayerItemQuery rightJoinWithPlayerEquipmentRelatedByRightArmPlayerItemId() Adds a RIGHT JOIN clause and with to the query using the PlayerEquipmentRelatedByRightArmPlayerItemId relation
 * @method     ChildPlayerItemQuery innerJoinWithPlayerEquipmentRelatedByRightArmPlayerItemId() Adds a INNER JOIN clause and with to the query using the PlayerEquipmentRelatedByRightArmPlayerItemId relation
 *
 * @method     ChildPlayerItemQuery leftJoinPlayerEquipmentRelatedByLeftLegPlayerItemId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerEquipmentRelatedByLeftLegPlayerItemId relation
 * @method     ChildPlayerItemQuery rightJoinPlayerEquipmentRelatedByLeftLegPlayerItemId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerEquipmentRelatedByLeftLegPlayerItemId relation
 * @method     ChildPlayerItemQuery innerJoinPlayerEquipmentRelatedByLeftLegPlayerItemId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerEquipmentRelatedByLeftLegPlayerItemId relation
 *
 * @method     ChildPlayerItemQuery joinWithPlayerEquipmentRelatedByLeftLegPlayerItemId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerEquipmentRelatedByLeftLegPlayerItemId relation
 *
 * @method     ChildPlayerItemQuery leftJoinWithPlayerEquipmentRelatedByLeftLegPlayerItemId() Adds a LEFT JOIN clause and with to the query using the PlayerEquipmentRelatedByLeftLegPlayerItemId relation
 * @method     ChildPlayerItemQuery rightJoinWithPlayerEquipmentRelatedByLeftLegPlayerItemId() Adds a RIGHT JOIN clause and with to the query using the PlayerEquipmentRelatedByLeftLegPlayerItemId relation
 * @method     ChildPlayerItemQuery innerJoinWithPlayerEquipmentRelatedByLeftLegPlayerItemId() Adds a INNER JOIN clause and with to the query using the PlayerEquipmentRelatedByLeftLegPlayerItemId relation
 *
 * @method     ChildPlayerItemQuery leftJoinPlayerEquipmentRelatedByRightLegPlayerItemId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerEquipmentRelatedByRightLegPlayerItemId relation
 * @method     ChildPlayerItemQuery rightJoinPlayerEquipmentRelatedByRightLegPlayerItemId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerEquipmentRelatedByRightLegPlayerItemId relation
 * @method     ChildPlayerItemQuery innerJoinPlayerEquipmentRelatedByRightLegPlayerItemId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerEquipmentRelatedByRightLegPlayerItemId relation
 *
 * @method     ChildPlayerItemQuery joinWithPlayerEquipmentRelatedByRightLegPlayerItemId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerEquipmentRelatedByRightLegPlayerItemId relation
 *
 * @method     ChildPlayerItemQuery leftJoinWithPlayerEquipmentRelatedByRightLegPlayerItemId() Adds a LEFT JOIN clause and with to the query using the PlayerEquipmentRelatedByRightLegPlayerItemId relation
 * @method     ChildPlayerItemQuery rightJoinWithPlayerEquipmentRelatedByRightLegPlayerItemId() Adds a RIGHT JOIN clause and with to the query using the PlayerEquipmentRelatedByRightLegPlayerItemId relation
 * @method     ChildPlayerItemQuery innerJoinWithPlayerEquipmentRelatedByRightLegPlayerItemId() Adds a INNER JOIN clause and with to the query using the PlayerEquipmentRelatedByRightLegPlayerItemId relation
 *
 * @method     \app\model\PlayerQuery|\app\model\ItemQuery|\app\model\PlayerEquipmentQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPlayerItem findOne(ConnectionInterface $con = null) Return the first ChildPlayerItem matching the query
 * @method     ChildPlayerItem findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlayerItem matching the query, or a new ChildPlayerItem object populated from the query conditions when no match is found
 *
 * @method     ChildPlayerItem findOneById(int $id) Return the first ChildPlayerItem filtered by the id column
 * @method     ChildPlayerItem findOneByPlayerId(int $player_id) Return the first ChildPlayerItem filtered by the player_id column
 * @method     ChildPlayerItem findOneByItemId(int $item_id) Return the first ChildPlayerItem filtered by the item_id column
 * @method     ChildPlayerItem findOneByCreatedAt(string $created_at) Return the first ChildPlayerItem filtered by the created_at column
 * @method     ChildPlayerItem findOneByUpdatedAt(string $updated_at) Return the first ChildPlayerItem filtered by the updated_at column *

 * @method     ChildPlayerItem requirePk($key, ConnectionInterface $con = null) Return the ChildPlayerItem by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerItem requireOne(ConnectionInterface $con = null) Return the first ChildPlayerItem matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayerItem requireOneById(int $id) Return the first ChildPlayerItem filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerItem requireOneByPlayerId(int $player_id) Return the first ChildPlayerItem filtered by the player_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerItem requireOneByItemId(int $item_id) Return the first ChildPlayerItem filtered by the item_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerItem requireOneByCreatedAt(string $created_at) Return the first ChildPlayerItem filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerItem requireOneByUpdatedAt(string $updated_at) Return the first ChildPlayerItem filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayerItem[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlayerItem objects based on current ModelCriteria
 * @method     ChildPlayerItem[]|ObjectCollection findById(int $id) Return ChildPlayerItem objects filtered by the id column
 * @method     ChildPlayerItem[]|ObjectCollection findByPlayerId(int $player_id) Return ChildPlayerItem objects filtered by the player_id column
 * @method     ChildPlayerItem[]|ObjectCollection findByItemId(int $item_id) Return ChildPlayerItem objects filtered by the item_id column
 * @method     ChildPlayerItem[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildPlayerItem objects filtered by the created_at column
 * @method     ChildPlayerItem[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildPlayerItem objects filtered by the updated_at column
 * @method     ChildPlayerItem[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlayerItemQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \app\model\Base\PlayerItemQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\app\\model\\PlayerItem', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlayerItemQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlayerItemQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlayerItemQuery) {
            return $criteria;
        }
        $query = new ChildPlayerItemQuery();
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
     * @return ChildPlayerItem|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlayerItemTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PlayerItemTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPlayerItem A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, player_id, item_id, created_at, updated_at FROM player_item WHERE id = :p0';
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
            /** @var ChildPlayerItem $obj */
            $obj = new ChildPlayerItem();
            $obj->hydrate($row);
            PlayerItemTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPlayerItem|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PlayerItemTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PlayerItemTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PlayerItemTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PlayerItemTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerItemTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function filterByPlayerId($playerId = null, $comparison = null)
    {
        if (is_array($playerId)) {
            $useMinMax = false;
            if (isset($playerId['min'])) {
                $this->addUsingAlias(PlayerItemTableMap::COL_PLAYER_ID, $playerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerId['max'])) {
                $this->addUsingAlias(PlayerItemTableMap::COL_PLAYER_ID, $playerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerItemTableMap::COL_PLAYER_ID, $playerId, $comparison);
    }

    /**
     * Filter the query on the item_id column
     *
     * Example usage:
     * <code>
     * $query->filterByItemId(1234); // WHERE item_id = 1234
     * $query->filterByItemId(array(12, 34)); // WHERE item_id IN (12, 34)
     * $query->filterByItemId(array('min' => 12)); // WHERE item_id > 12
     * </code>
     *
     * @see       filterByItem()
     *
     * @param     mixed $itemId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function filterByItemId($itemId = null, $comparison = null)
    {
        if (is_array($itemId)) {
            $useMinMax = false;
            if (isset($itemId['min'])) {
                $this->addUsingAlias(PlayerItemTableMap::COL_ITEM_ID, $itemId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($itemId['max'])) {
                $this->addUsingAlias(PlayerItemTableMap::COL_ITEM_ID, $itemId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerItemTableMap::COL_ITEM_ID, $itemId, $comparison);
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
     * @return $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PlayerItemTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PlayerItemTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerItemTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PlayerItemTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PlayerItemTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerItemTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \app\model\Player object
     *
     * @param \app\model\Player|ObjectCollection $player The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerItemQuery The current query, for fluid interface
     */
    public function filterByPlayer($player, $comparison = null)
    {
        if ($player instanceof \app\model\Player) {
            return $this
                ->addUsingAlias(PlayerItemTableMap::COL_PLAYER_ID, $player->getId(), $comparison);
        } elseif ($player instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerItemTableMap::COL_PLAYER_ID, $player->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildPlayerItemQuery The current query, for fluid interface
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
     * Filter the query by a related \app\model\Item object
     *
     * @param \app\model\Item|ObjectCollection $item The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerItemQuery The current query, for fluid interface
     */
    public function filterByItem($item, $comparison = null)
    {
        if ($item instanceof \app\model\Item) {
            return $this
                ->addUsingAlias(PlayerItemTableMap::COL_ITEM_ID, $item->getId(), $comparison);
        } elseif ($item instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerItemTableMap::COL_ITEM_ID, $item->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByItem() only accepts arguments of type \app\model\Item or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Item relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function joinItem($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Item');

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
            $this->addJoinObject($join, 'Item');
        }

        return $this;
    }

    /**
     * Use the Item relation Item object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\ItemQuery A secondary query class using the current class as primary query
     */
    public function useItemQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinItem($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Item', '\app\model\ItemQuery');
    }

    /**
     * Filter the query by a related \app\model\PlayerEquipment object
     *
     * @param \app\model\PlayerEquipment|ObjectCollection $playerEquipment the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlayerItemQuery The current query, for fluid interface
     */
    public function filterByPlayerEquipmentRelatedByWeapon1PlayerItemId($playerEquipment, $comparison = null)
    {
        if ($playerEquipment instanceof \app\model\PlayerEquipment) {
            return $this
                ->addUsingAlias(PlayerItemTableMap::COL_ID, $playerEquipment->getWeapon1PlayerItemId(), $comparison);
        } elseif ($playerEquipment instanceof ObjectCollection) {
            return $this
                ->usePlayerEquipmentRelatedByWeapon1PlayerItemIdQuery()
                ->filterByPrimaryKeys($playerEquipment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlayerEquipmentRelatedByWeapon1PlayerItemId() only accepts arguments of type \app\model\PlayerEquipment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerEquipmentRelatedByWeapon1PlayerItemId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function joinPlayerEquipmentRelatedByWeapon1PlayerItemId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerEquipmentRelatedByWeapon1PlayerItemId');

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
            $this->addJoinObject($join, 'PlayerEquipmentRelatedByWeapon1PlayerItemId');
        }

        return $this;
    }

    /**
     * Use the PlayerEquipmentRelatedByWeapon1PlayerItemId relation PlayerEquipment object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerEquipmentQuery A secondary query class using the current class as primary query
     */
    public function usePlayerEquipmentRelatedByWeapon1PlayerItemIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerEquipmentRelatedByWeapon1PlayerItemId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerEquipmentRelatedByWeapon1PlayerItemId', '\app\model\PlayerEquipmentQuery');
    }

    /**
     * Filter the query by a related \app\model\PlayerEquipment object
     *
     * @param \app\model\PlayerEquipment|ObjectCollection $playerEquipment the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlayerItemQuery The current query, for fluid interface
     */
    public function filterByPlayerEquipmentRelatedByWeapon2PlayerItemId($playerEquipment, $comparison = null)
    {
        if ($playerEquipment instanceof \app\model\PlayerEquipment) {
            return $this
                ->addUsingAlias(PlayerItemTableMap::COL_ID, $playerEquipment->getWeapon2PlayerItemId(), $comparison);
        } elseif ($playerEquipment instanceof ObjectCollection) {
            return $this
                ->usePlayerEquipmentRelatedByWeapon2PlayerItemIdQuery()
                ->filterByPrimaryKeys($playerEquipment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlayerEquipmentRelatedByWeapon2PlayerItemId() only accepts arguments of type \app\model\PlayerEquipment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerEquipmentRelatedByWeapon2PlayerItemId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function joinPlayerEquipmentRelatedByWeapon2PlayerItemId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerEquipmentRelatedByWeapon2PlayerItemId');

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
            $this->addJoinObject($join, 'PlayerEquipmentRelatedByWeapon2PlayerItemId');
        }

        return $this;
    }

    /**
     * Use the PlayerEquipmentRelatedByWeapon2PlayerItemId relation PlayerEquipment object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerEquipmentQuery A secondary query class using the current class as primary query
     */
    public function usePlayerEquipmentRelatedByWeapon2PlayerItemIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerEquipmentRelatedByWeapon2PlayerItemId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerEquipmentRelatedByWeapon2PlayerItemId', '\app\model\PlayerEquipmentQuery');
    }

    /**
     * Filter the query by a related \app\model\PlayerEquipment object
     *
     * @param \app\model\PlayerEquipment|ObjectCollection $playerEquipment the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlayerItemQuery The current query, for fluid interface
     */
    public function filterByPlayerEquipmentRelatedByHeadPlayerItemId($playerEquipment, $comparison = null)
    {
        if ($playerEquipment instanceof \app\model\PlayerEquipment) {
            return $this
                ->addUsingAlias(PlayerItemTableMap::COL_ID, $playerEquipment->getHeadPlayerItemId(), $comparison);
        } elseif ($playerEquipment instanceof ObjectCollection) {
            return $this
                ->usePlayerEquipmentRelatedByHeadPlayerItemIdQuery()
                ->filterByPrimaryKeys($playerEquipment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlayerEquipmentRelatedByHeadPlayerItemId() only accepts arguments of type \app\model\PlayerEquipment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerEquipmentRelatedByHeadPlayerItemId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function joinPlayerEquipmentRelatedByHeadPlayerItemId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerEquipmentRelatedByHeadPlayerItemId');

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
            $this->addJoinObject($join, 'PlayerEquipmentRelatedByHeadPlayerItemId');
        }

        return $this;
    }

    /**
     * Use the PlayerEquipmentRelatedByHeadPlayerItemId relation PlayerEquipment object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerEquipmentQuery A secondary query class using the current class as primary query
     */
    public function usePlayerEquipmentRelatedByHeadPlayerItemIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerEquipmentRelatedByHeadPlayerItemId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerEquipmentRelatedByHeadPlayerItemId', '\app\model\PlayerEquipmentQuery');
    }

    /**
     * Filter the query by a related \app\model\PlayerEquipment object
     *
     * @param \app\model\PlayerEquipment|ObjectCollection $playerEquipment the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlayerItemQuery The current query, for fluid interface
     */
    public function filterByPlayerEquipmentRelatedByLeftArmPlayerItemId($playerEquipment, $comparison = null)
    {
        if ($playerEquipment instanceof \app\model\PlayerEquipment) {
            return $this
                ->addUsingAlias(PlayerItemTableMap::COL_ID, $playerEquipment->getLeftArmPlayerItemId(), $comparison);
        } elseif ($playerEquipment instanceof ObjectCollection) {
            return $this
                ->usePlayerEquipmentRelatedByLeftArmPlayerItemIdQuery()
                ->filterByPrimaryKeys($playerEquipment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlayerEquipmentRelatedByLeftArmPlayerItemId() only accepts arguments of type \app\model\PlayerEquipment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerEquipmentRelatedByLeftArmPlayerItemId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function joinPlayerEquipmentRelatedByLeftArmPlayerItemId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerEquipmentRelatedByLeftArmPlayerItemId');

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
            $this->addJoinObject($join, 'PlayerEquipmentRelatedByLeftArmPlayerItemId');
        }

        return $this;
    }

    /**
     * Use the PlayerEquipmentRelatedByLeftArmPlayerItemId relation PlayerEquipment object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerEquipmentQuery A secondary query class using the current class as primary query
     */
    public function usePlayerEquipmentRelatedByLeftArmPlayerItemIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerEquipmentRelatedByLeftArmPlayerItemId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerEquipmentRelatedByLeftArmPlayerItemId', '\app\model\PlayerEquipmentQuery');
    }

    /**
     * Filter the query by a related \app\model\PlayerEquipment object
     *
     * @param \app\model\PlayerEquipment|ObjectCollection $playerEquipment the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlayerItemQuery The current query, for fluid interface
     */
    public function filterByPlayerEquipmentRelatedByRightArmPlayerItemId($playerEquipment, $comparison = null)
    {
        if ($playerEquipment instanceof \app\model\PlayerEquipment) {
            return $this
                ->addUsingAlias(PlayerItemTableMap::COL_ID, $playerEquipment->getRightArmPlayerItemId(), $comparison);
        } elseif ($playerEquipment instanceof ObjectCollection) {
            return $this
                ->usePlayerEquipmentRelatedByRightArmPlayerItemIdQuery()
                ->filterByPrimaryKeys($playerEquipment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlayerEquipmentRelatedByRightArmPlayerItemId() only accepts arguments of type \app\model\PlayerEquipment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerEquipmentRelatedByRightArmPlayerItemId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function joinPlayerEquipmentRelatedByRightArmPlayerItemId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerEquipmentRelatedByRightArmPlayerItemId');

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
            $this->addJoinObject($join, 'PlayerEquipmentRelatedByRightArmPlayerItemId');
        }

        return $this;
    }

    /**
     * Use the PlayerEquipmentRelatedByRightArmPlayerItemId relation PlayerEquipment object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerEquipmentQuery A secondary query class using the current class as primary query
     */
    public function usePlayerEquipmentRelatedByRightArmPlayerItemIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerEquipmentRelatedByRightArmPlayerItemId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerEquipmentRelatedByRightArmPlayerItemId', '\app\model\PlayerEquipmentQuery');
    }

    /**
     * Filter the query by a related \app\model\PlayerEquipment object
     *
     * @param \app\model\PlayerEquipment|ObjectCollection $playerEquipment the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlayerItemQuery The current query, for fluid interface
     */
    public function filterByPlayerEquipmentRelatedByLeftLegPlayerItemId($playerEquipment, $comparison = null)
    {
        if ($playerEquipment instanceof \app\model\PlayerEquipment) {
            return $this
                ->addUsingAlias(PlayerItemTableMap::COL_ID, $playerEquipment->getLeftLegPlayerItemId(), $comparison);
        } elseif ($playerEquipment instanceof ObjectCollection) {
            return $this
                ->usePlayerEquipmentRelatedByLeftLegPlayerItemIdQuery()
                ->filterByPrimaryKeys($playerEquipment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlayerEquipmentRelatedByLeftLegPlayerItemId() only accepts arguments of type \app\model\PlayerEquipment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerEquipmentRelatedByLeftLegPlayerItemId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function joinPlayerEquipmentRelatedByLeftLegPlayerItemId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerEquipmentRelatedByLeftLegPlayerItemId');

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
            $this->addJoinObject($join, 'PlayerEquipmentRelatedByLeftLegPlayerItemId');
        }

        return $this;
    }

    /**
     * Use the PlayerEquipmentRelatedByLeftLegPlayerItemId relation PlayerEquipment object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerEquipmentQuery A secondary query class using the current class as primary query
     */
    public function usePlayerEquipmentRelatedByLeftLegPlayerItemIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerEquipmentRelatedByLeftLegPlayerItemId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerEquipmentRelatedByLeftLegPlayerItemId', '\app\model\PlayerEquipmentQuery');
    }

    /**
     * Filter the query by a related \app\model\PlayerEquipment object
     *
     * @param \app\model\PlayerEquipment|ObjectCollection $playerEquipment the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlayerItemQuery The current query, for fluid interface
     */
    public function filterByPlayerEquipmentRelatedByRightLegPlayerItemId($playerEquipment, $comparison = null)
    {
        if ($playerEquipment instanceof \app\model\PlayerEquipment) {
            return $this
                ->addUsingAlias(PlayerItemTableMap::COL_ID, $playerEquipment->getRightLegPlayerItemId(), $comparison);
        } elseif ($playerEquipment instanceof ObjectCollection) {
            return $this
                ->usePlayerEquipmentRelatedByRightLegPlayerItemIdQuery()
                ->filterByPrimaryKeys($playerEquipment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlayerEquipmentRelatedByRightLegPlayerItemId() only accepts arguments of type \app\model\PlayerEquipment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerEquipmentRelatedByRightLegPlayerItemId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function joinPlayerEquipmentRelatedByRightLegPlayerItemId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerEquipmentRelatedByRightLegPlayerItemId');

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
            $this->addJoinObject($join, 'PlayerEquipmentRelatedByRightLegPlayerItemId');
        }

        return $this;
    }

    /**
     * Use the PlayerEquipmentRelatedByRightLegPlayerItemId relation PlayerEquipment object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerEquipmentQuery A secondary query class using the current class as primary query
     */
    public function usePlayerEquipmentRelatedByRightLegPlayerItemIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerEquipmentRelatedByRightLegPlayerItemId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerEquipmentRelatedByRightLegPlayerItemId', '\app\model\PlayerEquipmentQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPlayerItem $playerItem Object to remove from the list of results
     *
     * @return $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function prune($playerItem = null)
    {
        if ($playerItem) {
            $this->addUsingAlias(PlayerItemTableMap::COL_ID, $playerItem->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the player_item table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerItemTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlayerItemTableMap::clearInstancePool();
            PlayerItemTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerItemTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlayerItemTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlayerItemTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlayerItemTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PlayerItemTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PlayerItemTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PlayerItemTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PlayerItemTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PlayerItemTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildPlayerItemQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PlayerItemTableMap::COL_CREATED_AT);
    }

} // PlayerItemQuery
