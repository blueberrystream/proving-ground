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
use app\model\Item;
use app\model\ItemQuery;


/**
 * This class defines the structure of the 'item' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class ItemTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'app.model.Map.ItemTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'item';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\app\\model\\Item';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'app.model.Item';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the id field
     */
    const COL_ID = 'item.id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'item.name';

    /**
     * the column name for the proprium_id field
     */
    const COL_PROPRIUM_ID = 'item.proprium_id';

    /**
     * the column name for the part_id field
     */
    const COL_PART_ID = 'item.part_id';

    /**
     * the column name for the hit_point field
     */
    const COL_HIT_POINT = 'item.hit_point';

    /**
     * the column name for the attack_point field
     */
    const COL_ATTACK_POINT = 'item.attack_point';

    /**
     * the column name for the defense_point field
     */
    const COL_DEFENSE_POINT = 'item.defense_point';

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
        self::TYPE_PHPNAME       => array('Id', 'Name', 'PropriumId', 'PartId', 'HitPoint', 'AttackPoint', 'DefensePoint', ),
        self::TYPE_CAMELNAME     => array('id', 'name', 'propriumId', 'partId', 'hitPoint', 'attackPoint', 'defensePoint', ),
        self::TYPE_COLNAME       => array(ItemTableMap::COL_ID, ItemTableMap::COL_NAME, ItemTableMap::COL_PROPRIUM_ID, ItemTableMap::COL_PART_ID, ItemTableMap::COL_HIT_POINT, ItemTableMap::COL_ATTACK_POINT, ItemTableMap::COL_DEFENSE_POINT, ),
        self::TYPE_FIELDNAME     => array('id', 'name', 'proprium_id', 'part_id', 'hit_point', 'attack_point', 'defense_point', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'PropriumId' => 2, 'PartId' => 3, 'HitPoint' => 4, 'AttackPoint' => 5, 'DefensePoint' => 6, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'name' => 1, 'propriumId' => 2, 'partId' => 3, 'hitPoint' => 4, 'attackPoint' => 5, 'defensePoint' => 6, ),
        self::TYPE_COLNAME       => array(ItemTableMap::COL_ID => 0, ItemTableMap::COL_NAME => 1, ItemTableMap::COL_PROPRIUM_ID => 2, ItemTableMap::COL_PART_ID => 3, ItemTableMap::COL_HIT_POINT => 4, ItemTableMap::COL_ATTACK_POINT => 5, ItemTableMap::COL_DEFENSE_POINT => 6, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'name' => 1, 'proprium_id' => 2, 'part_id' => 3, 'hit_point' => 4, 'attack_point' => 5, 'defense_point' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
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
        $this->setName('item');
        $this->setPhpName('Item');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\app\\model\\Item');
        $this->setPackage('app.model');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('item_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 255, null);
        $this->addForeignKey('proprium_id', 'PropriumId', 'INTEGER', 'proprium', 'id', true, null, null);
        $this->addForeignKey('part_id', 'PartId', 'INTEGER', 'part', 'id', true, null, null);
        $this->addColumn('hit_point', 'HitPoint', 'INTEGER', true, null, 0);
        $this->addColumn('attack_point', 'AttackPoint', 'INTEGER', true, null, 0);
        $this->addColumn('defense_point', 'DefensePoint', 'INTEGER', true, null, 0);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Part', '\\app\\model\\Part', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':part_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Proprium', '\\app\\model\\Proprium', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':proprium_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('PlayerItem', '\\app\\model\\PlayerItem', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':item_id',
    1 => ':id',
  ),
), null, null, 'PlayerItems', false);
    } // buildRelations()

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
        return $withPrefix ? ItemTableMap::CLASS_DEFAULT : ItemTableMap::OM_CLASS;
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
     * @return array           (Item object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ItemTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ItemTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ItemTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ItemTableMap::OM_CLASS;
            /** @var Item $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ItemTableMap::addInstanceToPool($obj, $key);
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
            $key = ItemTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ItemTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Item $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ItemTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ItemTableMap::COL_ID);
            $criteria->addSelectColumn(ItemTableMap::COL_NAME);
            $criteria->addSelectColumn(ItemTableMap::COL_PROPRIUM_ID);
            $criteria->addSelectColumn(ItemTableMap::COL_PART_ID);
            $criteria->addSelectColumn(ItemTableMap::COL_HIT_POINT);
            $criteria->addSelectColumn(ItemTableMap::COL_ATTACK_POINT);
            $criteria->addSelectColumn(ItemTableMap::COL_DEFENSE_POINT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.proprium_id');
            $criteria->addSelectColumn($alias . '.part_id');
            $criteria->addSelectColumn($alias . '.hit_point');
            $criteria->addSelectColumn($alias . '.attack_point');
            $criteria->addSelectColumn($alias . '.defense_point');
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
        return Propel::getServiceContainer()->getDatabaseMap(ItemTableMap::DATABASE_NAME)->getTable(ItemTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ItemTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(ItemTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new ItemTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Item or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Item object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ItemTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \app\model\Item) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ItemTableMap::DATABASE_NAME);
            $criteria->add(ItemTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = ItemQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ItemTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ItemTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the item table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ItemQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Item or Criteria object.
     *
     * @param mixed               $criteria Criteria or Item object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ItemTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Item object
        }

        if ($criteria->containsKey(ItemTableMap::COL_ID) && $criteria->keyContainsValue(ItemTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ItemTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = ItemQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // ItemTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ItemTableMap::buildTableMap();
