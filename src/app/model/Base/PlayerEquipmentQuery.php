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
use app\model\PlayerEquipment as ChildPlayerEquipment;
use app\model\PlayerEquipmentQuery as ChildPlayerEquipmentQuery;
use app\model\Map\PlayerEquipmentTableMap;

/**
 * Base class that represents a query for the 'player_equipment' table.
 *
 *
 *
 * @method     ChildPlayerEquipmentQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPlayerEquipmentQuery orderByPlayerId($order = Criteria::ASC) Order by the player_id column
 * @method     ChildPlayerEquipmentQuery orderByWeapon1PlayerItemId($order = Criteria::ASC) Order by the weapon1_player_item_id column
 * @method     ChildPlayerEquipmentQuery orderByWeapon2PlayerItemId($order = Criteria::ASC) Order by the weapon2_player_item_id column
 * @method     ChildPlayerEquipmentQuery orderByHeadPlayerItemId($order = Criteria::ASC) Order by the head_player_item_id column
 * @method     ChildPlayerEquipmentQuery orderByLeftArmPlayerItemId($order = Criteria::ASC) Order by the left_arm_player_item_id column
 * @method     ChildPlayerEquipmentQuery orderByRightArmPlayerItemId($order = Criteria::ASC) Order by the right_arm_player_item_id column
 * @method     ChildPlayerEquipmentQuery orderByLeftLegPlayerItemId($order = Criteria::ASC) Order by the left_leg_player_item_id column
 * @method     ChildPlayerEquipmentQuery orderByRightLegPlayerItemId($order = Criteria::ASC) Order by the right_leg_player_item_id column
 * @method     ChildPlayerEquipmentQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildPlayerEquipmentQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildPlayerEquipmentQuery groupById() Group by the id column
 * @method     ChildPlayerEquipmentQuery groupByPlayerId() Group by the player_id column
 * @method     ChildPlayerEquipmentQuery groupByWeapon1PlayerItemId() Group by the weapon1_player_item_id column
 * @method     ChildPlayerEquipmentQuery groupByWeapon2PlayerItemId() Group by the weapon2_player_item_id column
 * @method     ChildPlayerEquipmentQuery groupByHeadPlayerItemId() Group by the head_player_item_id column
 * @method     ChildPlayerEquipmentQuery groupByLeftArmPlayerItemId() Group by the left_arm_player_item_id column
 * @method     ChildPlayerEquipmentQuery groupByRightArmPlayerItemId() Group by the right_arm_player_item_id column
 * @method     ChildPlayerEquipmentQuery groupByLeftLegPlayerItemId() Group by the left_leg_player_item_id column
 * @method     ChildPlayerEquipmentQuery groupByRightLegPlayerItemId() Group by the right_leg_player_item_id column
 * @method     ChildPlayerEquipmentQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildPlayerEquipmentQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildPlayerEquipmentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlayerEquipmentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlayerEquipmentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlayerEquipmentQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPlayerEquipmentQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPlayerEquipmentQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPlayerEquipmentQuery leftJoinPlayer($relationAlias = null) Adds a LEFT JOIN clause to the query using the Player relation
 * @method     ChildPlayerEquipmentQuery rightJoinPlayer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Player relation
 * @method     ChildPlayerEquipmentQuery innerJoinPlayer($relationAlias = null) Adds a INNER JOIN clause to the query using the Player relation
 *
 * @method     ChildPlayerEquipmentQuery joinWithPlayer($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Player relation
 *
 * @method     ChildPlayerEquipmentQuery leftJoinWithPlayer() Adds a LEFT JOIN clause and with to the query using the Player relation
 * @method     ChildPlayerEquipmentQuery rightJoinWithPlayer() Adds a RIGHT JOIN clause and with to the query using the Player relation
 * @method     ChildPlayerEquipmentQuery innerJoinWithPlayer() Adds a INNER JOIN clause and with to the query using the Player relation
 *
 * @method     ChildPlayerEquipmentQuery leftJoinPlayerItemRelatedByWeapon1PlayerItemId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerItemRelatedByWeapon1PlayerItemId relation
 * @method     ChildPlayerEquipmentQuery rightJoinPlayerItemRelatedByWeapon1PlayerItemId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerItemRelatedByWeapon1PlayerItemId relation
 * @method     ChildPlayerEquipmentQuery innerJoinPlayerItemRelatedByWeapon1PlayerItemId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerItemRelatedByWeapon1PlayerItemId relation
 *
 * @method     ChildPlayerEquipmentQuery joinWithPlayerItemRelatedByWeapon1PlayerItemId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerItemRelatedByWeapon1PlayerItemId relation
 *
 * @method     ChildPlayerEquipmentQuery leftJoinWithPlayerItemRelatedByWeapon1PlayerItemId() Adds a LEFT JOIN clause and with to the query using the PlayerItemRelatedByWeapon1PlayerItemId relation
 * @method     ChildPlayerEquipmentQuery rightJoinWithPlayerItemRelatedByWeapon1PlayerItemId() Adds a RIGHT JOIN clause and with to the query using the PlayerItemRelatedByWeapon1PlayerItemId relation
 * @method     ChildPlayerEquipmentQuery innerJoinWithPlayerItemRelatedByWeapon1PlayerItemId() Adds a INNER JOIN clause and with to the query using the PlayerItemRelatedByWeapon1PlayerItemId relation
 *
 * @method     ChildPlayerEquipmentQuery leftJoinPlayerItemRelatedByWeapon2PlayerItemId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerItemRelatedByWeapon2PlayerItemId relation
 * @method     ChildPlayerEquipmentQuery rightJoinPlayerItemRelatedByWeapon2PlayerItemId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerItemRelatedByWeapon2PlayerItemId relation
 * @method     ChildPlayerEquipmentQuery innerJoinPlayerItemRelatedByWeapon2PlayerItemId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerItemRelatedByWeapon2PlayerItemId relation
 *
 * @method     ChildPlayerEquipmentQuery joinWithPlayerItemRelatedByWeapon2PlayerItemId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerItemRelatedByWeapon2PlayerItemId relation
 *
 * @method     ChildPlayerEquipmentQuery leftJoinWithPlayerItemRelatedByWeapon2PlayerItemId() Adds a LEFT JOIN clause and with to the query using the PlayerItemRelatedByWeapon2PlayerItemId relation
 * @method     ChildPlayerEquipmentQuery rightJoinWithPlayerItemRelatedByWeapon2PlayerItemId() Adds a RIGHT JOIN clause and with to the query using the PlayerItemRelatedByWeapon2PlayerItemId relation
 * @method     ChildPlayerEquipmentQuery innerJoinWithPlayerItemRelatedByWeapon2PlayerItemId() Adds a INNER JOIN clause and with to the query using the PlayerItemRelatedByWeapon2PlayerItemId relation
 *
 * @method     ChildPlayerEquipmentQuery leftJoinPlayerItemRelatedByHeadPlayerItemId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerItemRelatedByHeadPlayerItemId relation
 * @method     ChildPlayerEquipmentQuery rightJoinPlayerItemRelatedByHeadPlayerItemId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerItemRelatedByHeadPlayerItemId relation
 * @method     ChildPlayerEquipmentQuery innerJoinPlayerItemRelatedByHeadPlayerItemId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerItemRelatedByHeadPlayerItemId relation
 *
 * @method     ChildPlayerEquipmentQuery joinWithPlayerItemRelatedByHeadPlayerItemId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerItemRelatedByHeadPlayerItemId relation
 *
 * @method     ChildPlayerEquipmentQuery leftJoinWithPlayerItemRelatedByHeadPlayerItemId() Adds a LEFT JOIN clause and with to the query using the PlayerItemRelatedByHeadPlayerItemId relation
 * @method     ChildPlayerEquipmentQuery rightJoinWithPlayerItemRelatedByHeadPlayerItemId() Adds a RIGHT JOIN clause and with to the query using the PlayerItemRelatedByHeadPlayerItemId relation
 * @method     ChildPlayerEquipmentQuery innerJoinWithPlayerItemRelatedByHeadPlayerItemId() Adds a INNER JOIN clause and with to the query using the PlayerItemRelatedByHeadPlayerItemId relation
 *
 * @method     ChildPlayerEquipmentQuery leftJoinPlayerItemRelatedByLeftArmPlayerItemId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerItemRelatedByLeftArmPlayerItemId relation
 * @method     ChildPlayerEquipmentQuery rightJoinPlayerItemRelatedByLeftArmPlayerItemId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerItemRelatedByLeftArmPlayerItemId relation
 * @method     ChildPlayerEquipmentQuery innerJoinPlayerItemRelatedByLeftArmPlayerItemId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerItemRelatedByLeftArmPlayerItemId relation
 *
 * @method     ChildPlayerEquipmentQuery joinWithPlayerItemRelatedByLeftArmPlayerItemId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerItemRelatedByLeftArmPlayerItemId relation
 *
 * @method     ChildPlayerEquipmentQuery leftJoinWithPlayerItemRelatedByLeftArmPlayerItemId() Adds a LEFT JOIN clause and with to the query using the PlayerItemRelatedByLeftArmPlayerItemId relation
 * @method     ChildPlayerEquipmentQuery rightJoinWithPlayerItemRelatedByLeftArmPlayerItemId() Adds a RIGHT JOIN clause and with to the query using the PlayerItemRelatedByLeftArmPlayerItemId relation
 * @method     ChildPlayerEquipmentQuery innerJoinWithPlayerItemRelatedByLeftArmPlayerItemId() Adds a INNER JOIN clause and with to the query using the PlayerItemRelatedByLeftArmPlayerItemId relation
 *
 * @method     ChildPlayerEquipmentQuery leftJoinPlayerItemRelatedByRightArmPlayerItemId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerItemRelatedByRightArmPlayerItemId relation
 * @method     ChildPlayerEquipmentQuery rightJoinPlayerItemRelatedByRightArmPlayerItemId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerItemRelatedByRightArmPlayerItemId relation
 * @method     ChildPlayerEquipmentQuery innerJoinPlayerItemRelatedByRightArmPlayerItemId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerItemRelatedByRightArmPlayerItemId relation
 *
 * @method     ChildPlayerEquipmentQuery joinWithPlayerItemRelatedByRightArmPlayerItemId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerItemRelatedByRightArmPlayerItemId relation
 *
 * @method     ChildPlayerEquipmentQuery leftJoinWithPlayerItemRelatedByRightArmPlayerItemId() Adds a LEFT JOIN clause and with to the query using the PlayerItemRelatedByRightArmPlayerItemId relation
 * @method     ChildPlayerEquipmentQuery rightJoinWithPlayerItemRelatedByRightArmPlayerItemId() Adds a RIGHT JOIN clause and with to the query using the PlayerItemRelatedByRightArmPlayerItemId relation
 * @method     ChildPlayerEquipmentQuery innerJoinWithPlayerItemRelatedByRightArmPlayerItemId() Adds a INNER JOIN clause and with to the query using the PlayerItemRelatedByRightArmPlayerItemId relation
 *
 * @method     ChildPlayerEquipmentQuery leftJoinPlayerItemRelatedByLeftLegPlayerItemId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerItemRelatedByLeftLegPlayerItemId relation
 * @method     ChildPlayerEquipmentQuery rightJoinPlayerItemRelatedByLeftLegPlayerItemId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerItemRelatedByLeftLegPlayerItemId relation
 * @method     ChildPlayerEquipmentQuery innerJoinPlayerItemRelatedByLeftLegPlayerItemId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerItemRelatedByLeftLegPlayerItemId relation
 *
 * @method     ChildPlayerEquipmentQuery joinWithPlayerItemRelatedByLeftLegPlayerItemId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerItemRelatedByLeftLegPlayerItemId relation
 *
 * @method     ChildPlayerEquipmentQuery leftJoinWithPlayerItemRelatedByLeftLegPlayerItemId() Adds a LEFT JOIN clause and with to the query using the PlayerItemRelatedByLeftLegPlayerItemId relation
 * @method     ChildPlayerEquipmentQuery rightJoinWithPlayerItemRelatedByLeftLegPlayerItemId() Adds a RIGHT JOIN clause and with to the query using the PlayerItemRelatedByLeftLegPlayerItemId relation
 * @method     ChildPlayerEquipmentQuery innerJoinWithPlayerItemRelatedByLeftLegPlayerItemId() Adds a INNER JOIN clause and with to the query using the PlayerItemRelatedByLeftLegPlayerItemId relation
 *
 * @method     ChildPlayerEquipmentQuery leftJoinPlayerItemRelatedByRightLegPlayerItemId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerItemRelatedByRightLegPlayerItemId relation
 * @method     ChildPlayerEquipmentQuery rightJoinPlayerItemRelatedByRightLegPlayerItemId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerItemRelatedByRightLegPlayerItemId relation
 * @method     ChildPlayerEquipmentQuery innerJoinPlayerItemRelatedByRightLegPlayerItemId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerItemRelatedByRightLegPlayerItemId relation
 *
 * @method     ChildPlayerEquipmentQuery joinWithPlayerItemRelatedByRightLegPlayerItemId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlayerItemRelatedByRightLegPlayerItemId relation
 *
 * @method     ChildPlayerEquipmentQuery leftJoinWithPlayerItemRelatedByRightLegPlayerItemId() Adds a LEFT JOIN clause and with to the query using the PlayerItemRelatedByRightLegPlayerItemId relation
 * @method     ChildPlayerEquipmentQuery rightJoinWithPlayerItemRelatedByRightLegPlayerItemId() Adds a RIGHT JOIN clause and with to the query using the PlayerItemRelatedByRightLegPlayerItemId relation
 * @method     ChildPlayerEquipmentQuery innerJoinWithPlayerItemRelatedByRightLegPlayerItemId() Adds a INNER JOIN clause and with to the query using the PlayerItemRelatedByRightLegPlayerItemId relation
 *
 * @method     \app\model\PlayerQuery|\app\model\PlayerItemQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPlayerEquipment findOne(ConnectionInterface $con = null) Return the first ChildPlayerEquipment matching the query
 * @method     ChildPlayerEquipment findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlayerEquipment matching the query, or a new ChildPlayerEquipment object populated from the query conditions when no match is found
 *
 * @method     ChildPlayerEquipment findOneById(int $id) Return the first ChildPlayerEquipment filtered by the id column
 * @method     ChildPlayerEquipment findOneByPlayerId(int $player_id) Return the first ChildPlayerEquipment filtered by the player_id column
 * @method     ChildPlayerEquipment findOneByWeapon1PlayerItemId(int $weapon1_player_item_id) Return the first ChildPlayerEquipment filtered by the weapon1_player_item_id column
 * @method     ChildPlayerEquipment findOneByWeapon2PlayerItemId(int $weapon2_player_item_id) Return the first ChildPlayerEquipment filtered by the weapon2_player_item_id column
 * @method     ChildPlayerEquipment findOneByHeadPlayerItemId(int $head_player_item_id) Return the first ChildPlayerEquipment filtered by the head_player_item_id column
 * @method     ChildPlayerEquipment findOneByLeftArmPlayerItemId(int $left_arm_player_item_id) Return the first ChildPlayerEquipment filtered by the left_arm_player_item_id column
 * @method     ChildPlayerEquipment findOneByRightArmPlayerItemId(int $right_arm_player_item_id) Return the first ChildPlayerEquipment filtered by the right_arm_player_item_id column
 * @method     ChildPlayerEquipment findOneByLeftLegPlayerItemId(int $left_leg_player_item_id) Return the first ChildPlayerEquipment filtered by the left_leg_player_item_id column
 * @method     ChildPlayerEquipment findOneByRightLegPlayerItemId(int $right_leg_player_item_id) Return the first ChildPlayerEquipment filtered by the right_leg_player_item_id column
 * @method     ChildPlayerEquipment findOneByCreatedAt(string $created_at) Return the first ChildPlayerEquipment filtered by the created_at column
 * @method     ChildPlayerEquipment findOneByUpdatedAt(string $updated_at) Return the first ChildPlayerEquipment filtered by the updated_at column *

 * @method     ChildPlayerEquipment requirePk($key, ConnectionInterface $con = null) Return the ChildPlayerEquipment by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerEquipment requireOne(ConnectionInterface $con = null) Return the first ChildPlayerEquipment matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayerEquipment requireOneById(int $id) Return the first ChildPlayerEquipment filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerEquipment requireOneByPlayerId(int $player_id) Return the first ChildPlayerEquipment filtered by the player_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerEquipment requireOneByWeapon1PlayerItemId(int $weapon1_player_item_id) Return the first ChildPlayerEquipment filtered by the weapon1_player_item_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerEquipment requireOneByWeapon2PlayerItemId(int $weapon2_player_item_id) Return the first ChildPlayerEquipment filtered by the weapon2_player_item_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerEquipment requireOneByHeadPlayerItemId(int $head_player_item_id) Return the first ChildPlayerEquipment filtered by the head_player_item_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerEquipment requireOneByLeftArmPlayerItemId(int $left_arm_player_item_id) Return the first ChildPlayerEquipment filtered by the left_arm_player_item_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerEquipment requireOneByRightArmPlayerItemId(int $right_arm_player_item_id) Return the first ChildPlayerEquipment filtered by the right_arm_player_item_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerEquipment requireOneByLeftLegPlayerItemId(int $left_leg_player_item_id) Return the first ChildPlayerEquipment filtered by the left_leg_player_item_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerEquipment requireOneByRightLegPlayerItemId(int $right_leg_player_item_id) Return the first ChildPlayerEquipment filtered by the right_leg_player_item_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerEquipment requireOneByCreatedAt(string $created_at) Return the first ChildPlayerEquipment filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerEquipment requireOneByUpdatedAt(string $updated_at) Return the first ChildPlayerEquipment filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayerEquipment[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlayerEquipment objects based on current ModelCriteria
 * @method     ChildPlayerEquipment[]|ObjectCollection findById(int $id) Return ChildPlayerEquipment objects filtered by the id column
 * @method     ChildPlayerEquipment[]|ObjectCollection findByPlayerId(int $player_id) Return ChildPlayerEquipment objects filtered by the player_id column
 * @method     ChildPlayerEquipment[]|ObjectCollection findByWeapon1PlayerItemId(int $weapon1_player_item_id) Return ChildPlayerEquipment objects filtered by the weapon1_player_item_id column
 * @method     ChildPlayerEquipment[]|ObjectCollection findByWeapon2PlayerItemId(int $weapon2_player_item_id) Return ChildPlayerEquipment objects filtered by the weapon2_player_item_id column
 * @method     ChildPlayerEquipment[]|ObjectCollection findByHeadPlayerItemId(int $head_player_item_id) Return ChildPlayerEquipment objects filtered by the head_player_item_id column
 * @method     ChildPlayerEquipment[]|ObjectCollection findByLeftArmPlayerItemId(int $left_arm_player_item_id) Return ChildPlayerEquipment objects filtered by the left_arm_player_item_id column
 * @method     ChildPlayerEquipment[]|ObjectCollection findByRightArmPlayerItemId(int $right_arm_player_item_id) Return ChildPlayerEquipment objects filtered by the right_arm_player_item_id column
 * @method     ChildPlayerEquipment[]|ObjectCollection findByLeftLegPlayerItemId(int $left_leg_player_item_id) Return ChildPlayerEquipment objects filtered by the left_leg_player_item_id column
 * @method     ChildPlayerEquipment[]|ObjectCollection findByRightLegPlayerItemId(int $right_leg_player_item_id) Return ChildPlayerEquipment objects filtered by the right_leg_player_item_id column
 * @method     ChildPlayerEquipment[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildPlayerEquipment objects filtered by the created_at column
 * @method     ChildPlayerEquipment[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildPlayerEquipment objects filtered by the updated_at column
 * @method     ChildPlayerEquipment[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlayerEquipmentQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \app\model\Base\PlayerEquipmentQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\app\\model\\PlayerEquipment', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlayerEquipmentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlayerEquipmentQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlayerEquipmentQuery) {
            return $criteria;
        }
        $query = new ChildPlayerEquipmentQuery();
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
     * @return ChildPlayerEquipment|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlayerEquipmentTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PlayerEquipmentTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPlayerEquipment A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, player_id, weapon1_player_item_id, weapon2_player_item_id, head_player_item_id, left_arm_player_item_id, right_arm_player_item_id, left_leg_player_item_id, right_leg_player_item_id, created_at, updated_at FROM player_equipment WHERE id = :p0';
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
            /** @var ChildPlayerEquipment $obj */
            $obj = new ChildPlayerEquipment();
            $obj->hydrate($row);
            PlayerEquipmentTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPlayerEquipment|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PlayerEquipmentTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PlayerEquipmentTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerEquipmentTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterByPlayerId($playerId = null, $comparison = null)
    {
        if (is_array($playerId)) {
            $useMinMax = false;
            if (isset($playerId['min'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_PLAYER_ID, $playerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerId['max'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_PLAYER_ID, $playerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerEquipmentTableMap::COL_PLAYER_ID, $playerId, $comparison);
    }

    /**
     * Filter the query on the weapon1_player_item_id column
     *
     * Example usage:
     * <code>
     * $query->filterByWeapon1PlayerItemId(1234); // WHERE weapon1_player_item_id = 1234
     * $query->filterByWeapon1PlayerItemId(array(12, 34)); // WHERE weapon1_player_item_id IN (12, 34)
     * $query->filterByWeapon1PlayerItemId(array('min' => 12)); // WHERE weapon1_player_item_id > 12
     * </code>
     *
     * @see       filterByPlayerItemRelatedByWeapon1PlayerItemId()
     *
     * @param     mixed $weapon1PlayerItemId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterByWeapon1PlayerItemId($weapon1PlayerItemId = null, $comparison = null)
    {
        if (is_array($weapon1PlayerItemId)) {
            $useMinMax = false;
            if (isset($weapon1PlayerItemId['min'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_WEAPON1_PLAYER_ITEM_ID, $weapon1PlayerItemId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($weapon1PlayerItemId['max'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_WEAPON1_PLAYER_ITEM_ID, $weapon1PlayerItemId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerEquipmentTableMap::COL_WEAPON1_PLAYER_ITEM_ID, $weapon1PlayerItemId, $comparison);
    }

    /**
     * Filter the query on the weapon2_player_item_id column
     *
     * Example usage:
     * <code>
     * $query->filterByWeapon2PlayerItemId(1234); // WHERE weapon2_player_item_id = 1234
     * $query->filterByWeapon2PlayerItemId(array(12, 34)); // WHERE weapon2_player_item_id IN (12, 34)
     * $query->filterByWeapon2PlayerItemId(array('min' => 12)); // WHERE weapon2_player_item_id > 12
     * </code>
     *
     * @see       filterByPlayerItemRelatedByWeapon2PlayerItemId()
     *
     * @param     mixed $weapon2PlayerItemId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterByWeapon2PlayerItemId($weapon2PlayerItemId = null, $comparison = null)
    {
        if (is_array($weapon2PlayerItemId)) {
            $useMinMax = false;
            if (isset($weapon2PlayerItemId['min'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_WEAPON2_PLAYER_ITEM_ID, $weapon2PlayerItemId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($weapon2PlayerItemId['max'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_WEAPON2_PLAYER_ITEM_ID, $weapon2PlayerItemId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerEquipmentTableMap::COL_WEAPON2_PLAYER_ITEM_ID, $weapon2PlayerItemId, $comparison);
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
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterByHeadPlayerItemId($headPlayerItemId = null, $comparison = null)
    {
        if (is_array($headPlayerItemId)) {
            $useMinMax = false;
            if (isset($headPlayerItemId['min'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_HEAD_PLAYER_ITEM_ID, $headPlayerItemId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($headPlayerItemId['max'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_HEAD_PLAYER_ITEM_ID, $headPlayerItemId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerEquipmentTableMap::COL_HEAD_PLAYER_ITEM_ID, $headPlayerItemId, $comparison);
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
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterByLeftArmPlayerItemId($leftArmPlayerItemId = null, $comparison = null)
    {
        if (is_array($leftArmPlayerItemId)) {
            $useMinMax = false;
            if (isset($leftArmPlayerItemId['min'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_LEFT_ARM_PLAYER_ITEM_ID, $leftArmPlayerItemId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($leftArmPlayerItemId['max'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_LEFT_ARM_PLAYER_ITEM_ID, $leftArmPlayerItemId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerEquipmentTableMap::COL_LEFT_ARM_PLAYER_ITEM_ID, $leftArmPlayerItemId, $comparison);
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
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterByRightArmPlayerItemId($rightArmPlayerItemId = null, $comparison = null)
    {
        if (is_array($rightArmPlayerItemId)) {
            $useMinMax = false;
            if (isset($rightArmPlayerItemId['min'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_RIGHT_ARM_PLAYER_ITEM_ID, $rightArmPlayerItemId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rightArmPlayerItemId['max'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_RIGHT_ARM_PLAYER_ITEM_ID, $rightArmPlayerItemId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerEquipmentTableMap::COL_RIGHT_ARM_PLAYER_ITEM_ID, $rightArmPlayerItemId, $comparison);
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
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterByLeftLegPlayerItemId($leftLegPlayerItemId = null, $comparison = null)
    {
        if (is_array($leftLegPlayerItemId)) {
            $useMinMax = false;
            if (isset($leftLegPlayerItemId['min'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_LEFT_LEG_PLAYER_ITEM_ID, $leftLegPlayerItemId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($leftLegPlayerItemId['max'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_LEFT_LEG_PLAYER_ITEM_ID, $leftLegPlayerItemId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerEquipmentTableMap::COL_LEFT_LEG_PLAYER_ITEM_ID, $leftLegPlayerItemId, $comparison);
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
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterByRightLegPlayerItemId($rightLegPlayerItemId = null, $comparison = null)
    {
        if (is_array($rightLegPlayerItemId)) {
            $useMinMax = false;
            if (isset($rightLegPlayerItemId['min'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_RIGHT_LEG_PLAYER_ITEM_ID, $rightLegPlayerItemId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rightLegPlayerItemId['max'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_RIGHT_LEG_PLAYER_ITEM_ID, $rightLegPlayerItemId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerEquipmentTableMap::COL_RIGHT_LEG_PLAYER_ITEM_ID, $rightLegPlayerItemId, $comparison);
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
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerEquipmentTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PlayerEquipmentTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerEquipmentTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \app\model\Player object
     *
     * @param \app\model\Player|ObjectCollection $player The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterByPlayer($player, $comparison = null)
    {
        if ($player instanceof \app\model\Player) {
            return $this
                ->addUsingAlias(PlayerEquipmentTableMap::COL_PLAYER_ID, $player->getId(), $comparison);
        } elseif ($player instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerEquipmentTableMap::COL_PLAYER_ID, $player->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
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
     * @return ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterByPlayerItemRelatedByWeapon1PlayerItemId($playerItem, $comparison = null)
    {
        if ($playerItem instanceof \app\model\PlayerItem) {
            return $this
                ->addUsingAlias(PlayerEquipmentTableMap::COL_WEAPON1_PLAYER_ITEM_ID, $playerItem->getId(), $comparison);
        } elseif ($playerItem instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerEquipmentTableMap::COL_WEAPON1_PLAYER_ITEM_ID, $playerItem->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPlayerItemRelatedByWeapon1PlayerItemId() only accepts arguments of type \app\model\PlayerItem or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerItemRelatedByWeapon1PlayerItemId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function joinPlayerItemRelatedByWeapon1PlayerItemId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerItemRelatedByWeapon1PlayerItemId');

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
            $this->addJoinObject($join, 'PlayerItemRelatedByWeapon1PlayerItemId');
        }

        return $this;
    }

    /**
     * Use the PlayerItemRelatedByWeapon1PlayerItemId relation PlayerItem object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerItemQuery A secondary query class using the current class as primary query
     */
    public function usePlayerItemRelatedByWeapon1PlayerItemIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerItemRelatedByWeapon1PlayerItemId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerItemRelatedByWeapon1PlayerItemId', '\app\model\PlayerItemQuery');
    }

    /**
     * Filter the query by a related \app\model\PlayerItem object
     *
     * @param \app\model\PlayerItem|ObjectCollection $playerItem The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterByPlayerItemRelatedByWeapon2PlayerItemId($playerItem, $comparison = null)
    {
        if ($playerItem instanceof \app\model\PlayerItem) {
            return $this
                ->addUsingAlias(PlayerEquipmentTableMap::COL_WEAPON2_PLAYER_ITEM_ID, $playerItem->getId(), $comparison);
        } elseif ($playerItem instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerEquipmentTableMap::COL_WEAPON2_PLAYER_ITEM_ID, $playerItem->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPlayerItemRelatedByWeapon2PlayerItemId() only accepts arguments of type \app\model\PlayerItem or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerItemRelatedByWeapon2PlayerItemId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function joinPlayerItemRelatedByWeapon2PlayerItemId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerItemRelatedByWeapon2PlayerItemId');

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
            $this->addJoinObject($join, 'PlayerItemRelatedByWeapon2PlayerItemId');
        }

        return $this;
    }

    /**
     * Use the PlayerItemRelatedByWeapon2PlayerItemId relation PlayerItem object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PlayerItemQuery A secondary query class using the current class as primary query
     */
    public function usePlayerItemRelatedByWeapon2PlayerItemIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerItemRelatedByWeapon2PlayerItemId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerItemRelatedByWeapon2PlayerItemId', '\app\model\PlayerItemQuery');
    }

    /**
     * Filter the query by a related \app\model\PlayerItem object
     *
     * @param \app\model\PlayerItem|ObjectCollection $playerItem The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterByPlayerItemRelatedByHeadPlayerItemId($playerItem, $comparison = null)
    {
        if ($playerItem instanceof \app\model\PlayerItem) {
            return $this
                ->addUsingAlias(PlayerEquipmentTableMap::COL_HEAD_PLAYER_ITEM_ID, $playerItem->getId(), $comparison);
        } elseif ($playerItem instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerEquipmentTableMap::COL_HEAD_PLAYER_ITEM_ID, $playerItem->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function joinPlayerItemRelatedByHeadPlayerItemId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function usePlayerItemRelatedByHeadPlayerItemIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @return ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterByPlayerItemRelatedByLeftArmPlayerItemId($playerItem, $comparison = null)
    {
        if ($playerItem instanceof \app\model\PlayerItem) {
            return $this
                ->addUsingAlias(PlayerEquipmentTableMap::COL_LEFT_ARM_PLAYER_ITEM_ID, $playerItem->getId(), $comparison);
        } elseif ($playerItem instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerEquipmentTableMap::COL_LEFT_ARM_PLAYER_ITEM_ID, $playerItem->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function joinPlayerItemRelatedByLeftArmPlayerItemId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function usePlayerItemRelatedByLeftArmPlayerItemIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @return ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterByPlayerItemRelatedByRightArmPlayerItemId($playerItem, $comparison = null)
    {
        if ($playerItem instanceof \app\model\PlayerItem) {
            return $this
                ->addUsingAlias(PlayerEquipmentTableMap::COL_RIGHT_ARM_PLAYER_ITEM_ID, $playerItem->getId(), $comparison);
        } elseif ($playerItem instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerEquipmentTableMap::COL_RIGHT_ARM_PLAYER_ITEM_ID, $playerItem->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function joinPlayerItemRelatedByRightArmPlayerItemId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function usePlayerItemRelatedByRightArmPlayerItemIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @return ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterByPlayerItemRelatedByLeftLegPlayerItemId($playerItem, $comparison = null)
    {
        if ($playerItem instanceof \app\model\PlayerItem) {
            return $this
                ->addUsingAlias(PlayerEquipmentTableMap::COL_LEFT_LEG_PLAYER_ITEM_ID, $playerItem->getId(), $comparison);
        } elseif ($playerItem instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerEquipmentTableMap::COL_LEFT_LEG_PLAYER_ITEM_ID, $playerItem->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function joinPlayerItemRelatedByLeftLegPlayerItemId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function usePlayerItemRelatedByLeftLegPlayerItemIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @return ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function filterByPlayerItemRelatedByRightLegPlayerItemId($playerItem, $comparison = null)
    {
        if ($playerItem instanceof \app\model\PlayerItem) {
            return $this
                ->addUsingAlias(PlayerEquipmentTableMap::COL_RIGHT_LEG_PLAYER_ITEM_ID, $playerItem->getId(), $comparison);
        } elseif ($playerItem instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerEquipmentTableMap::COL_RIGHT_LEG_PLAYER_ITEM_ID, $playerItem->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function joinPlayerItemRelatedByRightLegPlayerItemId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function usePlayerItemRelatedByRightLegPlayerItemIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerItemRelatedByRightLegPlayerItemId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerItemRelatedByRightLegPlayerItemId', '\app\model\PlayerItemQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPlayerEquipment $playerEquipment Object to remove from the list of results
     *
     * @return $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function prune($playerEquipment = null)
    {
        if ($playerEquipment) {
            $this->addUsingAlias(PlayerEquipmentTableMap::COL_ID, $playerEquipment->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the player_equipment table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerEquipmentTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlayerEquipmentTableMap::clearInstancePool();
            PlayerEquipmentTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerEquipmentTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlayerEquipmentTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlayerEquipmentTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlayerEquipmentTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PlayerEquipmentTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PlayerEquipmentTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PlayerEquipmentTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PlayerEquipmentTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PlayerEquipmentTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildPlayerEquipmentQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PlayerEquipmentTableMap::COL_CREATED_AT);
    }

} // PlayerEquipmentQuery
