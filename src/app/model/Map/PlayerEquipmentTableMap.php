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
use app\model\PlayerEquipment;
use app\model\PlayerEquipmentQuery;


/**
 * This class defines the structure of the 'player_equipment' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class PlayerEquipmentTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'app.model.Map.PlayerEquipmentTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'player_equipment';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\app\\model\\PlayerEquipment';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'app.model.PlayerEquipment';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 11;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 11;

    /**
     * the column name for the id field
     */
    const COL_ID = 'player_equipment.id';

    /**
     * the column name for the player_id field
     */
    const COL_PLAYER_ID = 'player_equipment.player_id';

    /**
     * the column name for the weapon1_player_item_id field
     */
    const COL_WEAPON1_PLAYER_ITEM_ID = 'player_equipment.weapon1_player_item_id';

    /**
     * the column name for the weapon2_player_item_id field
     */
    const COL_WEAPON2_PLAYER_ITEM_ID = 'player_equipment.weapon2_player_item_id';

    /**
     * the column name for the head_player_item_id field
     */
    const COL_HEAD_PLAYER_ITEM_ID = 'player_equipment.head_player_item_id';

    /**
     * the column name for the left_arm_player_item_id field
     */
    const COL_LEFT_ARM_PLAYER_ITEM_ID = 'player_equipment.left_arm_player_item_id';

    /**
     * the column name for the right_arm_player_item_id field
     */
    const COL_RIGHT_ARM_PLAYER_ITEM_ID = 'player_equipment.right_arm_player_item_id';

    /**
     * the column name for the left_leg_player_item_id field
     */
    const COL_LEFT_LEG_PLAYER_ITEM_ID = 'player_equipment.left_leg_player_item_id';

    /**
     * the column name for the right_leg_player_item_id field
     */
    const COL_RIGHT_LEG_PLAYER_ITEM_ID = 'player_equipment.right_leg_player_item_id';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'player_equipment.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'player_equipment.updated_at';

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
        self::TYPE_PHPNAME       => array('Id', 'PlayerId', 'Weapon1PlayerItemId', 'Weapon2PlayerItemId', 'HeadPlayerItemId', 'LeftArmPlayerItemId', 'RightArmPlayerItemId', 'LeftLegPlayerItemId', 'RightLegPlayerItemId', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'playerId', 'weapon1PlayerItemId', 'weapon2PlayerItemId', 'headPlayerItemId', 'leftArmPlayerItemId', 'rightArmPlayerItemId', 'leftLegPlayerItemId', 'rightLegPlayerItemId', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(PlayerEquipmentTableMap::COL_ID, PlayerEquipmentTableMap::COL_PLAYER_ID, PlayerEquipmentTableMap::COL_WEAPON1_PLAYER_ITEM_ID, PlayerEquipmentTableMap::COL_WEAPON2_PLAYER_ITEM_ID, PlayerEquipmentTableMap::COL_HEAD_PLAYER_ITEM_ID, PlayerEquipmentTableMap::COL_LEFT_ARM_PLAYER_ITEM_ID, PlayerEquipmentTableMap::COL_RIGHT_ARM_PLAYER_ITEM_ID, PlayerEquipmentTableMap::COL_LEFT_LEG_PLAYER_ITEM_ID, PlayerEquipmentTableMap::COL_RIGHT_LEG_PLAYER_ITEM_ID, PlayerEquipmentTableMap::COL_CREATED_AT, PlayerEquipmentTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'player_id', 'weapon1_player_item_id', 'weapon2_player_item_id', 'head_player_item_id', 'left_arm_player_item_id', 'right_arm_player_item_id', 'left_leg_player_item_id', 'right_leg_player_item_id', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'PlayerId' => 1, 'Weapon1PlayerItemId' => 2, 'Weapon2PlayerItemId' => 3, 'HeadPlayerItemId' => 4, 'LeftArmPlayerItemId' => 5, 'RightArmPlayerItemId' => 6, 'LeftLegPlayerItemId' => 7, 'RightLegPlayerItemId' => 8, 'CreatedAt' => 9, 'UpdatedAt' => 10, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'playerId' => 1, 'weapon1PlayerItemId' => 2, 'weapon2PlayerItemId' => 3, 'headPlayerItemId' => 4, 'leftArmPlayerItemId' => 5, 'rightArmPlayerItemId' => 6, 'leftLegPlayerItemId' => 7, 'rightLegPlayerItemId' => 8, 'createdAt' => 9, 'updatedAt' => 10, ),
        self::TYPE_COLNAME       => array(PlayerEquipmentTableMap::COL_ID => 0, PlayerEquipmentTableMap::COL_PLAYER_ID => 1, PlayerEquipmentTableMap::COL_WEAPON1_PLAYER_ITEM_ID => 2, PlayerEquipmentTableMap::COL_WEAPON2_PLAYER_ITEM_ID => 3, PlayerEquipmentTableMap::COL_HEAD_PLAYER_ITEM_ID => 4, PlayerEquipmentTableMap::COL_LEFT_ARM_PLAYER_ITEM_ID => 5, PlayerEquipmentTableMap::COL_RIGHT_ARM_PLAYER_ITEM_ID => 6, PlayerEquipmentTableMap::COL_LEFT_LEG_PLAYER_ITEM_ID => 7, PlayerEquipmentTableMap::COL_RIGHT_LEG_PLAYER_ITEM_ID => 8, PlayerEquipmentTableMap::COL_CREATED_AT => 9, PlayerEquipmentTableMap::COL_UPDATED_AT => 10, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'player_id' => 1, 'weapon1_player_item_id' => 2, 'weapon2_player_item_id' => 3, 'head_player_item_id' => 4, 'left_arm_player_item_id' => 5, 'right_arm_player_item_id' => 6, 'left_leg_player_item_id' => 7, 'right_leg_player_item_id' => 8, 'created_at' => 9, 'updated_at' => 10, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
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
        $this->setName('player_equipment');
        $this->setPhpName('PlayerEquipment');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\app\\model\\PlayerEquipment');
        $this->setPackage('app.model');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('player_equipment_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('player_id', 'PlayerId', 'INTEGER', 'player', 'id', true, null, null);
        $this->addForeignKey('weapon1_player_item_id', 'Weapon1PlayerItemId', 'INTEGER', 'player_item', 'id', true, null, null);
        $this->addForeignKey('weapon2_player_item_id', 'Weapon2PlayerItemId', 'INTEGER', 'player_item', 'id', true, null, null);
        $this->addForeignKey('head_player_item_id', 'HeadPlayerItemId', 'INTEGER', 'player_item', 'id', true, null, null);
        $this->addForeignKey('left_arm_player_item_id', 'LeftArmPlayerItemId', 'INTEGER', 'player_item', 'id', true, null, null);
        $this->addForeignKey('right_arm_player_item_id', 'RightArmPlayerItemId', 'INTEGER', 'player_item', 'id', true, null, null);
        $this->addForeignKey('left_leg_player_item_id', 'LeftLegPlayerItemId', 'INTEGER', 'player_item', 'id', true, null, null);
        $this->addForeignKey('right_leg_player_item_id', 'RightLegPlayerItemId', 'INTEGER', 'player_item', 'id', true, null, null);
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
        $this->addRelation('PlayerItemRelatedByWeapon1PlayerItemId', '\\app\\model\\PlayerItem', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':weapon1_player_item_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('PlayerItemRelatedByWeapon2PlayerItemId', '\\app\\model\\PlayerItem', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':weapon2_player_item_id',
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
        return $withPrefix ? PlayerEquipmentTableMap::CLASS_DEFAULT : PlayerEquipmentTableMap::OM_CLASS;
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
     * @return array           (PlayerEquipment object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = PlayerEquipmentTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PlayerEquipmentTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PlayerEquipmentTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PlayerEquipmentTableMap::OM_CLASS;
            /** @var PlayerEquipment $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PlayerEquipmentTableMap::addInstanceToPool($obj, $key);
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
            $key = PlayerEquipmentTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PlayerEquipmentTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var PlayerEquipment $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PlayerEquipmentTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(PlayerEquipmentTableMap::COL_ID);
            $criteria->addSelectColumn(PlayerEquipmentTableMap::COL_PLAYER_ID);
            $criteria->addSelectColumn(PlayerEquipmentTableMap::COL_WEAPON1_PLAYER_ITEM_ID);
            $criteria->addSelectColumn(PlayerEquipmentTableMap::COL_WEAPON2_PLAYER_ITEM_ID);
            $criteria->addSelectColumn(PlayerEquipmentTableMap::COL_HEAD_PLAYER_ITEM_ID);
            $criteria->addSelectColumn(PlayerEquipmentTableMap::COL_LEFT_ARM_PLAYER_ITEM_ID);
            $criteria->addSelectColumn(PlayerEquipmentTableMap::COL_RIGHT_ARM_PLAYER_ITEM_ID);
            $criteria->addSelectColumn(PlayerEquipmentTableMap::COL_LEFT_LEG_PLAYER_ITEM_ID);
            $criteria->addSelectColumn(PlayerEquipmentTableMap::COL_RIGHT_LEG_PLAYER_ITEM_ID);
            $criteria->addSelectColumn(PlayerEquipmentTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(PlayerEquipmentTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.player_id');
            $criteria->addSelectColumn($alias . '.weapon1_player_item_id');
            $criteria->addSelectColumn($alias . '.weapon2_player_item_id');
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
        return Propel::getServiceContainer()->getDatabaseMap(PlayerEquipmentTableMap::DATABASE_NAME)->getTable(PlayerEquipmentTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(PlayerEquipmentTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(PlayerEquipmentTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new PlayerEquipmentTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a PlayerEquipment or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or PlayerEquipment object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerEquipmentTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \app\model\PlayerEquipment) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PlayerEquipmentTableMap::DATABASE_NAME);
            $criteria->add(PlayerEquipmentTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = PlayerEquipmentQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            PlayerEquipmentTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                PlayerEquipmentTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the player_equipment table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return PlayerEquipmentQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a PlayerEquipment or Criteria object.
     *
     * @param mixed               $criteria Criteria or PlayerEquipment object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerEquipmentTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from PlayerEquipment object
        }

        if ($criteria->containsKey(PlayerEquipmentTableMap::COL_ID) && $criteria->keyContainsValue(PlayerEquipmentTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PlayerEquipmentTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = PlayerEquipmentQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // PlayerEquipmentTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
PlayerEquipmentTableMap::buildTableMap();
