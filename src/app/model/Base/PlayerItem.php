<?php

namespace app\model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;
use app\model\Item as ChildItem;
use app\model\ItemQuery as ChildItemQuery;
use app\model\Player as ChildPlayer;
use app\model\PlayerEquipment as ChildPlayerEquipment;
use app\model\PlayerEquipmentQuery as ChildPlayerEquipmentQuery;
use app\model\PlayerItem as ChildPlayerItem;
use app\model\PlayerItemQuery as ChildPlayerItemQuery;
use app\model\PlayerQuery as ChildPlayerQuery;
use app\model\Map\PlayerEquipmentTableMap;
use app\model\Map\PlayerItemTableMap;

/**
 * Base class that represents a row from the 'player_item' table.
 *
 *
 *
 * @package    propel.generator.app.model.Base
 */
abstract class PlayerItem implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\app\\model\\Map\\PlayerItemTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the player_id field.
     *
     * @var        int
     */
    protected $player_id;

    /**
     * The value for the item_id field.
     *
     * @var        int
     */
    protected $item_id;

    /**
     * The value for the created_at field.
     *
     * @var        DateTime
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     *
     * @var        DateTime
     */
    protected $updated_at;

    /**
     * @var        ChildPlayer
     */
    protected $aPlayer;

    /**
     * @var        ChildItem
     */
    protected $aItem;

    /**
     * @var        ObjectCollection|ChildPlayerEquipment[] Collection to store aggregation of ChildPlayerEquipment objects.
     */
    protected $collPlayerEquipmentsRelatedByWeapon1PlayerItemId;
    protected $collPlayerEquipmentsRelatedByWeapon1PlayerItemIdPartial;

    /**
     * @var        ObjectCollection|ChildPlayerEquipment[] Collection to store aggregation of ChildPlayerEquipment objects.
     */
    protected $collPlayerEquipmentsRelatedByWeapon2PlayerItemId;
    protected $collPlayerEquipmentsRelatedByWeapon2PlayerItemIdPartial;

    /**
     * @var        ObjectCollection|ChildPlayerEquipment[] Collection to store aggregation of ChildPlayerEquipment objects.
     */
    protected $collPlayerEquipmentsRelatedByHeadPlayerItemId;
    protected $collPlayerEquipmentsRelatedByHeadPlayerItemIdPartial;

    /**
     * @var        ObjectCollection|ChildPlayerEquipment[] Collection to store aggregation of ChildPlayerEquipment objects.
     */
    protected $collPlayerEquipmentsRelatedByLeftArmPlayerItemId;
    protected $collPlayerEquipmentsRelatedByLeftArmPlayerItemIdPartial;

    /**
     * @var        ObjectCollection|ChildPlayerEquipment[] Collection to store aggregation of ChildPlayerEquipment objects.
     */
    protected $collPlayerEquipmentsRelatedByRightArmPlayerItemId;
    protected $collPlayerEquipmentsRelatedByRightArmPlayerItemIdPartial;

    /**
     * @var        ObjectCollection|ChildPlayerEquipment[] Collection to store aggregation of ChildPlayerEquipment objects.
     */
    protected $collPlayerEquipmentsRelatedByLeftLegPlayerItemId;
    protected $collPlayerEquipmentsRelatedByLeftLegPlayerItemIdPartial;

    /**
     * @var        ObjectCollection|ChildPlayerEquipment[] Collection to store aggregation of ChildPlayerEquipment objects.
     */
    protected $collPlayerEquipmentsRelatedByRightLegPlayerItemId;
    protected $collPlayerEquipmentsRelatedByRightLegPlayerItemIdPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerEquipment[]
     */
    protected $playerEquipmentsRelatedByWeapon1PlayerItemIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerEquipment[]
     */
    protected $playerEquipmentsRelatedByWeapon2PlayerItemIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerEquipment[]
     */
    protected $playerEquipmentsRelatedByHeadPlayerItemIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerEquipment[]
     */
    protected $playerEquipmentsRelatedByLeftArmPlayerItemIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerEquipment[]
     */
    protected $playerEquipmentsRelatedByRightArmPlayerItemIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerEquipment[]
     */
    protected $playerEquipmentsRelatedByLeftLegPlayerItemIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerEquipment[]
     */
    protected $playerEquipmentsRelatedByRightLegPlayerItemIdScheduledForDeletion = null;

    /**
     * Initializes internal state of app\model\Base\PlayerItem object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>PlayerItem</code> instance.  If
     * <code>obj</code> is an instance of <code>PlayerItem</code>, delegates to
     * <code>equals(PlayerItem)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|PlayerItem The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [player_id] column value.
     *
     * @return int
     */
    public function getPlayerId()
    {
        return $this->player_id;
    }

    /**
     * Get the [item_id] column value.
     *
     * @return int
     */
    public function getItemId()
    {
        return $this->item_id;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTimeInterface ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTimeInterface ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[PlayerItemTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [player_id] column.
     *
     * @param int $v new value
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function setPlayerId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->player_id !== $v) {
            $this->player_id = $v;
            $this->modifiedColumns[PlayerItemTableMap::COL_PLAYER_ID] = true;
        }

        if ($this->aPlayer !== null && $this->aPlayer->getId() !== $v) {
            $this->aPlayer = null;
        }

        return $this;
    } // setPlayerId()

    /**
     * Set the value of [item_id] column.
     *
     * @param int $v new value
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function setItemId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->item_id !== $v) {
            $this->item_id = $v;
            $this->modifiedColumns[PlayerItemTableMap::COL_ITEM_ID] = true;
        }

        if ($this->aItem !== null && $this->aItem->getId() !== $v) {
            $this->aItem = null;
        }

        return $this;
    } // setItemId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PlayerItemTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PlayerItemTableMap::COL_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PlayerItemTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PlayerItemTableMap::translateFieldName('PlayerId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->player_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : PlayerItemTableMap::translateFieldName('ItemId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->item_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : PlayerItemTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : PlayerItemTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = PlayerItemTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\app\\model\\PlayerItem'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aPlayer !== null && $this->player_id !== $this->aPlayer->getId()) {
            $this->aPlayer = null;
        }
        if ($this->aItem !== null && $this->item_id !== $this->aItem->getId()) {
            $this->aItem = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlayerItemTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildPlayerItemQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPlayer = null;
            $this->aItem = null;
            $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId = null;

            $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId = null;

            $this->collPlayerEquipmentsRelatedByHeadPlayerItemId = null;

            $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId = null;

            $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId = null;

            $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId = null;

            $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see PlayerItem::setDeleted()
     * @see PlayerItem::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerItemTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildPlayerItemQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerItemTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(PlayerItemTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
                if (!$this->isColumnModified(PlayerItemTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PlayerItemTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                PlayerItemTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aPlayer !== null) {
                if ($this->aPlayer->isModified() || $this->aPlayer->isNew()) {
                    $affectedRows += $this->aPlayer->save($con);
                }
                $this->setPlayer($this->aPlayer);
            }

            if ($this->aItem !== null) {
                if ($this->aItem->isModified() || $this->aItem->isNew()) {
                    $affectedRows += $this->aItem->save($con);
                }
                $this->setItem($this->aItem);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->playerEquipmentsRelatedByWeapon1PlayerItemIdScheduledForDeletion !== null) {
                if (!$this->playerEquipmentsRelatedByWeapon1PlayerItemIdScheduledForDeletion->isEmpty()) {
                    \app\model\PlayerEquipmentQuery::create()
                        ->filterByPrimaryKeys($this->playerEquipmentsRelatedByWeapon1PlayerItemIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerEquipmentsRelatedByWeapon1PlayerItemIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId !== null) {
                foreach ($this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerEquipmentsRelatedByWeapon2PlayerItemIdScheduledForDeletion !== null) {
                if (!$this->playerEquipmentsRelatedByWeapon2PlayerItemIdScheduledForDeletion->isEmpty()) {
                    \app\model\PlayerEquipmentQuery::create()
                        ->filterByPrimaryKeys($this->playerEquipmentsRelatedByWeapon2PlayerItemIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerEquipmentsRelatedByWeapon2PlayerItemIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId !== null) {
                foreach ($this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerEquipmentsRelatedByHeadPlayerItemIdScheduledForDeletion !== null) {
                if (!$this->playerEquipmentsRelatedByHeadPlayerItemIdScheduledForDeletion->isEmpty()) {
                    \app\model\PlayerEquipmentQuery::create()
                        ->filterByPrimaryKeys($this->playerEquipmentsRelatedByHeadPlayerItemIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerEquipmentsRelatedByHeadPlayerItemIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerEquipmentsRelatedByHeadPlayerItemId !== null) {
                foreach ($this->collPlayerEquipmentsRelatedByHeadPlayerItemId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerEquipmentsRelatedByLeftArmPlayerItemIdScheduledForDeletion !== null) {
                if (!$this->playerEquipmentsRelatedByLeftArmPlayerItemIdScheduledForDeletion->isEmpty()) {
                    \app\model\PlayerEquipmentQuery::create()
                        ->filterByPrimaryKeys($this->playerEquipmentsRelatedByLeftArmPlayerItemIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerEquipmentsRelatedByLeftArmPlayerItemIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId !== null) {
                foreach ($this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerEquipmentsRelatedByRightArmPlayerItemIdScheduledForDeletion !== null) {
                if (!$this->playerEquipmentsRelatedByRightArmPlayerItemIdScheduledForDeletion->isEmpty()) {
                    \app\model\PlayerEquipmentQuery::create()
                        ->filterByPrimaryKeys($this->playerEquipmentsRelatedByRightArmPlayerItemIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerEquipmentsRelatedByRightArmPlayerItemIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerEquipmentsRelatedByRightArmPlayerItemId !== null) {
                foreach ($this->collPlayerEquipmentsRelatedByRightArmPlayerItemId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerEquipmentsRelatedByLeftLegPlayerItemIdScheduledForDeletion !== null) {
                if (!$this->playerEquipmentsRelatedByLeftLegPlayerItemIdScheduledForDeletion->isEmpty()) {
                    \app\model\PlayerEquipmentQuery::create()
                        ->filterByPrimaryKeys($this->playerEquipmentsRelatedByLeftLegPlayerItemIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerEquipmentsRelatedByLeftLegPlayerItemIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId !== null) {
                foreach ($this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerEquipmentsRelatedByRightLegPlayerItemIdScheduledForDeletion !== null) {
                if (!$this->playerEquipmentsRelatedByRightLegPlayerItemIdScheduledForDeletion->isEmpty()) {
                    \app\model\PlayerEquipmentQuery::create()
                        ->filterByPrimaryKeys($this->playerEquipmentsRelatedByRightLegPlayerItemIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerEquipmentsRelatedByRightLegPlayerItemIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerEquipmentsRelatedByRightLegPlayerItemId !== null) {
                foreach ($this->collPlayerEquipmentsRelatedByRightLegPlayerItemId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[PlayerItemTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PlayerItemTableMap::COL_ID . ')');
        }
        if (null === $this->id) {
            try {
                $dataFetcher = $con->query("SELECT nextval('player_item_id_seq')");
                $this->id = (int) $dataFetcher->fetchColumn();
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', 0, $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PlayerItemTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(PlayerItemTableMap::COL_PLAYER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'player_id';
        }
        if ($this->isColumnModified(PlayerItemTableMap::COL_ITEM_ID)) {
            $modifiedColumns[':p' . $index++]  = 'item_id';
        }
        if ($this->isColumnModified(PlayerItemTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(PlayerItemTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO player_item (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'player_id':
                        $stmt->bindValue($identifier, $this->player_id, PDO::PARAM_INT);
                        break;
                    case 'item_id':
                        $stmt->bindValue($identifier, $this->item_id, PDO::PARAM_INT);
                        break;
                    case 'created_at':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'updated_at':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PlayerItemTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getPlayerId();
                break;
            case 2:
                return $this->getItemId();
                break;
            case 3:
                return $this->getCreatedAt();
                break;
            case 4:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['PlayerItem'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PlayerItem'][$this->hashCode()] = true;
        $keys = PlayerItemTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getPlayerId(),
            $keys[2] => $this->getItemId(),
            $keys[3] => $this->getCreatedAt(),
            $keys[4] => $this->getUpdatedAt(),
        );
        if ($result[$keys[3]] instanceof \DateTimeInterface) {
            $result[$keys[3]] = $result[$keys[3]]->format('c');
        }

        if ($result[$keys[4]] instanceof \DateTimeInterface) {
            $result[$keys[4]] = $result[$keys[4]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPlayer) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'player';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player';
                        break;
                    default:
                        $key = 'Player';
                }

                $result[$key] = $this->aPlayer->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aItem) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'item';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'item';
                        break;
                    default:
                        $key = 'Item';
                }

                $result[$key] = $this->aItem->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerEquipments';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_equipments';
                        break;
                    default:
                        $key = 'PlayerEquipments';
                }

                $result[$key] = $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerEquipments';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_equipments';
                        break;
                    default:
                        $key = 'PlayerEquipments';
                }

                $result[$key] = $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerEquipmentsRelatedByHeadPlayerItemId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerEquipments';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_equipments';
                        break;
                    default:
                        $key = 'PlayerEquipments';
                }

                $result[$key] = $this->collPlayerEquipmentsRelatedByHeadPlayerItemId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerEquipments';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_equipments';
                        break;
                    default:
                        $key = 'PlayerEquipments';
                }

                $result[$key] = $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerEquipments';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_equipments';
                        break;
                    default:
                        $key = 'PlayerEquipments';
                }

                $result[$key] = $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerEquipments';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_equipments';
                        break;
                    default:
                        $key = 'PlayerEquipments';
                }

                $result[$key] = $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerEquipments';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_equipments';
                        break;
                    default:
                        $key = 'PlayerEquipments';
                }

                $result[$key] = $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\app\model\PlayerItem
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PlayerItemTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\app\model\PlayerItem
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setPlayerId($value);
                break;
            case 2:
                $this->setItemId($value);
                break;
            case 3:
                $this->setCreatedAt($value);
                break;
            case 4:
                $this->setUpdatedAt($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = PlayerItemTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setPlayerId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setItemId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setCreatedAt($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setUpdatedAt($arr[$keys[4]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\app\model\PlayerItem The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PlayerItemTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PlayerItemTableMap::COL_ID)) {
            $criteria->add(PlayerItemTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(PlayerItemTableMap::COL_PLAYER_ID)) {
            $criteria->add(PlayerItemTableMap::COL_PLAYER_ID, $this->player_id);
        }
        if ($this->isColumnModified(PlayerItemTableMap::COL_ITEM_ID)) {
            $criteria->add(PlayerItemTableMap::COL_ITEM_ID, $this->item_id);
        }
        if ($this->isColumnModified(PlayerItemTableMap::COL_CREATED_AT)) {
            $criteria->add(PlayerItemTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(PlayerItemTableMap::COL_UPDATED_AT)) {
            $criteria->add(PlayerItemTableMap::COL_UPDATED_AT, $this->updated_at);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildPlayerItemQuery::create();
        $criteria->add(PlayerItemTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \app\model\PlayerItem (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPlayerId($this->getPlayerId());
        $copyObj->setItemId($this->getItemId());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getPlayerEquipmentsRelatedByWeapon1PlayerItemId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerEquipmentRelatedByWeapon1PlayerItemId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerEquipmentsRelatedByWeapon2PlayerItemId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerEquipmentRelatedByWeapon2PlayerItemId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerEquipmentsRelatedByHeadPlayerItemId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerEquipmentRelatedByHeadPlayerItemId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerEquipmentsRelatedByLeftArmPlayerItemId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerEquipmentRelatedByLeftArmPlayerItemId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerEquipmentsRelatedByRightArmPlayerItemId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerEquipmentRelatedByRightArmPlayerItemId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerEquipmentsRelatedByLeftLegPlayerItemId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerEquipmentRelatedByLeftLegPlayerItemId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerEquipmentsRelatedByRightLegPlayerItemId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerEquipmentRelatedByRightLegPlayerItemId($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \app\model\PlayerItem Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildPlayer object.
     *
     * @param  ChildPlayer $v
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPlayer(ChildPlayer $v = null)
    {
        if ($v === null) {
            $this->setPlayerId(NULL);
        } else {
            $this->setPlayerId($v->getId());
        }

        $this->aPlayer = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPlayer object, it will not be re-added.
        if ($v !== null) {
            $v->addPlayerItem($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPlayer object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildPlayer The associated ChildPlayer object.
     * @throws PropelException
     */
    public function getPlayer(ConnectionInterface $con = null)
    {
        if ($this->aPlayer === null && ($this->player_id !== null)) {
            $this->aPlayer = ChildPlayerQuery::create()->findPk($this->player_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPlayer->addPlayerItems($this);
             */
        }

        return $this->aPlayer;
    }

    /**
     * Declares an association between this object and a ChildItem object.
     *
     * @param  ChildItem $v
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     * @throws PropelException
     */
    public function setItem(ChildItem $v = null)
    {
        if ($v === null) {
            $this->setItemId(NULL);
        } else {
            $this->setItemId($v->getId());
        }

        $this->aItem = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildItem object, it will not be re-added.
        if ($v !== null) {
            $v->addPlayerItem($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildItem object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildItem The associated ChildItem object.
     * @throws PropelException
     */
    public function getItem(ConnectionInterface $con = null)
    {
        if ($this->aItem === null && ($this->item_id !== null)) {
            $this->aItem = ChildItemQuery::create()->findPk($this->item_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aItem->addPlayerItems($this);
             */
        }

        return $this->aItem;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('PlayerEquipmentRelatedByWeapon1PlayerItemId' == $relationName) {
            $this->initPlayerEquipmentsRelatedByWeapon1PlayerItemId();
            return;
        }
        if ('PlayerEquipmentRelatedByWeapon2PlayerItemId' == $relationName) {
            $this->initPlayerEquipmentsRelatedByWeapon2PlayerItemId();
            return;
        }
        if ('PlayerEquipmentRelatedByHeadPlayerItemId' == $relationName) {
            $this->initPlayerEquipmentsRelatedByHeadPlayerItemId();
            return;
        }
        if ('PlayerEquipmentRelatedByLeftArmPlayerItemId' == $relationName) {
            $this->initPlayerEquipmentsRelatedByLeftArmPlayerItemId();
            return;
        }
        if ('PlayerEquipmentRelatedByRightArmPlayerItemId' == $relationName) {
            $this->initPlayerEquipmentsRelatedByRightArmPlayerItemId();
            return;
        }
        if ('PlayerEquipmentRelatedByLeftLegPlayerItemId' == $relationName) {
            $this->initPlayerEquipmentsRelatedByLeftLegPlayerItemId();
            return;
        }
        if ('PlayerEquipmentRelatedByRightLegPlayerItemId' == $relationName) {
            $this->initPlayerEquipmentsRelatedByRightLegPlayerItemId();
            return;
        }
    }

    /**
     * Clears out the collPlayerEquipmentsRelatedByWeapon1PlayerItemId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerEquipmentsRelatedByWeapon1PlayerItemId()
     */
    public function clearPlayerEquipmentsRelatedByWeapon1PlayerItemId()
    {
        $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerEquipmentsRelatedByWeapon1PlayerItemId collection loaded partially.
     */
    public function resetPartialPlayerEquipmentsRelatedByWeapon1PlayerItemId($v = true)
    {
        $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemIdPartial = $v;
    }

    /**
     * Initializes the collPlayerEquipmentsRelatedByWeapon1PlayerItemId collection.
     *
     * By default this just sets the collPlayerEquipmentsRelatedByWeapon1PlayerItemId collection to an empty array (like clearcollPlayerEquipmentsRelatedByWeapon1PlayerItemId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerEquipmentsRelatedByWeapon1PlayerItemId($overrideExisting = true)
    {
        if (null !== $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerEquipmentTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId = new $collectionClassName;
        $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId->setModel('\app\model\PlayerEquipment');
    }

    /**
     * Gets an array of ChildPlayerEquipment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayerItem is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerEquipment[] List of ChildPlayerEquipment objects
     * @throws PropelException
     */
    public function getPlayerEquipmentsRelatedByWeapon1PlayerItemId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId) {
                // return empty collection
                $this->initPlayerEquipmentsRelatedByWeapon1PlayerItemId();
            } else {
                $collPlayerEquipmentsRelatedByWeapon1PlayerItemId = ChildPlayerEquipmentQuery::create(null, $criteria)
                    ->filterByPlayerItemRelatedByWeapon1PlayerItemId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemIdPartial && count($collPlayerEquipmentsRelatedByWeapon1PlayerItemId)) {
                        $this->initPlayerEquipmentsRelatedByWeapon1PlayerItemId(false);

                        foreach ($collPlayerEquipmentsRelatedByWeapon1PlayerItemId as $obj) {
                            if (false == $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId->contains($obj)) {
                                $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId->append($obj);
                            }
                        }

                        $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemIdPartial = true;
                    }

                    return $collPlayerEquipmentsRelatedByWeapon1PlayerItemId;
                }

                if ($partial && $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId) {
                    foreach ($this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerEquipmentsRelatedByWeapon1PlayerItemId[] = $obj;
                        }
                    }
                }

                $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId = $collPlayerEquipmentsRelatedByWeapon1PlayerItemId;
                $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemIdPartial = false;
            }
        }

        return $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId;
    }

    /**
     * Sets a collection of ChildPlayerEquipment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerEquipmentsRelatedByWeapon1PlayerItemId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function setPlayerEquipmentsRelatedByWeapon1PlayerItemId(Collection $playerEquipmentsRelatedByWeapon1PlayerItemId, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerEquipment[] $playerEquipmentsRelatedByWeapon1PlayerItemIdToDelete */
        $playerEquipmentsRelatedByWeapon1PlayerItemIdToDelete = $this->getPlayerEquipmentsRelatedByWeapon1PlayerItemId(new Criteria(), $con)->diff($playerEquipmentsRelatedByWeapon1PlayerItemId);


        $this->playerEquipmentsRelatedByWeapon1PlayerItemIdScheduledForDeletion = $playerEquipmentsRelatedByWeapon1PlayerItemIdToDelete;

        foreach ($playerEquipmentsRelatedByWeapon1PlayerItemIdToDelete as $playerEquipmentRelatedByWeapon1PlayerItemIdRemoved) {
            $playerEquipmentRelatedByWeapon1PlayerItemIdRemoved->setPlayerItemRelatedByWeapon1PlayerItemId(null);
        }

        $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId = null;
        foreach ($playerEquipmentsRelatedByWeapon1PlayerItemId as $playerEquipmentRelatedByWeapon1PlayerItemId) {
            $this->addPlayerEquipmentRelatedByWeapon1PlayerItemId($playerEquipmentRelatedByWeapon1PlayerItemId);
        }

        $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId = $playerEquipmentsRelatedByWeapon1PlayerItemId;
        $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PlayerEquipment objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PlayerEquipment objects.
     * @throws PropelException
     */
    public function countPlayerEquipmentsRelatedByWeapon1PlayerItemId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerEquipmentsRelatedByWeapon1PlayerItemId());
            }

            $query = ChildPlayerEquipmentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerItemRelatedByWeapon1PlayerItemId($this)
                ->count($con);
        }

        return count($this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId);
    }

    /**
     * Method called to associate a ChildPlayerEquipment object to this object
     * through the ChildPlayerEquipment foreign key attribute.
     *
     * @param  ChildPlayerEquipment $l ChildPlayerEquipment
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function addPlayerEquipmentRelatedByWeapon1PlayerItemId(ChildPlayerEquipment $l)
    {
        if ($this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId === null) {
            $this->initPlayerEquipmentsRelatedByWeapon1PlayerItemId();
            $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemIdPartial = true;
        }

        if (!$this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId->contains($l)) {
            $this->doAddPlayerEquipmentRelatedByWeapon1PlayerItemId($l);

            if ($this->playerEquipmentsRelatedByWeapon1PlayerItemIdScheduledForDeletion and $this->playerEquipmentsRelatedByWeapon1PlayerItemIdScheduledForDeletion->contains($l)) {
                $this->playerEquipmentsRelatedByWeapon1PlayerItemIdScheduledForDeletion->remove($this->playerEquipmentsRelatedByWeapon1PlayerItemIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerEquipment $playerEquipmentRelatedByWeapon1PlayerItemId The ChildPlayerEquipment object to add.
     */
    protected function doAddPlayerEquipmentRelatedByWeapon1PlayerItemId(ChildPlayerEquipment $playerEquipmentRelatedByWeapon1PlayerItemId)
    {
        $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId[]= $playerEquipmentRelatedByWeapon1PlayerItemId;
        $playerEquipmentRelatedByWeapon1PlayerItemId->setPlayerItemRelatedByWeapon1PlayerItemId($this);
    }

    /**
     * @param  ChildPlayerEquipment $playerEquipmentRelatedByWeapon1PlayerItemId The ChildPlayerEquipment object to remove.
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function removePlayerEquipmentRelatedByWeapon1PlayerItemId(ChildPlayerEquipment $playerEquipmentRelatedByWeapon1PlayerItemId)
    {
        if ($this->getPlayerEquipmentsRelatedByWeapon1PlayerItemId()->contains($playerEquipmentRelatedByWeapon1PlayerItemId)) {
            $pos = $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId->search($playerEquipmentRelatedByWeapon1PlayerItemId);
            $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId->remove($pos);
            if (null === $this->playerEquipmentsRelatedByWeapon1PlayerItemIdScheduledForDeletion) {
                $this->playerEquipmentsRelatedByWeapon1PlayerItemIdScheduledForDeletion = clone $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId;
                $this->playerEquipmentsRelatedByWeapon1PlayerItemIdScheduledForDeletion->clear();
            }
            $this->playerEquipmentsRelatedByWeapon1PlayerItemIdScheduledForDeletion[]= clone $playerEquipmentRelatedByWeapon1PlayerItemId;
            $playerEquipmentRelatedByWeapon1PlayerItemId->setPlayerItemRelatedByWeapon1PlayerItemId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PlayerItem is new, it will return
     * an empty collection; or if this PlayerItem has previously
     * been saved, it will retrieve related PlayerEquipmentsRelatedByWeapon1PlayerItemId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PlayerItem.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerEquipment[] List of ChildPlayerEquipment objects
     */
    public function getPlayerEquipmentsRelatedByWeapon1PlayerItemIdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerEquipmentQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerEquipmentsRelatedByWeapon1PlayerItemId($query, $con);
    }

    /**
     * Clears out the collPlayerEquipmentsRelatedByWeapon2PlayerItemId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerEquipmentsRelatedByWeapon2PlayerItemId()
     */
    public function clearPlayerEquipmentsRelatedByWeapon2PlayerItemId()
    {
        $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerEquipmentsRelatedByWeapon2PlayerItemId collection loaded partially.
     */
    public function resetPartialPlayerEquipmentsRelatedByWeapon2PlayerItemId($v = true)
    {
        $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemIdPartial = $v;
    }

    /**
     * Initializes the collPlayerEquipmentsRelatedByWeapon2PlayerItemId collection.
     *
     * By default this just sets the collPlayerEquipmentsRelatedByWeapon2PlayerItemId collection to an empty array (like clearcollPlayerEquipmentsRelatedByWeapon2PlayerItemId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerEquipmentsRelatedByWeapon2PlayerItemId($overrideExisting = true)
    {
        if (null !== $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerEquipmentTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId = new $collectionClassName;
        $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId->setModel('\app\model\PlayerEquipment');
    }

    /**
     * Gets an array of ChildPlayerEquipment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayerItem is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerEquipment[] List of ChildPlayerEquipment objects
     * @throws PropelException
     */
    public function getPlayerEquipmentsRelatedByWeapon2PlayerItemId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId) {
                // return empty collection
                $this->initPlayerEquipmentsRelatedByWeapon2PlayerItemId();
            } else {
                $collPlayerEquipmentsRelatedByWeapon2PlayerItemId = ChildPlayerEquipmentQuery::create(null, $criteria)
                    ->filterByPlayerItemRelatedByWeapon2PlayerItemId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemIdPartial && count($collPlayerEquipmentsRelatedByWeapon2PlayerItemId)) {
                        $this->initPlayerEquipmentsRelatedByWeapon2PlayerItemId(false);

                        foreach ($collPlayerEquipmentsRelatedByWeapon2PlayerItemId as $obj) {
                            if (false == $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId->contains($obj)) {
                                $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId->append($obj);
                            }
                        }

                        $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemIdPartial = true;
                    }

                    return $collPlayerEquipmentsRelatedByWeapon2PlayerItemId;
                }

                if ($partial && $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId) {
                    foreach ($this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerEquipmentsRelatedByWeapon2PlayerItemId[] = $obj;
                        }
                    }
                }

                $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId = $collPlayerEquipmentsRelatedByWeapon2PlayerItemId;
                $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemIdPartial = false;
            }
        }

        return $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId;
    }

    /**
     * Sets a collection of ChildPlayerEquipment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerEquipmentsRelatedByWeapon2PlayerItemId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function setPlayerEquipmentsRelatedByWeapon2PlayerItemId(Collection $playerEquipmentsRelatedByWeapon2PlayerItemId, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerEquipment[] $playerEquipmentsRelatedByWeapon2PlayerItemIdToDelete */
        $playerEquipmentsRelatedByWeapon2PlayerItemIdToDelete = $this->getPlayerEquipmentsRelatedByWeapon2PlayerItemId(new Criteria(), $con)->diff($playerEquipmentsRelatedByWeapon2PlayerItemId);


        $this->playerEquipmentsRelatedByWeapon2PlayerItemIdScheduledForDeletion = $playerEquipmentsRelatedByWeapon2PlayerItemIdToDelete;

        foreach ($playerEquipmentsRelatedByWeapon2PlayerItemIdToDelete as $playerEquipmentRelatedByWeapon2PlayerItemIdRemoved) {
            $playerEquipmentRelatedByWeapon2PlayerItemIdRemoved->setPlayerItemRelatedByWeapon2PlayerItemId(null);
        }

        $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId = null;
        foreach ($playerEquipmentsRelatedByWeapon2PlayerItemId as $playerEquipmentRelatedByWeapon2PlayerItemId) {
            $this->addPlayerEquipmentRelatedByWeapon2PlayerItemId($playerEquipmentRelatedByWeapon2PlayerItemId);
        }

        $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId = $playerEquipmentsRelatedByWeapon2PlayerItemId;
        $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PlayerEquipment objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PlayerEquipment objects.
     * @throws PropelException
     */
    public function countPlayerEquipmentsRelatedByWeapon2PlayerItemId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerEquipmentsRelatedByWeapon2PlayerItemId());
            }

            $query = ChildPlayerEquipmentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerItemRelatedByWeapon2PlayerItemId($this)
                ->count($con);
        }

        return count($this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId);
    }

    /**
     * Method called to associate a ChildPlayerEquipment object to this object
     * through the ChildPlayerEquipment foreign key attribute.
     *
     * @param  ChildPlayerEquipment $l ChildPlayerEquipment
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function addPlayerEquipmentRelatedByWeapon2PlayerItemId(ChildPlayerEquipment $l)
    {
        if ($this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId === null) {
            $this->initPlayerEquipmentsRelatedByWeapon2PlayerItemId();
            $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemIdPartial = true;
        }

        if (!$this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId->contains($l)) {
            $this->doAddPlayerEquipmentRelatedByWeapon2PlayerItemId($l);

            if ($this->playerEquipmentsRelatedByWeapon2PlayerItemIdScheduledForDeletion and $this->playerEquipmentsRelatedByWeapon2PlayerItemIdScheduledForDeletion->contains($l)) {
                $this->playerEquipmentsRelatedByWeapon2PlayerItemIdScheduledForDeletion->remove($this->playerEquipmentsRelatedByWeapon2PlayerItemIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerEquipment $playerEquipmentRelatedByWeapon2PlayerItemId The ChildPlayerEquipment object to add.
     */
    protected function doAddPlayerEquipmentRelatedByWeapon2PlayerItemId(ChildPlayerEquipment $playerEquipmentRelatedByWeapon2PlayerItemId)
    {
        $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId[]= $playerEquipmentRelatedByWeapon2PlayerItemId;
        $playerEquipmentRelatedByWeapon2PlayerItemId->setPlayerItemRelatedByWeapon2PlayerItemId($this);
    }

    /**
     * @param  ChildPlayerEquipment $playerEquipmentRelatedByWeapon2PlayerItemId The ChildPlayerEquipment object to remove.
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function removePlayerEquipmentRelatedByWeapon2PlayerItemId(ChildPlayerEquipment $playerEquipmentRelatedByWeapon2PlayerItemId)
    {
        if ($this->getPlayerEquipmentsRelatedByWeapon2PlayerItemId()->contains($playerEquipmentRelatedByWeapon2PlayerItemId)) {
            $pos = $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId->search($playerEquipmentRelatedByWeapon2PlayerItemId);
            $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId->remove($pos);
            if (null === $this->playerEquipmentsRelatedByWeapon2PlayerItemIdScheduledForDeletion) {
                $this->playerEquipmentsRelatedByWeapon2PlayerItemIdScheduledForDeletion = clone $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId;
                $this->playerEquipmentsRelatedByWeapon2PlayerItemIdScheduledForDeletion->clear();
            }
            $this->playerEquipmentsRelatedByWeapon2PlayerItemIdScheduledForDeletion[]= clone $playerEquipmentRelatedByWeapon2PlayerItemId;
            $playerEquipmentRelatedByWeapon2PlayerItemId->setPlayerItemRelatedByWeapon2PlayerItemId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PlayerItem is new, it will return
     * an empty collection; or if this PlayerItem has previously
     * been saved, it will retrieve related PlayerEquipmentsRelatedByWeapon2PlayerItemId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PlayerItem.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerEquipment[] List of ChildPlayerEquipment objects
     */
    public function getPlayerEquipmentsRelatedByWeapon2PlayerItemIdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerEquipmentQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerEquipmentsRelatedByWeapon2PlayerItemId($query, $con);
    }

    /**
     * Clears out the collPlayerEquipmentsRelatedByHeadPlayerItemId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerEquipmentsRelatedByHeadPlayerItemId()
     */
    public function clearPlayerEquipmentsRelatedByHeadPlayerItemId()
    {
        $this->collPlayerEquipmentsRelatedByHeadPlayerItemId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerEquipmentsRelatedByHeadPlayerItemId collection loaded partially.
     */
    public function resetPartialPlayerEquipmentsRelatedByHeadPlayerItemId($v = true)
    {
        $this->collPlayerEquipmentsRelatedByHeadPlayerItemIdPartial = $v;
    }

    /**
     * Initializes the collPlayerEquipmentsRelatedByHeadPlayerItemId collection.
     *
     * By default this just sets the collPlayerEquipmentsRelatedByHeadPlayerItemId collection to an empty array (like clearcollPlayerEquipmentsRelatedByHeadPlayerItemId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerEquipmentsRelatedByHeadPlayerItemId($overrideExisting = true)
    {
        if (null !== $this->collPlayerEquipmentsRelatedByHeadPlayerItemId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerEquipmentTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerEquipmentsRelatedByHeadPlayerItemId = new $collectionClassName;
        $this->collPlayerEquipmentsRelatedByHeadPlayerItemId->setModel('\app\model\PlayerEquipment');
    }

    /**
     * Gets an array of ChildPlayerEquipment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayerItem is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerEquipment[] List of ChildPlayerEquipment objects
     * @throws PropelException
     */
    public function getPlayerEquipmentsRelatedByHeadPlayerItemId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerEquipmentsRelatedByHeadPlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerEquipmentsRelatedByHeadPlayerItemId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerEquipmentsRelatedByHeadPlayerItemId) {
                // return empty collection
                $this->initPlayerEquipmentsRelatedByHeadPlayerItemId();
            } else {
                $collPlayerEquipmentsRelatedByHeadPlayerItemId = ChildPlayerEquipmentQuery::create(null, $criteria)
                    ->filterByPlayerItemRelatedByHeadPlayerItemId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerEquipmentsRelatedByHeadPlayerItemIdPartial && count($collPlayerEquipmentsRelatedByHeadPlayerItemId)) {
                        $this->initPlayerEquipmentsRelatedByHeadPlayerItemId(false);

                        foreach ($collPlayerEquipmentsRelatedByHeadPlayerItemId as $obj) {
                            if (false == $this->collPlayerEquipmentsRelatedByHeadPlayerItemId->contains($obj)) {
                                $this->collPlayerEquipmentsRelatedByHeadPlayerItemId->append($obj);
                            }
                        }

                        $this->collPlayerEquipmentsRelatedByHeadPlayerItemIdPartial = true;
                    }

                    return $collPlayerEquipmentsRelatedByHeadPlayerItemId;
                }

                if ($partial && $this->collPlayerEquipmentsRelatedByHeadPlayerItemId) {
                    foreach ($this->collPlayerEquipmentsRelatedByHeadPlayerItemId as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerEquipmentsRelatedByHeadPlayerItemId[] = $obj;
                        }
                    }
                }

                $this->collPlayerEquipmentsRelatedByHeadPlayerItemId = $collPlayerEquipmentsRelatedByHeadPlayerItemId;
                $this->collPlayerEquipmentsRelatedByHeadPlayerItemIdPartial = false;
            }
        }

        return $this->collPlayerEquipmentsRelatedByHeadPlayerItemId;
    }

    /**
     * Sets a collection of ChildPlayerEquipment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerEquipmentsRelatedByHeadPlayerItemId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function setPlayerEquipmentsRelatedByHeadPlayerItemId(Collection $playerEquipmentsRelatedByHeadPlayerItemId, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerEquipment[] $playerEquipmentsRelatedByHeadPlayerItemIdToDelete */
        $playerEquipmentsRelatedByHeadPlayerItemIdToDelete = $this->getPlayerEquipmentsRelatedByHeadPlayerItemId(new Criteria(), $con)->diff($playerEquipmentsRelatedByHeadPlayerItemId);


        $this->playerEquipmentsRelatedByHeadPlayerItemIdScheduledForDeletion = $playerEquipmentsRelatedByHeadPlayerItemIdToDelete;

        foreach ($playerEquipmentsRelatedByHeadPlayerItemIdToDelete as $playerEquipmentRelatedByHeadPlayerItemIdRemoved) {
            $playerEquipmentRelatedByHeadPlayerItemIdRemoved->setPlayerItemRelatedByHeadPlayerItemId(null);
        }

        $this->collPlayerEquipmentsRelatedByHeadPlayerItemId = null;
        foreach ($playerEquipmentsRelatedByHeadPlayerItemId as $playerEquipmentRelatedByHeadPlayerItemId) {
            $this->addPlayerEquipmentRelatedByHeadPlayerItemId($playerEquipmentRelatedByHeadPlayerItemId);
        }

        $this->collPlayerEquipmentsRelatedByHeadPlayerItemId = $playerEquipmentsRelatedByHeadPlayerItemId;
        $this->collPlayerEquipmentsRelatedByHeadPlayerItemIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PlayerEquipment objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PlayerEquipment objects.
     * @throws PropelException
     */
    public function countPlayerEquipmentsRelatedByHeadPlayerItemId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerEquipmentsRelatedByHeadPlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerEquipmentsRelatedByHeadPlayerItemId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerEquipmentsRelatedByHeadPlayerItemId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerEquipmentsRelatedByHeadPlayerItemId());
            }

            $query = ChildPlayerEquipmentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerItemRelatedByHeadPlayerItemId($this)
                ->count($con);
        }

        return count($this->collPlayerEquipmentsRelatedByHeadPlayerItemId);
    }

    /**
     * Method called to associate a ChildPlayerEquipment object to this object
     * through the ChildPlayerEquipment foreign key attribute.
     *
     * @param  ChildPlayerEquipment $l ChildPlayerEquipment
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function addPlayerEquipmentRelatedByHeadPlayerItemId(ChildPlayerEquipment $l)
    {
        if ($this->collPlayerEquipmentsRelatedByHeadPlayerItemId === null) {
            $this->initPlayerEquipmentsRelatedByHeadPlayerItemId();
            $this->collPlayerEquipmentsRelatedByHeadPlayerItemIdPartial = true;
        }

        if (!$this->collPlayerEquipmentsRelatedByHeadPlayerItemId->contains($l)) {
            $this->doAddPlayerEquipmentRelatedByHeadPlayerItemId($l);

            if ($this->playerEquipmentsRelatedByHeadPlayerItemIdScheduledForDeletion and $this->playerEquipmentsRelatedByHeadPlayerItemIdScheduledForDeletion->contains($l)) {
                $this->playerEquipmentsRelatedByHeadPlayerItemIdScheduledForDeletion->remove($this->playerEquipmentsRelatedByHeadPlayerItemIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerEquipment $playerEquipmentRelatedByHeadPlayerItemId The ChildPlayerEquipment object to add.
     */
    protected function doAddPlayerEquipmentRelatedByHeadPlayerItemId(ChildPlayerEquipment $playerEquipmentRelatedByHeadPlayerItemId)
    {
        $this->collPlayerEquipmentsRelatedByHeadPlayerItemId[]= $playerEquipmentRelatedByHeadPlayerItemId;
        $playerEquipmentRelatedByHeadPlayerItemId->setPlayerItemRelatedByHeadPlayerItemId($this);
    }

    /**
     * @param  ChildPlayerEquipment $playerEquipmentRelatedByHeadPlayerItemId The ChildPlayerEquipment object to remove.
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function removePlayerEquipmentRelatedByHeadPlayerItemId(ChildPlayerEquipment $playerEquipmentRelatedByHeadPlayerItemId)
    {
        if ($this->getPlayerEquipmentsRelatedByHeadPlayerItemId()->contains($playerEquipmentRelatedByHeadPlayerItemId)) {
            $pos = $this->collPlayerEquipmentsRelatedByHeadPlayerItemId->search($playerEquipmentRelatedByHeadPlayerItemId);
            $this->collPlayerEquipmentsRelatedByHeadPlayerItemId->remove($pos);
            if (null === $this->playerEquipmentsRelatedByHeadPlayerItemIdScheduledForDeletion) {
                $this->playerEquipmentsRelatedByHeadPlayerItemIdScheduledForDeletion = clone $this->collPlayerEquipmentsRelatedByHeadPlayerItemId;
                $this->playerEquipmentsRelatedByHeadPlayerItemIdScheduledForDeletion->clear();
            }
            $this->playerEquipmentsRelatedByHeadPlayerItemIdScheduledForDeletion[]= clone $playerEquipmentRelatedByHeadPlayerItemId;
            $playerEquipmentRelatedByHeadPlayerItemId->setPlayerItemRelatedByHeadPlayerItemId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PlayerItem is new, it will return
     * an empty collection; or if this PlayerItem has previously
     * been saved, it will retrieve related PlayerEquipmentsRelatedByHeadPlayerItemId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PlayerItem.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerEquipment[] List of ChildPlayerEquipment objects
     */
    public function getPlayerEquipmentsRelatedByHeadPlayerItemIdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerEquipmentQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerEquipmentsRelatedByHeadPlayerItemId($query, $con);
    }

    /**
     * Clears out the collPlayerEquipmentsRelatedByLeftArmPlayerItemId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerEquipmentsRelatedByLeftArmPlayerItemId()
     */
    public function clearPlayerEquipmentsRelatedByLeftArmPlayerItemId()
    {
        $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerEquipmentsRelatedByLeftArmPlayerItemId collection loaded partially.
     */
    public function resetPartialPlayerEquipmentsRelatedByLeftArmPlayerItemId($v = true)
    {
        $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemIdPartial = $v;
    }

    /**
     * Initializes the collPlayerEquipmentsRelatedByLeftArmPlayerItemId collection.
     *
     * By default this just sets the collPlayerEquipmentsRelatedByLeftArmPlayerItemId collection to an empty array (like clearcollPlayerEquipmentsRelatedByLeftArmPlayerItemId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerEquipmentsRelatedByLeftArmPlayerItemId($overrideExisting = true)
    {
        if (null !== $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerEquipmentTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId = new $collectionClassName;
        $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId->setModel('\app\model\PlayerEquipment');
    }

    /**
     * Gets an array of ChildPlayerEquipment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayerItem is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerEquipment[] List of ChildPlayerEquipment objects
     * @throws PropelException
     */
    public function getPlayerEquipmentsRelatedByLeftArmPlayerItemId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId) {
                // return empty collection
                $this->initPlayerEquipmentsRelatedByLeftArmPlayerItemId();
            } else {
                $collPlayerEquipmentsRelatedByLeftArmPlayerItemId = ChildPlayerEquipmentQuery::create(null, $criteria)
                    ->filterByPlayerItemRelatedByLeftArmPlayerItemId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemIdPartial && count($collPlayerEquipmentsRelatedByLeftArmPlayerItemId)) {
                        $this->initPlayerEquipmentsRelatedByLeftArmPlayerItemId(false);

                        foreach ($collPlayerEquipmentsRelatedByLeftArmPlayerItemId as $obj) {
                            if (false == $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId->contains($obj)) {
                                $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId->append($obj);
                            }
                        }

                        $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemIdPartial = true;
                    }

                    return $collPlayerEquipmentsRelatedByLeftArmPlayerItemId;
                }

                if ($partial && $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId) {
                    foreach ($this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerEquipmentsRelatedByLeftArmPlayerItemId[] = $obj;
                        }
                    }
                }

                $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId = $collPlayerEquipmentsRelatedByLeftArmPlayerItemId;
                $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemIdPartial = false;
            }
        }

        return $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId;
    }

    /**
     * Sets a collection of ChildPlayerEquipment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerEquipmentsRelatedByLeftArmPlayerItemId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function setPlayerEquipmentsRelatedByLeftArmPlayerItemId(Collection $playerEquipmentsRelatedByLeftArmPlayerItemId, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerEquipment[] $playerEquipmentsRelatedByLeftArmPlayerItemIdToDelete */
        $playerEquipmentsRelatedByLeftArmPlayerItemIdToDelete = $this->getPlayerEquipmentsRelatedByLeftArmPlayerItemId(new Criteria(), $con)->diff($playerEquipmentsRelatedByLeftArmPlayerItemId);


        $this->playerEquipmentsRelatedByLeftArmPlayerItemIdScheduledForDeletion = $playerEquipmentsRelatedByLeftArmPlayerItemIdToDelete;

        foreach ($playerEquipmentsRelatedByLeftArmPlayerItemIdToDelete as $playerEquipmentRelatedByLeftArmPlayerItemIdRemoved) {
            $playerEquipmentRelatedByLeftArmPlayerItemIdRemoved->setPlayerItemRelatedByLeftArmPlayerItemId(null);
        }

        $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId = null;
        foreach ($playerEquipmentsRelatedByLeftArmPlayerItemId as $playerEquipmentRelatedByLeftArmPlayerItemId) {
            $this->addPlayerEquipmentRelatedByLeftArmPlayerItemId($playerEquipmentRelatedByLeftArmPlayerItemId);
        }

        $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId = $playerEquipmentsRelatedByLeftArmPlayerItemId;
        $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PlayerEquipment objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PlayerEquipment objects.
     * @throws PropelException
     */
    public function countPlayerEquipmentsRelatedByLeftArmPlayerItemId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerEquipmentsRelatedByLeftArmPlayerItemId());
            }

            $query = ChildPlayerEquipmentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerItemRelatedByLeftArmPlayerItemId($this)
                ->count($con);
        }

        return count($this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId);
    }

    /**
     * Method called to associate a ChildPlayerEquipment object to this object
     * through the ChildPlayerEquipment foreign key attribute.
     *
     * @param  ChildPlayerEquipment $l ChildPlayerEquipment
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function addPlayerEquipmentRelatedByLeftArmPlayerItemId(ChildPlayerEquipment $l)
    {
        if ($this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId === null) {
            $this->initPlayerEquipmentsRelatedByLeftArmPlayerItemId();
            $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemIdPartial = true;
        }

        if (!$this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId->contains($l)) {
            $this->doAddPlayerEquipmentRelatedByLeftArmPlayerItemId($l);

            if ($this->playerEquipmentsRelatedByLeftArmPlayerItemIdScheduledForDeletion and $this->playerEquipmentsRelatedByLeftArmPlayerItemIdScheduledForDeletion->contains($l)) {
                $this->playerEquipmentsRelatedByLeftArmPlayerItemIdScheduledForDeletion->remove($this->playerEquipmentsRelatedByLeftArmPlayerItemIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerEquipment $playerEquipmentRelatedByLeftArmPlayerItemId The ChildPlayerEquipment object to add.
     */
    protected function doAddPlayerEquipmentRelatedByLeftArmPlayerItemId(ChildPlayerEquipment $playerEquipmentRelatedByLeftArmPlayerItemId)
    {
        $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId[]= $playerEquipmentRelatedByLeftArmPlayerItemId;
        $playerEquipmentRelatedByLeftArmPlayerItemId->setPlayerItemRelatedByLeftArmPlayerItemId($this);
    }

    /**
     * @param  ChildPlayerEquipment $playerEquipmentRelatedByLeftArmPlayerItemId The ChildPlayerEquipment object to remove.
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function removePlayerEquipmentRelatedByLeftArmPlayerItemId(ChildPlayerEquipment $playerEquipmentRelatedByLeftArmPlayerItemId)
    {
        if ($this->getPlayerEquipmentsRelatedByLeftArmPlayerItemId()->contains($playerEquipmentRelatedByLeftArmPlayerItemId)) {
            $pos = $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId->search($playerEquipmentRelatedByLeftArmPlayerItemId);
            $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId->remove($pos);
            if (null === $this->playerEquipmentsRelatedByLeftArmPlayerItemIdScheduledForDeletion) {
                $this->playerEquipmentsRelatedByLeftArmPlayerItemIdScheduledForDeletion = clone $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId;
                $this->playerEquipmentsRelatedByLeftArmPlayerItemIdScheduledForDeletion->clear();
            }
            $this->playerEquipmentsRelatedByLeftArmPlayerItemIdScheduledForDeletion[]= clone $playerEquipmentRelatedByLeftArmPlayerItemId;
            $playerEquipmentRelatedByLeftArmPlayerItemId->setPlayerItemRelatedByLeftArmPlayerItemId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PlayerItem is new, it will return
     * an empty collection; or if this PlayerItem has previously
     * been saved, it will retrieve related PlayerEquipmentsRelatedByLeftArmPlayerItemId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PlayerItem.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerEquipment[] List of ChildPlayerEquipment objects
     */
    public function getPlayerEquipmentsRelatedByLeftArmPlayerItemIdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerEquipmentQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerEquipmentsRelatedByLeftArmPlayerItemId($query, $con);
    }

    /**
     * Clears out the collPlayerEquipmentsRelatedByRightArmPlayerItemId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerEquipmentsRelatedByRightArmPlayerItemId()
     */
    public function clearPlayerEquipmentsRelatedByRightArmPlayerItemId()
    {
        $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerEquipmentsRelatedByRightArmPlayerItemId collection loaded partially.
     */
    public function resetPartialPlayerEquipmentsRelatedByRightArmPlayerItemId($v = true)
    {
        $this->collPlayerEquipmentsRelatedByRightArmPlayerItemIdPartial = $v;
    }

    /**
     * Initializes the collPlayerEquipmentsRelatedByRightArmPlayerItemId collection.
     *
     * By default this just sets the collPlayerEquipmentsRelatedByRightArmPlayerItemId collection to an empty array (like clearcollPlayerEquipmentsRelatedByRightArmPlayerItemId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerEquipmentsRelatedByRightArmPlayerItemId($overrideExisting = true)
    {
        if (null !== $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerEquipmentTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId = new $collectionClassName;
        $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId->setModel('\app\model\PlayerEquipment');
    }

    /**
     * Gets an array of ChildPlayerEquipment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayerItem is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerEquipment[] List of ChildPlayerEquipment objects
     * @throws PropelException
     */
    public function getPlayerEquipmentsRelatedByRightArmPlayerItemId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerEquipmentsRelatedByRightArmPlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId) {
                // return empty collection
                $this->initPlayerEquipmentsRelatedByRightArmPlayerItemId();
            } else {
                $collPlayerEquipmentsRelatedByRightArmPlayerItemId = ChildPlayerEquipmentQuery::create(null, $criteria)
                    ->filterByPlayerItemRelatedByRightArmPlayerItemId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerEquipmentsRelatedByRightArmPlayerItemIdPartial && count($collPlayerEquipmentsRelatedByRightArmPlayerItemId)) {
                        $this->initPlayerEquipmentsRelatedByRightArmPlayerItemId(false);

                        foreach ($collPlayerEquipmentsRelatedByRightArmPlayerItemId as $obj) {
                            if (false == $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId->contains($obj)) {
                                $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId->append($obj);
                            }
                        }

                        $this->collPlayerEquipmentsRelatedByRightArmPlayerItemIdPartial = true;
                    }

                    return $collPlayerEquipmentsRelatedByRightArmPlayerItemId;
                }

                if ($partial && $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId) {
                    foreach ($this->collPlayerEquipmentsRelatedByRightArmPlayerItemId as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerEquipmentsRelatedByRightArmPlayerItemId[] = $obj;
                        }
                    }
                }

                $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId = $collPlayerEquipmentsRelatedByRightArmPlayerItemId;
                $this->collPlayerEquipmentsRelatedByRightArmPlayerItemIdPartial = false;
            }
        }

        return $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId;
    }

    /**
     * Sets a collection of ChildPlayerEquipment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerEquipmentsRelatedByRightArmPlayerItemId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function setPlayerEquipmentsRelatedByRightArmPlayerItemId(Collection $playerEquipmentsRelatedByRightArmPlayerItemId, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerEquipment[] $playerEquipmentsRelatedByRightArmPlayerItemIdToDelete */
        $playerEquipmentsRelatedByRightArmPlayerItemIdToDelete = $this->getPlayerEquipmentsRelatedByRightArmPlayerItemId(new Criteria(), $con)->diff($playerEquipmentsRelatedByRightArmPlayerItemId);


        $this->playerEquipmentsRelatedByRightArmPlayerItemIdScheduledForDeletion = $playerEquipmentsRelatedByRightArmPlayerItemIdToDelete;

        foreach ($playerEquipmentsRelatedByRightArmPlayerItemIdToDelete as $playerEquipmentRelatedByRightArmPlayerItemIdRemoved) {
            $playerEquipmentRelatedByRightArmPlayerItemIdRemoved->setPlayerItemRelatedByRightArmPlayerItemId(null);
        }

        $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId = null;
        foreach ($playerEquipmentsRelatedByRightArmPlayerItemId as $playerEquipmentRelatedByRightArmPlayerItemId) {
            $this->addPlayerEquipmentRelatedByRightArmPlayerItemId($playerEquipmentRelatedByRightArmPlayerItemId);
        }

        $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId = $playerEquipmentsRelatedByRightArmPlayerItemId;
        $this->collPlayerEquipmentsRelatedByRightArmPlayerItemIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PlayerEquipment objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PlayerEquipment objects.
     * @throws PropelException
     */
    public function countPlayerEquipmentsRelatedByRightArmPlayerItemId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerEquipmentsRelatedByRightArmPlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerEquipmentsRelatedByRightArmPlayerItemId());
            }

            $query = ChildPlayerEquipmentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerItemRelatedByRightArmPlayerItemId($this)
                ->count($con);
        }

        return count($this->collPlayerEquipmentsRelatedByRightArmPlayerItemId);
    }

    /**
     * Method called to associate a ChildPlayerEquipment object to this object
     * through the ChildPlayerEquipment foreign key attribute.
     *
     * @param  ChildPlayerEquipment $l ChildPlayerEquipment
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function addPlayerEquipmentRelatedByRightArmPlayerItemId(ChildPlayerEquipment $l)
    {
        if ($this->collPlayerEquipmentsRelatedByRightArmPlayerItemId === null) {
            $this->initPlayerEquipmentsRelatedByRightArmPlayerItemId();
            $this->collPlayerEquipmentsRelatedByRightArmPlayerItemIdPartial = true;
        }

        if (!$this->collPlayerEquipmentsRelatedByRightArmPlayerItemId->contains($l)) {
            $this->doAddPlayerEquipmentRelatedByRightArmPlayerItemId($l);

            if ($this->playerEquipmentsRelatedByRightArmPlayerItemIdScheduledForDeletion and $this->playerEquipmentsRelatedByRightArmPlayerItemIdScheduledForDeletion->contains($l)) {
                $this->playerEquipmentsRelatedByRightArmPlayerItemIdScheduledForDeletion->remove($this->playerEquipmentsRelatedByRightArmPlayerItemIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerEquipment $playerEquipmentRelatedByRightArmPlayerItemId The ChildPlayerEquipment object to add.
     */
    protected function doAddPlayerEquipmentRelatedByRightArmPlayerItemId(ChildPlayerEquipment $playerEquipmentRelatedByRightArmPlayerItemId)
    {
        $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId[]= $playerEquipmentRelatedByRightArmPlayerItemId;
        $playerEquipmentRelatedByRightArmPlayerItemId->setPlayerItemRelatedByRightArmPlayerItemId($this);
    }

    /**
     * @param  ChildPlayerEquipment $playerEquipmentRelatedByRightArmPlayerItemId The ChildPlayerEquipment object to remove.
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function removePlayerEquipmentRelatedByRightArmPlayerItemId(ChildPlayerEquipment $playerEquipmentRelatedByRightArmPlayerItemId)
    {
        if ($this->getPlayerEquipmentsRelatedByRightArmPlayerItemId()->contains($playerEquipmentRelatedByRightArmPlayerItemId)) {
            $pos = $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId->search($playerEquipmentRelatedByRightArmPlayerItemId);
            $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId->remove($pos);
            if (null === $this->playerEquipmentsRelatedByRightArmPlayerItemIdScheduledForDeletion) {
                $this->playerEquipmentsRelatedByRightArmPlayerItemIdScheduledForDeletion = clone $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId;
                $this->playerEquipmentsRelatedByRightArmPlayerItemIdScheduledForDeletion->clear();
            }
            $this->playerEquipmentsRelatedByRightArmPlayerItemIdScheduledForDeletion[]= clone $playerEquipmentRelatedByRightArmPlayerItemId;
            $playerEquipmentRelatedByRightArmPlayerItemId->setPlayerItemRelatedByRightArmPlayerItemId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PlayerItem is new, it will return
     * an empty collection; or if this PlayerItem has previously
     * been saved, it will retrieve related PlayerEquipmentsRelatedByRightArmPlayerItemId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PlayerItem.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerEquipment[] List of ChildPlayerEquipment objects
     */
    public function getPlayerEquipmentsRelatedByRightArmPlayerItemIdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerEquipmentQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerEquipmentsRelatedByRightArmPlayerItemId($query, $con);
    }

    /**
     * Clears out the collPlayerEquipmentsRelatedByLeftLegPlayerItemId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerEquipmentsRelatedByLeftLegPlayerItemId()
     */
    public function clearPlayerEquipmentsRelatedByLeftLegPlayerItemId()
    {
        $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerEquipmentsRelatedByLeftLegPlayerItemId collection loaded partially.
     */
    public function resetPartialPlayerEquipmentsRelatedByLeftLegPlayerItemId($v = true)
    {
        $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemIdPartial = $v;
    }

    /**
     * Initializes the collPlayerEquipmentsRelatedByLeftLegPlayerItemId collection.
     *
     * By default this just sets the collPlayerEquipmentsRelatedByLeftLegPlayerItemId collection to an empty array (like clearcollPlayerEquipmentsRelatedByLeftLegPlayerItemId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerEquipmentsRelatedByLeftLegPlayerItemId($overrideExisting = true)
    {
        if (null !== $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerEquipmentTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId = new $collectionClassName;
        $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId->setModel('\app\model\PlayerEquipment');
    }

    /**
     * Gets an array of ChildPlayerEquipment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayerItem is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerEquipment[] List of ChildPlayerEquipment objects
     * @throws PropelException
     */
    public function getPlayerEquipmentsRelatedByLeftLegPlayerItemId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId) {
                // return empty collection
                $this->initPlayerEquipmentsRelatedByLeftLegPlayerItemId();
            } else {
                $collPlayerEquipmentsRelatedByLeftLegPlayerItemId = ChildPlayerEquipmentQuery::create(null, $criteria)
                    ->filterByPlayerItemRelatedByLeftLegPlayerItemId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemIdPartial && count($collPlayerEquipmentsRelatedByLeftLegPlayerItemId)) {
                        $this->initPlayerEquipmentsRelatedByLeftLegPlayerItemId(false);

                        foreach ($collPlayerEquipmentsRelatedByLeftLegPlayerItemId as $obj) {
                            if (false == $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId->contains($obj)) {
                                $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId->append($obj);
                            }
                        }

                        $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemIdPartial = true;
                    }

                    return $collPlayerEquipmentsRelatedByLeftLegPlayerItemId;
                }

                if ($partial && $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId) {
                    foreach ($this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerEquipmentsRelatedByLeftLegPlayerItemId[] = $obj;
                        }
                    }
                }

                $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId = $collPlayerEquipmentsRelatedByLeftLegPlayerItemId;
                $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemIdPartial = false;
            }
        }

        return $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId;
    }

    /**
     * Sets a collection of ChildPlayerEquipment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerEquipmentsRelatedByLeftLegPlayerItemId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function setPlayerEquipmentsRelatedByLeftLegPlayerItemId(Collection $playerEquipmentsRelatedByLeftLegPlayerItemId, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerEquipment[] $playerEquipmentsRelatedByLeftLegPlayerItemIdToDelete */
        $playerEquipmentsRelatedByLeftLegPlayerItemIdToDelete = $this->getPlayerEquipmentsRelatedByLeftLegPlayerItemId(new Criteria(), $con)->diff($playerEquipmentsRelatedByLeftLegPlayerItemId);


        $this->playerEquipmentsRelatedByLeftLegPlayerItemIdScheduledForDeletion = $playerEquipmentsRelatedByLeftLegPlayerItemIdToDelete;

        foreach ($playerEquipmentsRelatedByLeftLegPlayerItemIdToDelete as $playerEquipmentRelatedByLeftLegPlayerItemIdRemoved) {
            $playerEquipmentRelatedByLeftLegPlayerItemIdRemoved->setPlayerItemRelatedByLeftLegPlayerItemId(null);
        }

        $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId = null;
        foreach ($playerEquipmentsRelatedByLeftLegPlayerItemId as $playerEquipmentRelatedByLeftLegPlayerItemId) {
            $this->addPlayerEquipmentRelatedByLeftLegPlayerItemId($playerEquipmentRelatedByLeftLegPlayerItemId);
        }

        $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId = $playerEquipmentsRelatedByLeftLegPlayerItemId;
        $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PlayerEquipment objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PlayerEquipment objects.
     * @throws PropelException
     */
    public function countPlayerEquipmentsRelatedByLeftLegPlayerItemId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerEquipmentsRelatedByLeftLegPlayerItemId());
            }

            $query = ChildPlayerEquipmentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerItemRelatedByLeftLegPlayerItemId($this)
                ->count($con);
        }

        return count($this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId);
    }

    /**
     * Method called to associate a ChildPlayerEquipment object to this object
     * through the ChildPlayerEquipment foreign key attribute.
     *
     * @param  ChildPlayerEquipment $l ChildPlayerEquipment
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function addPlayerEquipmentRelatedByLeftLegPlayerItemId(ChildPlayerEquipment $l)
    {
        if ($this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId === null) {
            $this->initPlayerEquipmentsRelatedByLeftLegPlayerItemId();
            $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemIdPartial = true;
        }

        if (!$this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId->contains($l)) {
            $this->doAddPlayerEquipmentRelatedByLeftLegPlayerItemId($l);

            if ($this->playerEquipmentsRelatedByLeftLegPlayerItemIdScheduledForDeletion and $this->playerEquipmentsRelatedByLeftLegPlayerItemIdScheduledForDeletion->contains($l)) {
                $this->playerEquipmentsRelatedByLeftLegPlayerItemIdScheduledForDeletion->remove($this->playerEquipmentsRelatedByLeftLegPlayerItemIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerEquipment $playerEquipmentRelatedByLeftLegPlayerItemId The ChildPlayerEquipment object to add.
     */
    protected function doAddPlayerEquipmentRelatedByLeftLegPlayerItemId(ChildPlayerEquipment $playerEquipmentRelatedByLeftLegPlayerItemId)
    {
        $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId[]= $playerEquipmentRelatedByLeftLegPlayerItemId;
        $playerEquipmentRelatedByLeftLegPlayerItemId->setPlayerItemRelatedByLeftLegPlayerItemId($this);
    }

    /**
     * @param  ChildPlayerEquipment $playerEquipmentRelatedByLeftLegPlayerItemId The ChildPlayerEquipment object to remove.
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function removePlayerEquipmentRelatedByLeftLegPlayerItemId(ChildPlayerEquipment $playerEquipmentRelatedByLeftLegPlayerItemId)
    {
        if ($this->getPlayerEquipmentsRelatedByLeftLegPlayerItemId()->contains($playerEquipmentRelatedByLeftLegPlayerItemId)) {
            $pos = $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId->search($playerEquipmentRelatedByLeftLegPlayerItemId);
            $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId->remove($pos);
            if (null === $this->playerEquipmentsRelatedByLeftLegPlayerItemIdScheduledForDeletion) {
                $this->playerEquipmentsRelatedByLeftLegPlayerItemIdScheduledForDeletion = clone $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId;
                $this->playerEquipmentsRelatedByLeftLegPlayerItemIdScheduledForDeletion->clear();
            }
            $this->playerEquipmentsRelatedByLeftLegPlayerItemIdScheduledForDeletion[]= clone $playerEquipmentRelatedByLeftLegPlayerItemId;
            $playerEquipmentRelatedByLeftLegPlayerItemId->setPlayerItemRelatedByLeftLegPlayerItemId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PlayerItem is new, it will return
     * an empty collection; or if this PlayerItem has previously
     * been saved, it will retrieve related PlayerEquipmentsRelatedByLeftLegPlayerItemId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PlayerItem.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerEquipment[] List of ChildPlayerEquipment objects
     */
    public function getPlayerEquipmentsRelatedByLeftLegPlayerItemIdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerEquipmentQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerEquipmentsRelatedByLeftLegPlayerItemId($query, $con);
    }

    /**
     * Clears out the collPlayerEquipmentsRelatedByRightLegPlayerItemId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerEquipmentsRelatedByRightLegPlayerItemId()
     */
    public function clearPlayerEquipmentsRelatedByRightLegPlayerItemId()
    {
        $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerEquipmentsRelatedByRightLegPlayerItemId collection loaded partially.
     */
    public function resetPartialPlayerEquipmentsRelatedByRightLegPlayerItemId($v = true)
    {
        $this->collPlayerEquipmentsRelatedByRightLegPlayerItemIdPartial = $v;
    }

    /**
     * Initializes the collPlayerEquipmentsRelatedByRightLegPlayerItemId collection.
     *
     * By default this just sets the collPlayerEquipmentsRelatedByRightLegPlayerItemId collection to an empty array (like clearcollPlayerEquipmentsRelatedByRightLegPlayerItemId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerEquipmentsRelatedByRightLegPlayerItemId($overrideExisting = true)
    {
        if (null !== $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerEquipmentTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId = new $collectionClassName;
        $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId->setModel('\app\model\PlayerEquipment');
    }

    /**
     * Gets an array of ChildPlayerEquipment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayerItem is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerEquipment[] List of ChildPlayerEquipment objects
     * @throws PropelException
     */
    public function getPlayerEquipmentsRelatedByRightLegPlayerItemId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerEquipmentsRelatedByRightLegPlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId) {
                // return empty collection
                $this->initPlayerEquipmentsRelatedByRightLegPlayerItemId();
            } else {
                $collPlayerEquipmentsRelatedByRightLegPlayerItemId = ChildPlayerEquipmentQuery::create(null, $criteria)
                    ->filterByPlayerItemRelatedByRightLegPlayerItemId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerEquipmentsRelatedByRightLegPlayerItemIdPartial && count($collPlayerEquipmentsRelatedByRightLegPlayerItemId)) {
                        $this->initPlayerEquipmentsRelatedByRightLegPlayerItemId(false);

                        foreach ($collPlayerEquipmentsRelatedByRightLegPlayerItemId as $obj) {
                            if (false == $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId->contains($obj)) {
                                $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId->append($obj);
                            }
                        }

                        $this->collPlayerEquipmentsRelatedByRightLegPlayerItemIdPartial = true;
                    }

                    return $collPlayerEquipmentsRelatedByRightLegPlayerItemId;
                }

                if ($partial && $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId) {
                    foreach ($this->collPlayerEquipmentsRelatedByRightLegPlayerItemId as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerEquipmentsRelatedByRightLegPlayerItemId[] = $obj;
                        }
                    }
                }

                $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId = $collPlayerEquipmentsRelatedByRightLegPlayerItemId;
                $this->collPlayerEquipmentsRelatedByRightLegPlayerItemIdPartial = false;
            }
        }

        return $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId;
    }

    /**
     * Sets a collection of ChildPlayerEquipment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerEquipmentsRelatedByRightLegPlayerItemId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function setPlayerEquipmentsRelatedByRightLegPlayerItemId(Collection $playerEquipmentsRelatedByRightLegPlayerItemId, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerEquipment[] $playerEquipmentsRelatedByRightLegPlayerItemIdToDelete */
        $playerEquipmentsRelatedByRightLegPlayerItemIdToDelete = $this->getPlayerEquipmentsRelatedByRightLegPlayerItemId(new Criteria(), $con)->diff($playerEquipmentsRelatedByRightLegPlayerItemId);


        $this->playerEquipmentsRelatedByRightLegPlayerItemIdScheduledForDeletion = $playerEquipmentsRelatedByRightLegPlayerItemIdToDelete;

        foreach ($playerEquipmentsRelatedByRightLegPlayerItemIdToDelete as $playerEquipmentRelatedByRightLegPlayerItemIdRemoved) {
            $playerEquipmentRelatedByRightLegPlayerItemIdRemoved->setPlayerItemRelatedByRightLegPlayerItemId(null);
        }

        $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId = null;
        foreach ($playerEquipmentsRelatedByRightLegPlayerItemId as $playerEquipmentRelatedByRightLegPlayerItemId) {
            $this->addPlayerEquipmentRelatedByRightLegPlayerItemId($playerEquipmentRelatedByRightLegPlayerItemId);
        }

        $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId = $playerEquipmentsRelatedByRightLegPlayerItemId;
        $this->collPlayerEquipmentsRelatedByRightLegPlayerItemIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PlayerEquipment objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PlayerEquipment objects.
     * @throws PropelException
     */
    public function countPlayerEquipmentsRelatedByRightLegPlayerItemId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerEquipmentsRelatedByRightLegPlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerEquipmentsRelatedByRightLegPlayerItemId());
            }

            $query = ChildPlayerEquipmentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerItemRelatedByRightLegPlayerItemId($this)
                ->count($con);
        }

        return count($this->collPlayerEquipmentsRelatedByRightLegPlayerItemId);
    }

    /**
     * Method called to associate a ChildPlayerEquipment object to this object
     * through the ChildPlayerEquipment foreign key attribute.
     *
     * @param  ChildPlayerEquipment $l ChildPlayerEquipment
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function addPlayerEquipmentRelatedByRightLegPlayerItemId(ChildPlayerEquipment $l)
    {
        if ($this->collPlayerEquipmentsRelatedByRightLegPlayerItemId === null) {
            $this->initPlayerEquipmentsRelatedByRightLegPlayerItemId();
            $this->collPlayerEquipmentsRelatedByRightLegPlayerItemIdPartial = true;
        }

        if (!$this->collPlayerEquipmentsRelatedByRightLegPlayerItemId->contains($l)) {
            $this->doAddPlayerEquipmentRelatedByRightLegPlayerItemId($l);

            if ($this->playerEquipmentsRelatedByRightLegPlayerItemIdScheduledForDeletion and $this->playerEquipmentsRelatedByRightLegPlayerItemIdScheduledForDeletion->contains($l)) {
                $this->playerEquipmentsRelatedByRightLegPlayerItemIdScheduledForDeletion->remove($this->playerEquipmentsRelatedByRightLegPlayerItemIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerEquipment $playerEquipmentRelatedByRightLegPlayerItemId The ChildPlayerEquipment object to add.
     */
    protected function doAddPlayerEquipmentRelatedByRightLegPlayerItemId(ChildPlayerEquipment $playerEquipmentRelatedByRightLegPlayerItemId)
    {
        $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId[]= $playerEquipmentRelatedByRightLegPlayerItemId;
        $playerEquipmentRelatedByRightLegPlayerItemId->setPlayerItemRelatedByRightLegPlayerItemId($this);
    }

    /**
     * @param  ChildPlayerEquipment $playerEquipmentRelatedByRightLegPlayerItemId The ChildPlayerEquipment object to remove.
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function removePlayerEquipmentRelatedByRightLegPlayerItemId(ChildPlayerEquipment $playerEquipmentRelatedByRightLegPlayerItemId)
    {
        if ($this->getPlayerEquipmentsRelatedByRightLegPlayerItemId()->contains($playerEquipmentRelatedByRightLegPlayerItemId)) {
            $pos = $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId->search($playerEquipmentRelatedByRightLegPlayerItemId);
            $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId->remove($pos);
            if (null === $this->playerEquipmentsRelatedByRightLegPlayerItemIdScheduledForDeletion) {
                $this->playerEquipmentsRelatedByRightLegPlayerItemIdScheduledForDeletion = clone $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId;
                $this->playerEquipmentsRelatedByRightLegPlayerItemIdScheduledForDeletion->clear();
            }
            $this->playerEquipmentsRelatedByRightLegPlayerItemIdScheduledForDeletion[]= clone $playerEquipmentRelatedByRightLegPlayerItemId;
            $playerEquipmentRelatedByRightLegPlayerItemId->setPlayerItemRelatedByRightLegPlayerItemId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PlayerItem is new, it will return
     * an empty collection; or if this PlayerItem has previously
     * been saved, it will retrieve related PlayerEquipmentsRelatedByRightLegPlayerItemId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PlayerItem.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerEquipment[] List of ChildPlayerEquipment objects
     */
    public function getPlayerEquipmentsRelatedByRightLegPlayerItemIdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerEquipmentQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerEquipmentsRelatedByRightLegPlayerItemId($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aPlayer) {
            $this->aPlayer->removePlayerItem($this);
        }
        if (null !== $this->aItem) {
            $this->aItem->removePlayerItem($this);
        }
        $this->id = null;
        $this->player_id = null;
        $this->item_id = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId) {
                foreach ($this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId) {
                foreach ($this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerEquipmentsRelatedByHeadPlayerItemId) {
                foreach ($this->collPlayerEquipmentsRelatedByHeadPlayerItemId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId) {
                foreach ($this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerEquipmentsRelatedByRightArmPlayerItemId) {
                foreach ($this->collPlayerEquipmentsRelatedByRightArmPlayerItemId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId) {
                foreach ($this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerEquipmentsRelatedByRightLegPlayerItemId) {
                foreach ($this->collPlayerEquipmentsRelatedByRightLegPlayerItemId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collPlayerEquipmentsRelatedByWeapon1PlayerItemId = null;
        $this->collPlayerEquipmentsRelatedByWeapon2PlayerItemId = null;
        $this->collPlayerEquipmentsRelatedByHeadPlayerItemId = null;
        $this->collPlayerEquipmentsRelatedByLeftArmPlayerItemId = null;
        $this->collPlayerEquipmentsRelatedByRightArmPlayerItemId = null;
        $this->collPlayerEquipmentsRelatedByLeftLegPlayerItemId = null;
        $this->collPlayerEquipmentsRelatedByRightLegPlayerItemId = null;
        $this->aPlayer = null;
        $this->aItem = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PlayerItemTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[PlayerItemTableMap::COL_UPDATED_AT] = true;

        return $this;
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postSave')) {
            parent::postSave($con);
        }
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preInsert')) {
            return parent::preInsert($con);
        }
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postInsert')) {
            parent::postInsert($con);
        }
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preUpdate')) {
            return parent::preUpdate($con);
        }
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postUpdate')) {
            parent::postUpdate($con);
        }
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postDelete')) {
            parent::postDelete($con);
        }
    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
