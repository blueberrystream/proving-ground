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
 * @method     ChildPlayerDeckQuery orderByFirstPropriumId($order = Criteria::ASC) Order by the first_proprium_id column
 * @method     ChildPlayerDeckQuery orderBySecondPropriumId($order = Criteria::ASC) Order by the second_proprium_id column
 * @method     ChildPlayerDeckQuery orderByThirdPropriumId($order = Criteria::ASC) Order by the third_proprium_id column
 * @method     ChildPlayerDeckQuery orderByFourthPropriumId($order = Criteria::ASC) Order by the fourth_proprium_id column
 * @method     ChildPlayerDeckQuery orderByFifthPropriumId($order = Criteria::ASC) Order by the fifth_proprium_id column
 * @method     ChildPlayerDeckQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildPlayerDeckQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildPlayerDeckQuery groupById() Group by the id column
 * @method     ChildPlayerDeckQuery groupByPlayerId() Group by the player_id column
 * @method     ChildPlayerDeckQuery groupByFirstPropriumId() Group by the first_proprium_id column
 * @method     ChildPlayerDeckQuery groupBySecondPropriumId() Group by the second_proprium_id column
 * @method     ChildPlayerDeckQuery groupByThirdPropriumId() Group by the third_proprium_id column
 * @method     ChildPlayerDeckQuery groupByFourthPropriumId() Group by the fourth_proprium_id column
 * @method     ChildPlayerDeckQuery groupByFifthPropriumId() Group by the fifth_proprium_id column
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
 * @method     ChildPlayerDeckQuery leftJoinPropriumRelatedByFirstPropriumId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PropriumRelatedByFirstPropriumId relation
 * @method     ChildPlayerDeckQuery rightJoinPropriumRelatedByFirstPropriumId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PropriumRelatedByFirstPropriumId relation
 * @method     ChildPlayerDeckQuery innerJoinPropriumRelatedByFirstPropriumId($relationAlias = null) Adds a INNER JOIN clause to the query using the PropriumRelatedByFirstPropriumId relation
 *
 * @method     ChildPlayerDeckQuery joinWithPropriumRelatedByFirstPropriumId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PropriumRelatedByFirstPropriumId relation
 *
 * @method     ChildPlayerDeckQuery leftJoinWithPropriumRelatedByFirstPropriumId() Adds a LEFT JOIN clause and with to the query using the PropriumRelatedByFirstPropriumId relation
 * @method     ChildPlayerDeckQuery rightJoinWithPropriumRelatedByFirstPropriumId() Adds a RIGHT JOIN clause and with to the query using the PropriumRelatedByFirstPropriumId relation
 * @method     ChildPlayerDeckQuery innerJoinWithPropriumRelatedByFirstPropriumId() Adds a INNER JOIN clause and with to the query using the PropriumRelatedByFirstPropriumId relation
 *
 * @method     ChildPlayerDeckQuery leftJoinPropriumRelatedBySecondPropriumId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PropriumRelatedBySecondPropriumId relation
 * @method     ChildPlayerDeckQuery rightJoinPropriumRelatedBySecondPropriumId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PropriumRelatedBySecondPropriumId relation
 * @method     ChildPlayerDeckQuery innerJoinPropriumRelatedBySecondPropriumId($relationAlias = null) Adds a INNER JOIN clause to the query using the PropriumRelatedBySecondPropriumId relation
 *
 * @method     ChildPlayerDeckQuery joinWithPropriumRelatedBySecondPropriumId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PropriumRelatedBySecondPropriumId relation
 *
 * @method     ChildPlayerDeckQuery leftJoinWithPropriumRelatedBySecondPropriumId() Adds a LEFT JOIN clause and with to the query using the PropriumRelatedBySecondPropriumId relation
 * @method     ChildPlayerDeckQuery rightJoinWithPropriumRelatedBySecondPropriumId() Adds a RIGHT JOIN clause and with to the query using the PropriumRelatedBySecondPropriumId relation
 * @method     ChildPlayerDeckQuery innerJoinWithPropriumRelatedBySecondPropriumId() Adds a INNER JOIN clause and with to the query using the PropriumRelatedBySecondPropriumId relation
 *
 * @method     ChildPlayerDeckQuery leftJoinPropriumRelatedByThirdPropriumId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PropriumRelatedByThirdPropriumId relation
 * @method     ChildPlayerDeckQuery rightJoinPropriumRelatedByThirdPropriumId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PropriumRelatedByThirdPropriumId relation
 * @method     ChildPlayerDeckQuery innerJoinPropriumRelatedByThirdPropriumId($relationAlias = null) Adds a INNER JOIN clause to the query using the PropriumRelatedByThirdPropriumId relation
 *
 * @method     ChildPlayerDeckQuery joinWithPropriumRelatedByThirdPropriumId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PropriumRelatedByThirdPropriumId relation
 *
 * @method     ChildPlayerDeckQuery leftJoinWithPropriumRelatedByThirdPropriumId() Adds a LEFT JOIN clause and with to the query using the PropriumRelatedByThirdPropriumId relation
 * @method     ChildPlayerDeckQuery rightJoinWithPropriumRelatedByThirdPropriumId() Adds a RIGHT JOIN clause and with to the query using the PropriumRelatedByThirdPropriumId relation
 * @method     ChildPlayerDeckQuery innerJoinWithPropriumRelatedByThirdPropriumId() Adds a INNER JOIN clause and with to the query using the PropriumRelatedByThirdPropriumId relation
 *
 * @method     ChildPlayerDeckQuery leftJoinPropriumRelatedByFourthPropriumId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PropriumRelatedByFourthPropriumId relation
 * @method     ChildPlayerDeckQuery rightJoinPropriumRelatedByFourthPropriumId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PropriumRelatedByFourthPropriumId relation
 * @method     ChildPlayerDeckQuery innerJoinPropriumRelatedByFourthPropriumId($relationAlias = null) Adds a INNER JOIN clause to the query using the PropriumRelatedByFourthPropriumId relation
 *
 * @method     ChildPlayerDeckQuery joinWithPropriumRelatedByFourthPropriumId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PropriumRelatedByFourthPropriumId relation
 *
 * @method     ChildPlayerDeckQuery leftJoinWithPropriumRelatedByFourthPropriumId() Adds a LEFT JOIN clause and with to the query using the PropriumRelatedByFourthPropriumId relation
 * @method     ChildPlayerDeckQuery rightJoinWithPropriumRelatedByFourthPropriumId() Adds a RIGHT JOIN clause and with to the query using the PropriumRelatedByFourthPropriumId relation
 * @method     ChildPlayerDeckQuery innerJoinWithPropriumRelatedByFourthPropriumId() Adds a INNER JOIN clause and with to the query using the PropriumRelatedByFourthPropriumId relation
 *
 * @method     ChildPlayerDeckQuery leftJoinPropriumRelatedByFifthPropriumId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PropriumRelatedByFifthPropriumId relation
 * @method     ChildPlayerDeckQuery rightJoinPropriumRelatedByFifthPropriumId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PropriumRelatedByFifthPropriumId relation
 * @method     ChildPlayerDeckQuery innerJoinPropriumRelatedByFifthPropriumId($relationAlias = null) Adds a INNER JOIN clause to the query using the PropriumRelatedByFifthPropriumId relation
 *
 * @method     ChildPlayerDeckQuery joinWithPropriumRelatedByFifthPropriumId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PropriumRelatedByFifthPropriumId relation
 *
 * @method     ChildPlayerDeckQuery leftJoinWithPropriumRelatedByFifthPropriumId() Adds a LEFT JOIN clause and with to the query using the PropriumRelatedByFifthPropriumId relation
 * @method     ChildPlayerDeckQuery rightJoinWithPropriumRelatedByFifthPropriumId() Adds a RIGHT JOIN clause and with to the query using the PropriumRelatedByFifthPropriumId relation
 * @method     ChildPlayerDeckQuery innerJoinWithPropriumRelatedByFifthPropriumId() Adds a INNER JOIN clause and with to the query using the PropriumRelatedByFifthPropriumId relation
 *
 * @method     \app\model\PlayerQuery|\app\model\PropriumQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPlayerDeck findOne(ConnectionInterface $con = null) Return the first ChildPlayerDeck matching the query
 * @method     ChildPlayerDeck findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlayerDeck matching the query, or a new ChildPlayerDeck object populated from the query conditions when no match is found
 *
 * @method     ChildPlayerDeck findOneById(int $id) Return the first ChildPlayerDeck filtered by the id column
 * @method     ChildPlayerDeck findOneByPlayerId(int $player_id) Return the first ChildPlayerDeck filtered by the player_id column
 * @method     ChildPlayerDeck findOneByFirstPropriumId(int $first_proprium_id) Return the first ChildPlayerDeck filtered by the first_proprium_id column
 * @method     ChildPlayerDeck findOneBySecondPropriumId(int $second_proprium_id) Return the first ChildPlayerDeck filtered by the second_proprium_id column
 * @method     ChildPlayerDeck findOneByThirdPropriumId(int $third_proprium_id) Return the first ChildPlayerDeck filtered by the third_proprium_id column
 * @method     ChildPlayerDeck findOneByFourthPropriumId(int $fourth_proprium_id) Return the first ChildPlayerDeck filtered by the fourth_proprium_id column
 * @method     ChildPlayerDeck findOneByFifthPropriumId(int $fifth_proprium_id) Return the first ChildPlayerDeck filtered by the fifth_proprium_id column
 * @method     ChildPlayerDeck findOneByCreatedAt(string $created_at) Return the first ChildPlayerDeck filtered by the created_at column
 * @method     ChildPlayerDeck findOneByUpdatedAt(string $updated_at) Return the first ChildPlayerDeck filtered by the updated_at column *

 * @method     ChildPlayerDeck requirePk($key, ConnectionInterface $con = null) Return the ChildPlayerDeck by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerDeck requireOne(ConnectionInterface $con = null) Return the first ChildPlayerDeck matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayerDeck requireOneById(int $id) Return the first ChildPlayerDeck filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerDeck requireOneByPlayerId(int $player_id) Return the first ChildPlayerDeck filtered by the player_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerDeck requireOneByFirstPropriumId(int $first_proprium_id) Return the first ChildPlayerDeck filtered by the first_proprium_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerDeck requireOneBySecondPropriumId(int $second_proprium_id) Return the first ChildPlayerDeck filtered by the second_proprium_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerDeck requireOneByThirdPropriumId(int $third_proprium_id) Return the first ChildPlayerDeck filtered by the third_proprium_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerDeck requireOneByFourthPropriumId(int $fourth_proprium_id) Return the first ChildPlayerDeck filtered by the fourth_proprium_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerDeck requireOneByFifthPropriumId(int $fifth_proprium_id) Return the first ChildPlayerDeck filtered by the fifth_proprium_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerDeck requireOneByCreatedAt(string $created_at) Return the first ChildPlayerDeck filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerDeck requireOneByUpdatedAt(string $updated_at) Return the first ChildPlayerDeck filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayerDeck[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlayerDeck objects based on current ModelCriteria
 * @method     ChildPlayerDeck[]|ObjectCollection findById(int $id) Return ChildPlayerDeck objects filtered by the id column
 * @method     ChildPlayerDeck[]|ObjectCollection findByPlayerId(int $player_id) Return ChildPlayerDeck objects filtered by the player_id column
 * @method     ChildPlayerDeck[]|ObjectCollection findByFirstPropriumId(int $first_proprium_id) Return ChildPlayerDeck objects filtered by the first_proprium_id column
 * @method     ChildPlayerDeck[]|ObjectCollection findBySecondPropriumId(int $second_proprium_id) Return ChildPlayerDeck objects filtered by the second_proprium_id column
 * @method     ChildPlayerDeck[]|ObjectCollection findByThirdPropriumId(int $third_proprium_id) Return ChildPlayerDeck objects filtered by the third_proprium_id column
 * @method     ChildPlayerDeck[]|ObjectCollection findByFourthPropriumId(int $fourth_proprium_id) Return ChildPlayerDeck objects filtered by the fourth_proprium_id column
 * @method     ChildPlayerDeck[]|ObjectCollection findByFifthPropriumId(int $fifth_proprium_id) Return ChildPlayerDeck objects filtered by the fifth_proprium_id column
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
        $sql = 'SELECT id, player_id, first_proprium_id, second_proprium_id, third_proprium_id, fourth_proprium_id, fifth_proprium_id, created_at, updated_at FROM player_deck WHERE id = :p0';
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
     * Filter the query on the first_proprium_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstPropriumId(1234); // WHERE first_proprium_id = 1234
     * $query->filterByFirstPropriumId(array(12, 34)); // WHERE first_proprium_id IN (12, 34)
     * $query->filterByFirstPropriumId(array('min' => 12)); // WHERE first_proprium_id > 12
     * </code>
     *
     * @see       filterByPropriumRelatedByFirstPropriumId()
     *
     * @param     mixed $firstPropriumId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByFirstPropriumId($firstPropriumId = null, $comparison = null)
    {
        if (is_array($firstPropriumId)) {
            $useMinMax = false;
            if (isset($firstPropriumId['min'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_FIRST_PROPRIUM_ID, $firstPropriumId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($firstPropriumId['max'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_FIRST_PROPRIUM_ID, $firstPropriumId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerDeckTableMap::COL_FIRST_PROPRIUM_ID, $firstPropriumId, $comparison);
    }

    /**
     * Filter the query on the second_proprium_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySecondPropriumId(1234); // WHERE second_proprium_id = 1234
     * $query->filterBySecondPropriumId(array(12, 34)); // WHERE second_proprium_id IN (12, 34)
     * $query->filterBySecondPropriumId(array('min' => 12)); // WHERE second_proprium_id > 12
     * </code>
     *
     * @see       filterByPropriumRelatedBySecondPropriumId()
     *
     * @param     mixed $secondPropriumId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterBySecondPropriumId($secondPropriumId = null, $comparison = null)
    {
        if (is_array($secondPropriumId)) {
            $useMinMax = false;
            if (isset($secondPropriumId['min'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_SECOND_PROPRIUM_ID, $secondPropriumId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($secondPropriumId['max'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_SECOND_PROPRIUM_ID, $secondPropriumId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerDeckTableMap::COL_SECOND_PROPRIUM_ID, $secondPropriumId, $comparison);
    }

    /**
     * Filter the query on the third_proprium_id column
     *
     * Example usage:
     * <code>
     * $query->filterByThirdPropriumId(1234); // WHERE third_proprium_id = 1234
     * $query->filterByThirdPropriumId(array(12, 34)); // WHERE third_proprium_id IN (12, 34)
     * $query->filterByThirdPropriumId(array('min' => 12)); // WHERE third_proprium_id > 12
     * </code>
     *
     * @see       filterByPropriumRelatedByThirdPropriumId()
     *
     * @param     mixed $thirdPropriumId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByThirdPropriumId($thirdPropriumId = null, $comparison = null)
    {
        if (is_array($thirdPropriumId)) {
            $useMinMax = false;
            if (isset($thirdPropriumId['min'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_THIRD_PROPRIUM_ID, $thirdPropriumId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($thirdPropriumId['max'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_THIRD_PROPRIUM_ID, $thirdPropriumId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerDeckTableMap::COL_THIRD_PROPRIUM_ID, $thirdPropriumId, $comparison);
    }

    /**
     * Filter the query on the fourth_proprium_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFourthPropriumId(1234); // WHERE fourth_proprium_id = 1234
     * $query->filterByFourthPropriumId(array(12, 34)); // WHERE fourth_proprium_id IN (12, 34)
     * $query->filterByFourthPropriumId(array('min' => 12)); // WHERE fourth_proprium_id > 12
     * </code>
     *
     * @see       filterByPropriumRelatedByFourthPropriumId()
     *
     * @param     mixed $fourthPropriumId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByFourthPropriumId($fourthPropriumId = null, $comparison = null)
    {
        if (is_array($fourthPropriumId)) {
            $useMinMax = false;
            if (isset($fourthPropriumId['min'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_FOURTH_PROPRIUM_ID, $fourthPropriumId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fourthPropriumId['max'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_FOURTH_PROPRIUM_ID, $fourthPropriumId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerDeckTableMap::COL_FOURTH_PROPRIUM_ID, $fourthPropriumId, $comparison);
    }

    /**
     * Filter the query on the fifth_proprium_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFifthPropriumId(1234); // WHERE fifth_proprium_id = 1234
     * $query->filterByFifthPropriumId(array(12, 34)); // WHERE fifth_proprium_id IN (12, 34)
     * $query->filterByFifthPropriumId(array('min' => 12)); // WHERE fifth_proprium_id > 12
     * </code>
     *
     * @see       filterByPropriumRelatedByFifthPropriumId()
     *
     * @param     mixed $fifthPropriumId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByFifthPropriumId($fifthPropriumId = null, $comparison = null)
    {
        if (is_array($fifthPropriumId)) {
            $useMinMax = false;
            if (isset($fifthPropriumId['min'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_FIFTH_PROPRIUM_ID, $fifthPropriumId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fifthPropriumId['max'])) {
                $this->addUsingAlias(PlayerDeckTableMap::COL_FIFTH_PROPRIUM_ID, $fifthPropriumId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerDeckTableMap::COL_FIFTH_PROPRIUM_ID, $fifthPropriumId, $comparison);
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
     * Filter the query by a related \app\model\Proprium object
     *
     * @param \app\model\Proprium|ObjectCollection $proprium The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByPropriumRelatedByFirstPropriumId($proprium, $comparison = null)
    {
        if ($proprium instanceof \app\model\Proprium) {
            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_FIRST_PROPRIUM_ID, $proprium->getId(), $comparison);
        } elseif ($proprium instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_FIRST_PROPRIUM_ID, $proprium->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPropriumRelatedByFirstPropriumId() only accepts arguments of type \app\model\Proprium or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PropriumRelatedByFirstPropriumId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function joinPropriumRelatedByFirstPropriumId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PropriumRelatedByFirstPropriumId');

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
            $this->addJoinObject($join, 'PropriumRelatedByFirstPropriumId');
        }

        return $this;
    }

    /**
     * Use the PropriumRelatedByFirstPropriumId relation Proprium object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PropriumQuery A secondary query class using the current class as primary query
     */
    public function usePropriumRelatedByFirstPropriumIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPropriumRelatedByFirstPropriumId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PropriumRelatedByFirstPropriumId', '\app\model\PropriumQuery');
    }

    /**
     * Filter the query by a related \app\model\Proprium object
     *
     * @param \app\model\Proprium|ObjectCollection $proprium The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByPropriumRelatedBySecondPropriumId($proprium, $comparison = null)
    {
        if ($proprium instanceof \app\model\Proprium) {
            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_SECOND_PROPRIUM_ID, $proprium->getId(), $comparison);
        } elseif ($proprium instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_SECOND_PROPRIUM_ID, $proprium->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPropriumRelatedBySecondPropriumId() only accepts arguments of type \app\model\Proprium or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PropriumRelatedBySecondPropriumId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function joinPropriumRelatedBySecondPropriumId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PropriumRelatedBySecondPropriumId');

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
            $this->addJoinObject($join, 'PropriumRelatedBySecondPropriumId');
        }

        return $this;
    }

    /**
     * Use the PropriumRelatedBySecondPropriumId relation Proprium object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PropriumQuery A secondary query class using the current class as primary query
     */
    public function usePropriumRelatedBySecondPropriumIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPropriumRelatedBySecondPropriumId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PropriumRelatedBySecondPropriumId', '\app\model\PropriumQuery');
    }

    /**
     * Filter the query by a related \app\model\Proprium object
     *
     * @param \app\model\Proprium|ObjectCollection $proprium The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByPropriumRelatedByThirdPropriumId($proprium, $comparison = null)
    {
        if ($proprium instanceof \app\model\Proprium) {
            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_THIRD_PROPRIUM_ID, $proprium->getId(), $comparison);
        } elseif ($proprium instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_THIRD_PROPRIUM_ID, $proprium->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPropriumRelatedByThirdPropriumId() only accepts arguments of type \app\model\Proprium or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PropriumRelatedByThirdPropriumId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function joinPropriumRelatedByThirdPropriumId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PropriumRelatedByThirdPropriumId');

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
            $this->addJoinObject($join, 'PropriumRelatedByThirdPropriumId');
        }

        return $this;
    }

    /**
     * Use the PropriumRelatedByThirdPropriumId relation Proprium object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PropriumQuery A secondary query class using the current class as primary query
     */
    public function usePropriumRelatedByThirdPropriumIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPropriumRelatedByThirdPropriumId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PropriumRelatedByThirdPropriumId', '\app\model\PropriumQuery');
    }

    /**
     * Filter the query by a related \app\model\Proprium object
     *
     * @param \app\model\Proprium|ObjectCollection $proprium The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByPropriumRelatedByFourthPropriumId($proprium, $comparison = null)
    {
        if ($proprium instanceof \app\model\Proprium) {
            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_FOURTH_PROPRIUM_ID, $proprium->getId(), $comparison);
        } elseif ($proprium instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_FOURTH_PROPRIUM_ID, $proprium->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPropriumRelatedByFourthPropriumId() only accepts arguments of type \app\model\Proprium or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PropriumRelatedByFourthPropriumId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function joinPropriumRelatedByFourthPropriumId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PropriumRelatedByFourthPropriumId');

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
            $this->addJoinObject($join, 'PropriumRelatedByFourthPropriumId');
        }

        return $this;
    }

    /**
     * Use the PropriumRelatedByFourthPropriumId relation Proprium object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PropriumQuery A secondary query class using the current class as primary query
     */
    public function usePropriumRelatedByFourthPropriumIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPropriumRelatedByFourthPropriumId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PropriumRelatedByFourthPropriumId', '\app\model\PropriumQuery');
    }

    /**
     * Filter the query by a related \app\model\Proprium object
     *
     * @param \app\model\Proprium|ObjectCollection $proprium The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function filterByPropriumRelatedByFifthPropriumId($proprium, $comparison = null)
    {
        if ($proprium instanceof \app\model\Proprium) {
            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_FIFTH_PROPRIUM_ID, $proprium->getId(), $comparison);
        } elseif ($proprium instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerDeckTableMap::COL_FIFTH_PROPRIUM_ID, $proprium->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPropriumRelatedByFifthPropriumId() only accepts arguments of type \app\model\Proprium or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PropriumRelatedByFifthPropriumId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerDeckQuery The current query, for fluid interface
     */
    public function joinPropriumRelatedByFifthPropriumId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PropriumRelatedByFifthPropriumId');

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
            $this->addJoinObject($join, 'PropriumRelatedByFifthPropriumId');
        }

        return $this;
    }

    /**
     * Use the PropriumRelatedByFifthPropriumId relation Proprium object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \app\model\PropriumQuery A secondary query class using the current class as primary query
     */
    public function usePropriumRelatedByFifthPropriumIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPropriumRelatedByFifthPropriumId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PropriumRelatedByFifthPropriumId', '\app\model\PropriumQuery');
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
