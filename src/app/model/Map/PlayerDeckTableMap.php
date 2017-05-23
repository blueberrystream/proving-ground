<?php

namespace app\model\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use app\model\PlayerDeck;
use app\model\PlayerDeckQuery;


/**
 * This class defines the structure of the 'player_deck' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class PlayerDeckTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'app.model.Map.PlayerDeckTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'player_deck';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\app\\model\\PlayerDeck';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'app.model.PlayerDeck';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the id field
     */
    const COL_ID = 'player_deck.id';

    /**
     * the column name for the player_id field
     */
    const COL_PLAYER_ID = 'player_deck.player_id';

    /**
     * the column name for the head_player_item_id field
     */
    const COL_HEAD_PLAYER_ITEM_ID = 'player_deck.head_player_item_id';

    /**
     * the column name for the left_arm_player_item_id field
     */
    const COL_LEFT_ARM_PLAYER_ITEM_ID = 'player_deck.left_arm_player_item_id';

    /**
     * the column name for the right_arm_player_item_id field
     */
    const COL_RIGHT_ARM_PLAYER_ITEM_ID = 'player_deck.right_arm_player_item_id';

    /**
     * the column name for the left_leg_player_item_id field
     */
    const COL_LEFT_LEG_PLAYER_ITEM_ID = 'player_deck.left_leg_player_item_id';

    /**
     * the column name for the right_leg_player_item_id field
     */
    const COL_RIGHT_LEG_PLAYER_ITEM_ID = 'player_deck.right_leg_player_item_id';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'player_deck.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'player_deck.updated_at';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'PlayerId', 'HeadPlayerItemId', 'LeftArmPlayerItemId', 'RightArmPlayerItemId', 'LeftLegPlayerItemId', 'RightLegPlayerItemId', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'playerId', 'headPlayerItemId', 'leftArmPlayerItemId', 'rightArmPlayerItemId', 'leftLegPlayerItemId', 'rightLegPlayerItemId', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(PlayerDeckTableMap::COL_ID, PlayerDeckTableMap::COL_PLAYER_ID, PlayerDeckTableMap::COL_HEAD_PLAYER_ITEM_ID, PlayerDeckTableMap::COL_LEFT_ARM_PLAYER_ITEM_ID, PlayerDeckTableMap::COL_RIGHT_ARM_PLAYER_ITEM_ID, PlayerDeckTableMap::COL_LEFT_LEG_PLAYER_ITEM_ID, PlayerDeckTableMap::COL_RIGHT_LEG_PLAYER_ITEM_ID, PlayerDeckTableMap::COL_CREATED_AT, PlayerDeckTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'player_id', 'head_player_item_id', 'left_arm_player_item_id', 'right_arm_player_item_id', 'left_leg_player_item_id', 'right_leg_player_item_id', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'PlayerId' => 1, 'HeadPlayerItemId' => 2, 'LeftArmPlayerItemId' => 3, 'RightArmPlayerItemId' => 4, 'LeftLegPlayerItemId' => 5, 'RightLegPlayerItemId' => 6, 'CreatedAt' => 7, 'UpdatedAt' => 8, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'playerId' => 1, 'headPlayerItemId' => 2, 'leftArmPlayerItemId' => 3, 'rightArmPlayerItemId' => 4, 'leftLegPlayerItemId' => 5, 'rightLegPlayerItemId' => 6, 'createdAt' => 7, 'updatedAt' => 8, ),
        self::TYPE_COLNAME       => array(PlayerDeckTableMap::COL_ID => 0, PlayerDeckTableMap::COL_PLAYER_ID => 1, PlayerDeckTableMap::COL_HEAD_PLAYER_ITEM_ID => 2, PlayerDeckTableMap::COL_LEFT_ARM_PLAYER_ITEM_ID => 3, PlayerDeckTableMap::COL_RIGHT_ARM_PLAYER_ITEM_ID => 4, PlayerDeckTableMap::COL_LEFT_LEG_PLAYER_ITEM_ID => 5, PlayerDeckTableMap::COL_RIGHT_LEG_PLAYER_ITEM_ID => 6, PlayerDeckTableMap::COL_CREATED_AT => 7, PlayerDeckTableMap::COL_UPDATED_AT => 8, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'player_id' => 1, 'head_player_item_id' => 2, 'left_arm_player_item_id' => 3, 'right_arm_player_item_id' => 4, 'left_leg_player_item_id' => 5, 'right_leg_player_item_id' => 6, 'created_at' => 7, 'updated_at' => 8, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('player_deck');
        $this->setPhpName('PlayerDeck');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\app\\model\\PlayerDeck');
        $this->setPackage('app.model');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('player_deck_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('player_id', 'PlayerId', 'INTEGER', 'player', 'id', true, null, null);
        $this->addForeignKey('head_player_item_id', 'HeadPlayerItemId', 'INTEGER', 'player_item', 'id', false, null, null);
        $this->addForeignKey('left_arm_player_item_id', 'LeftArmPlayerItemId', 'INTEGER', 'player_item', 'id', false, null, null);
        $this->addForeignKey('right_arm_player_item_id', 'RightArmPlayerItemId', 'INTEGER', 'player_item', 'id', false, null, null);
        $this->addForeignKey('left_leg_player_item_id', 'LeftLegPlayerItemId', 'INTEGER', 'player_item', 'id', false, null, null);
        $this->addForeignKey('right_leg_player_item_id', 'RightLegPlayerItemId', 'INTEGER', 'player_item', 'id', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', true, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Player', '\\app\\model\\Player', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':player_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('PlayerItemRelatedByHeadPlayerItemId', '\\app\\model\\PlayerItem', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':head_player_item_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('PlayerItemRelatedByLeftArmPlayerItemId', '\\app\\model\\PlayerItem', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':left_arm_player_item_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('PlayerItemRelatedByRightArmPlayerItemId', '\\app\\model\\PlayerItem', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':right_arm_player_item_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('PlayerItemRelatedByLeftLegPlayerItemId', '\\app\\model\\PlayerItem', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':left_leg_player_item_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('PlayerItemRelatedByRightLegPlayerItemId', '\\app\\model\\PlayerItem', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':right_leg_player_item_id',
    1 => ':id',
  ),
), null, null, null, false);
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? PlayerDeckTableMap::CLASS_DEFAULT : PlayerDeckTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (PlayerDeck object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = PlayerDeckTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PlayerDeckTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PlayerDeckTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PlayerDeckTableMap::OM_CLASS;
            /** @var PlayerDeck $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PlayerDeckTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = PlayerDeckTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PlayerDeckTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var PlayerDeck $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PlayerDeckTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(PlayerDeckTableMap::COL_ID);
            $criteria->addSelectColumn(PlayerDeckTableMap::COL_PLAYER_ID);
            $criteria->addSelectColumn(PlayerDeckTableMap::COL_HEAD_PLAYER_ITEM_ID);
            $criteria->addSelectColumn(PlayerDeckTableMap::COL_LEFT_ARM_PLAYER_ITEM_ID);
            $criteria->addSelectColumn(PlayerDeckTableMap::COL_RIGHT_ARM_PLAYER_ITEM_ID);
            $criteria->addSelectColumn(PlayerDeckTableMap::COL_LEFT_LEG_PLAYER_ITEM_ID);
            $criteria->addSelectColumn(PlayerDeckTableMap::COL_RIGHT_LEG_PLAYER_ITEM_ID);
            $criteria->addSelectColumn(PlayerDeckTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(PlayerDeckTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.player_id');
            $criteria->addSelectColumn($alias . '.head_player_item_id');
            $criteria->addSelectColumn($alias . '.left_arm_player_item_id');
            $criteria->addSelectColumn($alias . '.right_arm_player_item_id');
            $criteria->addSelectColumn($alias . '.left_leg_player_item_id');
            $criteria->addSelectColumn($alias . '.right_leg_player_item_id');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(PlayerDeckTableMap::DATABASE_NAME)->getTable(PlayerDeckTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(PlayerDeckTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(PlayerDeckTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new PlayerDeckTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a PlayerDeck or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or PlayerDeck object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerDeckTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \app\model\PlayerDeck) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PlayerDeckTableMap::DATABASE_NAME);
            $criteria->add(PlayerDeckTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = PlayerDeckQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            PlayerDeckTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                PlayerDeckTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the player_deck table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return PlayerDeckQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a PlayerDeck or Criteria object.
     *
     * @param mixed               $criteria Criteria or PlayerDeck object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerDeckTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from PlayerDeck object
        }

        if ($criteria->containsKey(PlayerDeckTableMap::COL_ID) && $criteria->keyContainsValue(PlayerDeckTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PlayerDeckTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = PlayerDeckQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // PlayerDeckTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
PlayerDeckTableMap::buildTableMap();
