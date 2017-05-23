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
use app\model\PlayerDeck as ChildPlayerDeck;
use app\model\PlayerDeckQuery as ChildPlayerDeckQuery;
use app\model\PlayerItem as ChildPlayerItem;
use app\model\PlayerItemQuery as ChildPlayerItemQuery;
use app\model\PlayerQuery as ChildPlayerQuery;
use app\model\Map\PlayerDeckTableMap;
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
     * @var        ObjectCollection|ChildPlayerDeck[] Collection to store aggregation of ChildPlayerDeck objects.
     */
    protected $collPlayerDecksRelatedByHeadPlayerItemId;
    protected $collPlayerDecksRelatedByHeadPlayerItemIdPartial;

    /**
     * @var        ObjectCollection|ChildPlayerDeck[] Collection to store aggregation of ChildPlayerDeck objects.
     */
    protected $collPlayerDecksRelatedByLeftArmPlayerItemId;
    protected $collPlayerDecksRelatedByLeftArmPlayerItemIdPartial;

    /**
     * @var        ObjectCollection|ChildPlayerDeck[] Collection to store aggregation of ChildPlayerDeck objects.
     */
    protected $collPlayerDecksRelatedByRightArmPlayerItemId;
    protected $collPlayerDecksRelatedByRightArmPlayerItemIdPartial;

    /**
     * @var        ObjectCollection|ChildPlayerDeck[] Collection to store aggregation of ChildPlayerDeck objects.
     */
    protected $collPlayerDecksRelatedByLeftLegPlayerItemId;
    protected $collPlayerDecksRelatedByLeftLegPlayerItemIdPartial;

    /**
     * @var        ObjectCollection|ChildPlayerDeck[] Collection to store aggregation of ChildPlayerDeck objects.
     */
    protected $collPlayerDecksRelatedByRightLegPlayerItemId;
    protected $collPlayerDecksRelatedByRightLegPlayerItemIdPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerDeck[]
     */
    protected $playerDecksRelatedByHeadPlayerItemIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerDeck[]
     */
    protected $playerDecksRelatedByLeftArmPlayerItemIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerDeck[]
     */
    protected $playerDecksRelatedByRightArmPlayerItemIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerDeck[]
     */
    protected $playerDecksRelatedByLeftLegPlayerItemIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerDeck[]
     */
    protected $playerDecksRelatedByRightLegPlayerItemIdScheduledForDeletion = null;

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
            $this->collPlayerDecksRelatedByHeadPlayerItemId = null;

            $this->collPlayerDecksRelatedByLeftArmPlayerItemId = null;

            $this->collPlayerDecksRelatedByRightArmPlayerItemId = null;

            $this->collPlayerDecksRelatedByLeftLegPlayerItemId = null;

            $this->collPlayerDecksRelatedByRightLegPlayerItemId = null;

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

            if ($this->playerDecksRelatedByHeadPlayerItemIdScheduledForDeletion !== null) {
                if (!$this->playerDecksRelatedByHeadPlayerItemIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->playerDecksRelatedByHeadPlayerItemIdScheduledForDeletion as $playerDeckRelatedByHeadPlayerItemId) {
                        // need to save related object because we set the relation to null
                        $playerDeckRelatedByHeadPlayerItemId->save($con);
                    }
                    $this->playerDecksRelatedByHeadPlayerItemIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerDecksRelatedByHeadPlayerItemId !== null) {
                foreach ($this->collPlayerDecksRelatedByHeadPlayerItemId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerDecksRelatedByLeftArmPlayerItemIdScheduledForDeletion !== null) {
                if (!$this->playerDecksRelatedByLeftArmPlayerItemIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->playerDecksRelatedByLeftArmPlayerItemIdScheduledForDeletion as $playerDeckRelatedByLeftArmPlayerItemId) {
                        // need to save related object because we set the relation to null
                        $playerDeckRelatedByLeftArmPlayerItemId->save($con);
                    }
                    $this->playerDecksRelatedByLeftArmPlayerItemIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerDecksRelatedByLeftArmPlayerItemId !== null) {
                foreach ($this->collPlayerDecksRelatedByLeftArmPlayerItemId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerDecksRelatedByRightArmPlayerItemIdScheduledForDeletion !== null) {
                if (!$this->playerDecksRelatedByRightArmPlayerItemIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->playerDecksRelatedByRightArmPlayerItemIdScheduledForDeletion as $playerDeckRelatedByRightArmPlayerItemId) {
                        // need to save related object because we set the relation to null
                        $playerDeckRelatedByRightArmPlayerItemId->save($con);
                    }
                    $this->playerDecksRelatedByRightArmPlayerItemIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerDecksRelatedByRightArmPlayerItemId !== null) {
                foreach ($this->collPlayerDecksRelatedByRightArmPlayerItemId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerDecksRelatedByLeftLegPlayerItemIdScheduledForDeletion !== null) {
                if (!$this->playerDecksRelatedByLeftLegPlayerItemIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->playerDecksRelatedByLeftLegPlayerItemIdScheduledForDeletion as $playerDeckRelatedByLeftLegPlayerItemId) {
                        // need to save related object because we set the relation to null
                        $playerDeckRelatedByLeftLegPlayerItemId->save($con);
                    }
                    $this->playerDecksRelatedByLeftLegPlayerItemIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerDecksRelatedByLeftLegPlayerItemId !== null) {
                foreach ($this->collPlayerDecksRelatedByLeftLegPlayerItemId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerDecksRelatedByRightLegPlayerItemIdScheduledForDeletion !== null) {
                if (!$this->playerDecksRelatedByRightLegPlayerItemIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->playerDecksRelatedByRightLegPlayerItemIdScheduledForDeletion as $playerDeckRelatedByRightLegPlayerItemId) {
                        // need to save related object because we set the relation to null
                        $playerDeckRelatedByRightLegPlayerItemId->save($con);
                    }
                    $this->playerDecksRelatedByRightLegPlayerItemIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerDecksRelatedByRightLegPlayerItemId !== null) {
                foreach ($this->collPlayerDecksRelatedByRightLegPlayerItemId as $referrerFK) {
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
            if (null !== $this->collPlayerDecksRelatedByHeadPlayerItemId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerDecks';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_decks';
                        break;
                    default:
                        $key = 'PlayerDecks';
                }

                $result[$key] = $this->collPlayerDecksRelatedByHeadPlayerItemId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerDecksRelatedByLeftArmPlayerItemId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerDecks';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_decks';
                        break;
                    default:
                        $key = 'PlayerDecks';
                }

                $result[$key] = $this->collPlayerDecksRelatedByLeftArmPlayerItemId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerDecksRelatedByRightArmPlayerItemId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerDecks';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_decks';
                        break;
                    default:
                        $key = 'PlayerDecks';
                }

                $result[$key] = $this->collPlayerDecksRelatedByRightArmPlayerItemId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerDecksRelatedByLeftLegPlayerItemId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerDecks';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_decks';
                        break;
                    default:
                        $key = 'PlayerDecks';
                }

                $result[$key] = $this->collPlayerDecksRelatedByLeftLegPlayerItemId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerDecksRelatedByRightLegPlayerItemId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerDecks';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_decks';
                        break;
                    default:
                        $key = 'PlayerDecks';
                }

                $result[$key] = $this->collPlayerDecksRelatedByRightLegPlayerItemId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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

            foreach ($this->getPlayerDecksRelatedByHeadPlayerItemId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerDeckRelatedByHeadPlayerItemId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerDecksRelatedByLeftArmPlayerItemId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerDeckRelatedByLeftArmPlayerItemId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerDecksRelatedByRightArmPlayerItemId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerDeckRelatedByRightArmPlayerItemId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerDecksRelatedByLeftLegPlayerItemId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerDeckRelatedByLeftLegPlayerItemId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerDecksRelatedByRightLegPlayerItemId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerDeckRelatedByRightLegPlayerItemId($relObj->copy($deepCopy));
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
        if ('PlayerDeckRelatedByHeadPlayerItemId' == $relationName) {
            $this->initPlayerDecksRelatedByHeadPlayerItemId();
            return;
        }
        if ('PlayerDeckRelatedByLeftArmPlayerItemId' == $relationName) {
            $this->initPlayerDecksRelatedByLeftArmPlayerItemId();
            return;
        }
        if ('PlayerDeckRelatedByRightArmPlayerItemId' == $relationName) {
            $this->initPlayerDecksRelatedByRightArmPlayerItemId();
            return;
        }
        if ('PlayerDeckRelatedByLeftLegPlayerItemId' == $relationName) {
            $this->initPlayerDecksRelatedByLeftLegPlayerItemId();
            return;
        }
        if ('PlayerDeckRelatedByRightLegPlayerItemId' == $relationName) {
            $this->initPlayerDecksRelatedByRightLegPlayerItemId();
            return;
        }
    }

    /**
     * Clears out the collPlayerDecksRelatedByHeadPlayerItemId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerDecksRelatedByHeadPlayerItemId()
     */
    public function clearPlayerDecksRelatedByHeadPlayerItemId()
    {
        $this->collPlayerDecksRelatedByHeadPlayerItemId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerDecksRelatedByHeadPlayerItemId collection loaded partially.
     */
    public function resetPartialPlayerDecksRelatedByHeadPlayerItemId($v = true)
    {
        $this->collPlayerDecksRelatedByHeadPlayerItemIdPartial = $v;
    }

    /**
     * Initializes the collPlayerDecksRelatedByHeadPlayerItemId collection.
     *
     * By default this just sets the collPlayerDecksRelatedByHeadPlayerItemId collection to an empty array (like clearcollPlayerDecksRelatedByHeadPlayerItemId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerDecksRelatedByHeadPlayerItemId($overrideExisting = true)
    {
        if (null !== $this->collPlayerDecksRelatedByHeadPlayerItemId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerDeckTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerDecksRelatedByHeadPlayerItemId = new $collectionClassName;
        $this->collPlayerDecksRelatedByHeadPlayerItemId->setModel('\app\model\PlayerDeck');
    }

    /**
     * Gets an array of ChildPlayerDeck objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayerItem is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     * @throws PropelException
     */
    public function getPlayerDecksRelatedByHeadPlayerItemId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByHeadPlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByHeadPlayerItemId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByHeadPlayerItemId) {
                // return empty collection
                $this->initPlayerDecksRelatedByHeadPlayerItemId();
            } else {
                $collPlayerDecksRelatedByHeadPlayerItemId = ChildPlayerDeckQuery::create(null, $criteria)
                    ->filterByPlayerItemRelatedByHeadPlayerItemId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerDecksRelatedByHeadPlayerItemIdPartial && count($collPlayerDecksRelatedByHeadPlayerItemId)) {
                        $this->initPlayerDecksRelatedByHeadPlayerItemId(false);

                        foreach ($collPlayerDecksRelatedByHeadPlayerItemId as $obj) {
                            if (false == $this->collPlayerDecksRelatedByHeadPlayerItemId->contains($obj)) {
                                $this->collPlayerDecksRelatedByHeadPlayerItemId->append($obj);
                            }
                        }

                        $this->collPlayerDecksRelatedByHeadPlayerItemIdPartial = true;
                    }

                    return $collPlayerDecksRelatedByHeadPlayerItemId;
                }

                if ($partial && $this->collPlayerDecksRelatedByHeadPlayerItemId) {
                    foreach ($this->collPlayerDecksRelatedByHeadPlayerItemId as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerDecksRelatedByHeadPlayerItemId[] = $obj;
                        }
                    }
                }

                $this->collPlayerDecksRelatedByHeadPlayerItemId = $collPlayerDecksRelatedByHeadPlayerItemId;
                $this->collPlayerDecksRelatedByHeadPlayerItemIdPartial = false;
            }
        }

        return $this->collPlayerDecksRelatedByHeadPlayerItemId;
    }

    /**
     * Sets a collection of ChildPlayerDeck objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerDecksRelatedByHeadPlayerItemId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function setPlayerDecksRelatedByHeadPlayerItemId(Collection $playerDecksRelatedByHeadPlayerItemId, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerDeck[] $playerDecksRelatedByHeadPlayerItemIdToDelete */
        $playerDecksRelatedByHeadPlayerItemIdToDelete = $this->getPlayerDecksRelatedByHeadPlayerItemId(new Criteria(), $con)->diff($playerDecksRelatedByHeadPlayerItemId);


        $this->playerDecksRelatedByHeadPlayerItemIdScheduledForDeletion = $playerDecksRelatedByHeadPlayerItemIdToDelete;

        foreach ($playerDecksRelatedByHeadPlayerItemIdToDelete as $playerDeckRelatedByHeadPlayerItemIdRemoved) {
            $playerDeckRelatedByHeadPlayerItemIdRemoved->setPlayerItemRelatedByHeadPlayerItemId(null);
        }

        $this->collPlayerDecksRelatedByHeadPlayerItemId = null;
        foreach ($playerDecksRelatedByHeadPlayerItemId as $playerDeckRelatedByHeadPlayerItemId) {
            $this->addPlayerDeckRelatedByHeadPlayerItemId($playerDeckRelatedByHeadPlayerItemId);
        }

        $this->collPlayerDecksRelatedByHeadPlayerItemId = $playerDecksRelatedByHeadPlayerItemId;
        $this->collPlayerDecksRelatedByHeadPlayerItemIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PlayerDeck objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PlayerDeck objects.
     * @throws PropelException
     */
    public function countPlayerDecksRelatedByHeadPlayerItemId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByHeadPlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByHeadPlayerItemId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByHeadPlayerItemId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerDecksRelatedByHeadPlayerItemId());
            }

            $query = ChildPlayerDeckQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerItemRelatedByHeadPlayerItemId($this)
                ->count($con);
        }

        return count($this->collPlayerDecksRelatedByHeadPlayerItemId);
    }

    /**
     * Method called to associate a ChildPlayerDeck object to this object
     * through the ChildPlayerDeck foreign key attribute.
     *
     * @param  ChildPlayerDeck $l ChildPlayerDeck
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function addPlayerDeckRelatedByHeadPlayerItemId(ChildPlayerDeck $l)
    {
        if ($this->collPlayerDecksRelatedByHeadPlayerItemId === null) {
            $this->initPlayerDecksRelatedByHeadPlayerItemId();
            $this->collPlayerDecksRelatedByHeadPlayerItemIdPartial = true;
        }

        if (!$this->collPlayerDecksRelatedByHeadPlayerItemId->contains($l)) {
            $this->doAddPlayerDeckRelatedByHeadPlayerItemId($l);

            if ($this->playerDecksRelatedByHeadPlayerItemIdScheduledForDeletion and $this->playerDecksRelatedByHeadPlayerItemIdScheduledForDeletion->contains($l)) {
                $this->playerDecksRelatedByHeadPlayerItemIdScheduledForDeletion->remove($this->playerDecksRelatedByHeadPlayerItemIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerDeck $playerDeckRelatedByHeadPlayerItemId The ChildPlayerDeck object to add.
     */
    protected function doAddPlayerDeckRelatedByHeadPlayerItemId(ChildPlayerDeck $playerDeckRelatedByHeadPlayerItemId)
    {
        $this->collPlayerDecksRelatedByHeadPlayerItemId[]= $playerDeckRelatedByHeadPlayerItemId;
        $playerDeckRelatedByHeadPlayerItemId->setPlayerItemRelatedByHeadPlayerItemId($this);
    }

    /**
     * @param  ChildPlayerDeck $playerDeckRelatedByHeadPlayerItemId The ChildPlayerDeck object to remove.
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function removePlayerDeckRelatedByHeadPlayerItemId(ChildPlayerDeck $playerDeckRelatedByHeadPlayerItemId)
    {
        if ($this->getPlayerDecksRelatedByHeadPlayerItemId()->contains($playerDeckRelatedByHeadPlayerItemId)) {
            $pos = $this->collPlayerDecksRelatedByHeadPlayerItemId->search($playerDeckRelatedByHeadPlayerItemId);
            $this->collPlayerDecksRelatedByHeadPlayerItemId->remove($pos);
            if (null === $this->playerDecksRelatedByHeadPlayerItemIdScheduledForDeletion) {
                $this->playerDecksRelatedByHeadPlayerItemIdScheduledForDeletion = clone $this->collPlayerDecksRelatedByHeadPlayerItemId;
                $this->playerDecksRelatedByHeadPlayerItemIdScheduledForDeletion->clear();
            }
            $this->playerDecksRelatedByHeadPlayerItemIdScheduledForDeletion[]= $playerDeckRelatedByHeadPlayerItemId;
            $playerDeckRelatedByHeadPlayerItemId->setPlayerItemRelatedByHeadPlayerItemId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PlayerItem is new, it will return
     * an empty collection; or if this PlayerItem has previously
     * been saved, it will retrieve related PlayerDecksRelatedByHeadPlayerItemId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PlayerItem.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     */
    public function getPlayerDecksRelatedByHeadPlayerItemIdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerDeckQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerDecksRelatedByHeadPlayerItemId($query, $con);
    }

    /**
     * Clears out the collPlayerDecksRelatedByLeftArmPlayerItemId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerDecksRelatedByLeftArmPlayerItemId()
     */
    public function clearPlayerDecksRelatedByLeftArmPlayerItemId()
    {
        $this->collPlayerDecksRelatedByLeftArmPlayerItemId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerDecksRelatedByLeftArmPlayerItemId collection loaded partially.
     */
    public function resetPartialPlayerDecksRelatedByLeftArmPlayerItemId($v = true)
    {
        $this->collPlayerDecksRelatedByLeftArmPlayerItemIdPartial = $v;
    }

    /**
     * Initializes the collPlayerDecksRelatedByLeftArmPlayerItemId collection.
     *
     * By default this just sets the collPlayerDecksRelatedByLeftArmPlayerItemId collection to an empty array (like clearcollPlayerDecksRelatedByLeftArmPlayerItemId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerDecksRelatedByLeftArmPlayerItemId($overrideExisting = true)
    {
        if (null !== $this->collPlayerDecksRelatedByLeftArmPlayerItemId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerDeckTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerDecksRelatedByLeftArmPlayerItemId = new $collectionClassName;
        $this->collPlayerDecksRelatedByLeftArmPlayerItemId->setModel('\app\model\PlayerDeck');
    }

    /**
     * Gets an array of ChildPlayerDeck objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayerItem is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     * @throws PropelException
     */
    public function getPlayerDecksRelatedByLeftArmPlayerItemId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByLeftArmPlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByLeftArmPlayerItemId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByLeftArmPlayerItemId) {
                // return empty collection
                $this->initPlayerDecksRelatedByLeftArmPlayerItemId();
            } else {
                $collPlayerDecksRelatedByLeftArmPlayerItemId = ChildPlayerDeckQuery::create(null, $criteria)
                    ->filterByPlayerItemRelatedByLeftArmPlayerItemId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerDecksRelatedByLeftArmPlayerItemIdPartial && count($collPlayerDecksRelatedByLeftArmPlayerItemId)) {
                        $this->initPlayerDecksRelatedByLeftArmPlayerItemId(false);

                        foreach ($collPlayerDecksRelatedByLeftArmPlayerItemId as $obj) {
                            if (false == $this->collPlayerDecksRelatedByLeftArmPlayerItemId->contains($obj)) {
                                $this->collPlayerDecksRelatedByLeftArmPlayerItemId->append($obj);
                            }
                        }

                        $this->collPlayerDecksRelatedByLeftArmPlayerItemIdPartial = true;
                    }

                    return $collPlayerDecksRelatedByLeftArmPlayerItemId;
                }

                if ($partial && $this->collPlayerDecksRelatedByLeftArmPlayerItemId) {
                    foreach ($this->collPlayerDecksRelatedByLeftArmPlayerItemId as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerDecksRelatedByLeftArmPlayerItemId[] = $obj;
                        }
                    }
                }

                $this->collPlayerDecksRelatedByLeftArmPlayerItemId = $collPlayerDecksRelatedByLeftArmPlayerItemId;
                $this->collPlayerDecksRelatedByLeftArmPlayerItemIdPartial = false;
            }
        }

        return $this->collPlayerDecksRelatedByLeftArmPlayerItemId;
    }

    /**
     * Sets a collection of ChildPlayerDeck objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerDecksRelatedByLeftArmPlayerItemId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function setPlayerDecksRelatedByLeftArmPlayerItemId(Collection $playerDecksRelatedByLeftArmPlayerItemId, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerDeck[] $playerDecksRelatedByLeftArmPlayerItemIdToDelete */
        $playerDecksRelatedByLeftArmPlayerItemIdToDelete = $this->getPlayerDecksRelatedByLeftArmPlayerItemId(new Criteria(), $con)->diff($playerDecksRelatedByLeftArmPlayerItemId);


        $this->playerDecksRelatedByLeftArmPlayerItemIdScheduledForDeletion = $playerDecksRelatedByLeftArmPlayerItemIdToDelete;

        foreach ($playerDecksRelatedByLeftArmPlayerItemIdToDelete as $playerDeckRelatedByLeftArmPlayerItemIdRemoved) {
            $playerDeckRelatedByLeftArmPlayerItemIdRemoved->setPlayerItemRelatedByLeftArmPlayerItemId(null);
        }

        $this->collPlayerDecksRelatedByLeftArmPlayerItemId = null;
        foreach ($playerDecksRelatedByLeftArmPlayerItemId as $playerDeckRelatedByLeftArmPlayerItemId) {
            $this->addPlayerDeckRelatedByLeftArmPlayerItemId($playerDeckRelatedByLeftArmPlayerItemId);
        }

        $this->collPlayerDecksRelatedByLeftArmPlayerItemId = $playerDecksRelatedByLeftArmPlayerItemId;
        $this->collPlayerDecksRelatedByLeftArmPlayerItemIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PlayerDeck objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PlayerDeck objects.
     * @throws PropelException
     */
    public function countPlayerDecksRelatedByLeftArmPlayerItemId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByLeftArmPlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByLeftArmPlayerItemId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByLeftArmPlayerItemId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerDecksRelatedByLeftArmPlayerItemId());
            }

            $query = ChildPlayerDeckQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerItemRelatedByLeftArmPlayerItemId($this)
                ->count($con);
        }

        return count($this->collPlayerDecksRelatedByLeftArmPlayerItemId);
    }

    /**
     * Method called to associate a ChildPlayerDeck object to this object
     * through the ChildPlayerDeck foreign key attribute.
     *
     * @param  ChildPlayerDeck $l ChildPlayerDeck
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function addPlayerDeckRelatedByLeftArmPlayerItemId(ChildPlayerDeck $l)
    {
        if ($this->collPlayerDecksRelatedByLeftArmPlayerItemId === null) {
            $this->initPlayerDecksRelatedByLeftArmPlayerItemId();
            $this->collPlayerDecksRelatedByLeftArmPlayerItemIdPartial = true;
        }

        if (!$this->collPlayerDecksRelatedByLeftArmPlayerItemId->contains($l)) {
            $this->doAddPlayerDeckRelatedByLeftArmPlayerItemId($l);

            if ($this->playerDecksRelatedByLeftArmPlayerItemIdScheduledForDeletion and $this->playerDecksRelatedByLeftArmPlayerItemIdScheduledForDeletion->contains($l)) {
                $this->playerDecksRelatedByLeftArmPlayerItemIdScheduledForDeletion->remove($this->playerDecksRelatedByLeftArmPlayerItemIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerDeck $playerDeckRelatedByLeftArmPlayerItemId The ChildPlayerDeck object to add.
     */
    protected function doAddPlayerDeckRelatedByLeftArmPlayerItemId(ChildPlayerDeck $playerDeckRelatedByLeftArmPlayerItemId)
    {
        $this->collPlayerDecksRelatedByLeftArmPlayerItemId[]= $playerDeckRelatedByLeftArmPlayerItemId;
        $playerDeckRelatedByLeftArmPlayerItemId->setPlayerItemRelatedByLeftArmPlayerItemId($this);
    }

    /**
     * @param  ChildPlayerDeck $playerDeckRelatedByLeftArmPlayerItemId The ChildPlayerDeck object to remove.
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function removePlayerDeckRelatedByLeftArmPlayerItemId(ChildPlayerDeck $playerDeckRelatedByLeftArmPlayerItemId)
    {
        if ($this->getPlayerDecksRelatedByLeftArmPlayerItemId()->contains($playerDeckRelatedByLeftArmPlayerItemId)) {
            $pos = $this->collPlayerDecksRelatedByLeftArmPlayerItemId->search($playerDeckRelatedByLeftArmPlayerItemId);
            $this->collPlayerDecksRelatedByLeftArmPlayerItemId->remove($pos);
            if (null === $this->playerDecksRelatedByLeftArmPlayerItemIdScheduledForDeletion) {
                $this->playerDecksRelatedByLeftArmPlayerItemIdScheduledForDeletion = clone $this->collPlayerDecksRelatedByLeftArmPlayerItemId;
                $this->playerDecksRelatedByLeftArmPlayerItemIdScheduledForDeletion->clear();
            }
            $this->playerDecksRelatedByLeftArmPlayerItemIdScheduledForDeletion[]= $playerDeckRelatedByLeftArmPlayerItemId;
            $playerDeckRelatedByLeftArmPlayerItemId->setPlayerItemRelatedByLeftArmPlayerItemId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PlayerItem is new, it will return
     * an empty collection; or if this PlayerItem has previously
     * been saved, it will retrieve related PlayerDecksRelatedByLeftArmPlayerItemId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PlayerItem.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     */
    public function getPlayerDecksRelatedByLeftArmPlayerItemIdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerDeckQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerDecksRelatedByLeftArmPlayerItemId($query, $con);
    }

    /**
     * Clears out the collPlayerDecksRelatedByRightArmPlayerItemId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerDecksRelatedByRightArmPlayerItemId()
     */
    public function clearPlayerDecksRelatedByRightArmPlayerItemId()
    {
        $this->collPlayerDecksRelatedByRightArmPlayerItemId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerDecksRelatedByRightArmPlayerItemId collection loaded partially.
     */
    public function resetPartialPlayerDecksRelatedByRightArmPlayerItemId($v = true)
    {
        $this->collPlayerDecksRelatedByRightArmPlayerItemIdPartial = $v;
    }

    /**
     * Initializes the collPlayerDecksRelatedByRightArmPlayerItemId collection.
     *
     * By default this just sets the collPlayerDecksRelatedByRightArmPlayerItemId collection to an empty array (like clearcollPlayerDecksRelatedByRightArmPlayerItemId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerDecksRelatedByRightArmPlayerItemId($overrideExisting = true)
    {
        if (null !== $this->collPlayerDecksRelatedByRightArmPlayerItemId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerDeckTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerDecksRelatedByRightArmPlayerItemId = new $collectionClassName;
        $this->collPlayerDecksRelatedByRightArmPlayerItemId->setModel('\app\model\PlayerDeck');
    }

    /**
     * Gets an array of ChildPlayerDeck objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayerItem is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     * @throws PropelException
     */
    public function getPlayerDecksRelatedByRightArmPlayerItemId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByRightArmPlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByRightArmPlayerItemId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByRightArmPlayerItemId) {
                // return empty collection
                $this->initPlayerDecksRelatedByRightArmPlayerItemId();
            } else {
                $collPlayerDecksRelatedByRightArmPlayerItemId = ChildPlayerDeckQuery::create(null, $criteria)
                    ->filterByPlayerItemRelatedByRightArmPlayerItemId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerDecksRelatedByRightArmPlayerItemIdPartial && count($collPlayerDecksRelatedByRightArmPlayerItemId)) {
                        $this->initPlayerDecksRelatedByRightArmPlayerItemId(false);

                        foreach ($collPlayerDecksRelatedByRightArmPlayerItemId as $obj) {
                            if (false == $this->collPlayerDecksRelatedByRightArmPlayerItemId->contains($obj)) {
                                $this->collPlayerDecksRelatedByRightArmPlayerItemId->append($obj);
                            }
                        }

                        $this->collPlayerDecksRelatedByRightArmPlayerItemIdPartial = true;
                    }

                    return $collPlayerDecksRelatedByRightArmPlayerItemId;
                }

                if ($partial && $this->collPlayerDecksRelatedByRightArmPlayerItemId) {
                    foreach ($this->collPlayerDecksRelatedByRightArmPlayerItemId as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerDecksRelatedByRightArmPlayerItemId[] = $obj;
                        }
                    }
                }

                $this->collPlayerDecksRelatedByRightArmPlayerItemId = $collPlayerDecksRelatedByRightArmPlayerItemId;
                $this->collPlayerDecksRelatedByRightArmPlayerItemIdPartial = false;
            }
        }

        return $this->collPlayerDecksRelatedByRightArmPlayerItemId;
    }

    /**
     * Sets a collection of ChildPlayerDeck objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerDecksRelatedByRightArmPlayerItemId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function setPlayerDecksRelatedByRightArmPlayerItemId(Collection $playerDecksRelatedByRightArmPlayerItemId, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerDeck[] $playerDecksRelatedByRightArmPlayerItemIdToDelete */
        $playerDecksRelatedByRightArmPlayerItemIdToDelete = $this->getPlayerDecksRelatedByRightArmPlayerItemId(new Criteria(), $con)->diff($playerDecksRelatedByRightArmPlayerItemId);


        $this->playerDecksRelatedByRightArmPlayerItemIdScheduledForDeletion = $playerDecksRelatedByRightArmPlayerItemIdToDelete;

        foreach ($playerDecksRelatedByRightArmPlayerItemIdToDelete as $playerDeckRelatedByRightArmPlayerItemIdRemoved) {
            $playerDeckRelatedByRightArmPlayerItemIdRemoved->setPlayerItemRelatedByRightArmPlayerItemId(null);
        }

        $this->collPlayerDecksRelatedByRightArmPlayerItemId = null;
        foreach ($playerDecksRelatedByRightArmPlayerItemId as $playerDeckRelatedByRightArmPlayerItemId) {
            $this->addPlayerDeckRelatedByRightArmPlayerItemId($playerDeckRelatedByRightArmPlayerItemId);
        }

        $this->collPlayerDecksRelatedByRightArmPlayerItemId = $playerDecksRelatedByRightArmPlayerItemId;
        $this->collPlayerDecksRelatedByRightArmPlayerItemIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PlayerDeck objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PlayerDeck objects.
     * @throws PropelException
     */
    public function countPlayerDecksRelatedByRightArmPlayerItemId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByRightArmPlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByRightArmPlayerItemId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByRightArmPlayerItemId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerDecksRelatedByRightArmPlayerItemId());
            }

            $query = ChildPlayerDeckQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerItemRelatedByRightArmPlayerItemId($this)
                ->count($con);
        }

        return count($this->collPlayerDecksRelatedByRightArmPlayerItemId);
    }

    /**
     * Method called to associate a ChildPlayerDeck object to this object
     * through the ChildPlayerDeck foreign key attribute.
     *
     * @param  ChildPlayerDeck $l ChildPlayerDeck
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function addPlayerDeckRelatedByRightArmPlayerItemId(ChildPlayerDeck $l)
    {
        if ($this->collPlayerDecksRelatedByRightArmPlayerItemId === null) {
            $this->initPlayerDecksRelatedByRightArmPlayerItemId();
            $this->collPlayerDecksRelatedByRightArmPlayerItemIdPartial = true;
        }

        if (!$this->collPlayerDecksRelatedByRightArmPlayerItemId->contains($l)) {
            $this->doAddPlayerDeckRelatedByRightArmPlayerItemId($l);

            if ($this->playerDecksRelatedByRightArmPlayerItemIdScheduledForDeletion and $this->playerDecksRelatedByRightArmPlayerItemIdScheduledForDeletion->contains($l)) {
                $this->playerDecksRelatedByRightArmPlayerItemIdScheduledForDeletion->remove($this->playerDecksRelatedByRightArmPlayerItemIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerDeck $playerDeckRelatedByRightArmPlayerItemId The ChildPlayerDeck object to add.
     */
    protected function doAddPlayerDeckRelatedByRightArmPlayerItemId(ChildPlayerDeck $playerDeckRelatedByRightArmPlayerItemId)
    {
        $this->collPlayerDecksRelatedByRightArmPlayerItemId[]= $playerDeckRelatedByRightArmPlayerItemId;
        $playerDeckRelatedByRightArmPlayerItemId->setPlayerItemRelatedByRightArmPlayerItemId($this);
    }

    /**
     * @param  ChildPlayerDeck $playerDeckRelatedByRightArmPlayerItemId The ChildPlayerDeck object to remove.
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function removePlayerDeckRelatedByRightArmPlayerItemId(ChildPlayerDeck $playerDeckRelatedByRightArmPlayerItemId)
    {
        if ($this->getPlayerDecksRelatedByRightArmPlayerItemId()->contains($playerDeckRelatedByRightArmPlayerItemId)) {
            $pos = $this->collPlayerDecksRelatedByRightArmPlayerItemId->search($playerDeckRelatedByRightArmPlayerItemId);
            $this->collPlayerDecksRelatedByRightArmPlayerItemId->remove($pos);
            if (null === $this->playerDecksRelatedByRightArmPlayerItemIdScheduledForDeletion) {
                $this->playerDecksRelatedByRightArmPlayerItemIdScheduledForDeletion = clone $this->collPlayerDecksRelatedByRightArmPlayerItemId;
                $this->playerDecksRelatedByRightArmPlayerItemIdScheduledForDeletion->clear();
            }
            $this->playerDecksRelatedByRightArmPlayerItemIdScheduledForDeletion[]= $playerDeckRelatedByRightArmPlayerItemId;
            $playerDeckRelatedByRightArmPlayerItemId->setPlayerItemRelatedByRightArmPlayerItemId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PlayerItem is new, it will return
     * an empty collection; or if this PlayerItem has previously
     * been saved, it will retrieve related PlayerDecksRelatedByRightArmPlayerItemId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PlayerItem.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     */
    public function getPlayerDecksRelatedByRightArmPlayerItemIdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerDeckQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerDecksRelatedByRightArmPlayerItemId($query, $con);
    }

    /**
     * Clears out the collPlayerDecksRelatedByLeftLegPlayerItemId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerDecksRelatedByLeftLegPlayerItemId()
     */
    public function clearPlayerDecksRelatedByLeftLegPlayerItemId()
    {
        $this->collPlayerDecksRelatedByLeftLegPlayerItemId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerDecksRelatedByLeftLegPlayerItemId collection loaded partially.
     */
    public function resetPartialPlayerDecksRelatedByLeftLegPlayerItemId($v = true)
    {
        $this->collPlayerDecksRelatedByLeftLegPlayerItemIdPartial = $v;
    }

    /**
     * Initializes the collPlayerDecksRelatedByLeftLegPlayerItemId collection.
     *
     * By default this just sets the collPlayerDecksRelatedByLeftLegPlayerItemId collection to an empty array (like clearcollPlayerDecksRelatedByLeftLegPlayerItemId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerDecksRelatedByLeftLegPlayerItemId($overrideExisting = true)
    {
        if (null !== $this->collPlayerDecksRelatedByLeftLegPlayerItemId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerDeckTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerDecksRelatedByLeftLegPlayerItemId = new $collectionClassName;
        $this->collPlayerDecksRelatedByLeftLegPlayerItemId->setModel('\app\model\PlayerDeck');
    }

    /**
     * Gets an array of ChildPlayerDeck objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayerItem is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     * @throws PropelException
     */
    public function getPlayerDecksRelatedByLeftLegPlayerItemId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByLeftLegPlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByLeftLegPlayerItemId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByLeftLegPlayerItemId) {
                // return empty collection
                $this->initPlayerDecksRelatedByLeftLegPlayerItemId();
            } else {
                $collPlayerDecksRelatedByLeftLegPlayerItemId = ChildPlayerDeckQuery::create(null, $criteria)
                    ->filterByPlayerItemRelatedByLeftLegPlayerItemId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerDecksRelatedByLeftLegPlayerItemIdPartial && count($collPlayerDecksRelatedByLeftLegPlayerItemId)) {
                        $this->initPlayerDecksRelatedByLeftLegPlayerItemId(false);

                        foreach ($collPlayerDecksRelatedByLeftLegPlayerItemId as $obj) {
                            if (false == $this->collPlayerDecksRelatedByLeftLegPlayerItemId->contains($obj)) {
                                $this->collPlayerDecksRelatedByLeftLegPlayerItemId->append($obj);
                            }
                        }

                        $this->collPlayerDecksRelatedByLeftLegPlayerItemIdPartial = true;
                    }

                    return $collPlayerDecksRelatedByLeftLegPlayerItemId;
                }

                if ($partial && $this->collPlayerDecksRelatedByLeftLegPlayerItemId) {
                    foreach ($this->collPlayerDecksRelatedByLeftLegPlayerItemId as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerDecksRelatedByLeftLegPlayerItemId[] = $obj;
                        }
                    }
                }

                $this->collPlayerDecksRelatedByLeftLegPlayerItemId = $collPlayerDecksRelatedByLeftLegPlayerItemId;
                $this->collPlayerDecksRelatedByLeftLegPlayerItemIdPartial = false;
            }
        }

        return $this->collPlayerDecksRelatedByLeftLegPlayerItemId;
    }

    /**
     * Sets a collection of ChildPlayerDeck objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerDecksRelatedByLeftLegPlayerItemId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function setPlayerDecksRelatedByLeftLegPlayerItemId(Collection $playerDecksRelatedByLeftLegPlayerItemId, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerDeck[] $playerDecksRelatedByLeftLegPlayerItemIdToDelete */
        $playerDecksRelatedByLeftLegPlayerItemIdToDelete = $this->getPlayerDecksRelatedByLeftLegPlayerItemId(new Criteria(), $con)->diff($playerDecksRelatedByLeftLegPlayerItemId);


        $this->playerDecksRelatedByLeftLegPlayerItemIdScheduledForDeletion = $playerDecksRelatedByLeftLegPlayerItemIdToDelete;

        foreach ($playerDecksRelatedByLeftLegPlayerItemIdToDelete as $playerDeckRelatedByLeftLegPlayerItemIdRemoved) {
            $playerDeckRelatedByLeftLegPlayerItemIdRemoved->setPlayerItemRelatedByLeftLegPlayerItemId(null);
        }

        $this->collPlayerDecksRelatedByLeftLegPlayerItemId = null;
        foreach ($playerDecksRelatedByLeftLegPlayerItemId as $playerDeckRelatedByLeftLegPlayerItemId) {
            $this->addPlayerDeckRelatedByLeftLegPlayerItemId($playerDeckRelatedByLeftLegPlayerItemId);
        }

        $this->collPlayerDecksRelatedByLeftLegPlayerItemId = $playerDecksRelatedByLeftLegPlayerItemId;
        $this->collPlayerDecksRelatedByLeftLegPlayerItemIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PlayerDeck objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PlayerDeck objects.
     * @throws PropelException
     */
    public function countPlayerDecksRelatedByLeftLegPlayerItemId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByLeftLegPlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByLeftLegPlayerItemId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByLeftLegPlayerItemId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerDecksRelatedByLeftLegPlayerItemId());
            }

            $query = ChildPlayerDeckQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerItemRelatedByLeftLegPlayerItemId($this)
                ->count($con);
        }

        return count($this->collPlayerDecksRelatedByLeftLegPlayerItemId);
    }

    /**
     * Method called to associate a ChildPlayerDeck object to this object
     * through the ChildPlayerDeck foreign key attribute.
     *
     * @param  ChildPlayerDeck $l ChildPlayerDeck
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function addPlayerDeckRelatedByLeftLegPlayerItemId(ChildPlayerDeck $l)
    {
        if ($this->collPlayerDecksRelatedByLeftLegPlayerItemId === null) {
            $this->initPlayerDecksRelatedByLeftLegPlayerItemId();
            $this->collPlayerDecksRelatedByLeftLegPlayerItemIdPartial = true;
        }

        if (!$this->collPlayerDecksRelatedByLeftLegPlayerItemId->contains($l)) {
            $this->doAddPlayerDeckRelatedByLeftLegPlayerItemId($l);

            if ($this->playerDecksRelatedByLeftLegPlayerItemIdScheduledForDeletion and $this->playerDecksRelatedByLeftLegPlayerItemIdScheduledForDeletion->contains($l)) {
                $this->playerDecksRelatedByLeftLegPlayerItemIdScheduledForDeletion->remove($this->playerDecksRelatedByLeftLegPlayerItemIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerDeck $playerDeckRelatedByLeftLegPlayerItemId The ChildPlayerDeck object to add.
     */
    protected function doAddPlayerDeckRelatedByLeftLegPlayerItemId(ChildPlayerDeck $playerDeckRelatedByLeftLegPlayerItemId)
    {
        $this->collPlayerDecksRelatedByLeftLegPlayerItemId[]= $playerDeckRelatedByLeftLegPlayerItemId;
        $playerDeckRelatedByLeftLegPlayerItemId->setPlayerItemRelatedByLeftLegPlayerItemId($this);
    }

    /**
     * @param  ChildPlayerDeck $playerDeckRelatedByLeftLegPlayerItemId The ChildPlayerDeck object to remove.
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function removePlayerDeckRelatedByLeftLegPlayerItemId(ChildPlayerDeck $playerDeckRelatedByLeftLegPlayerItemId)
    {
        if ($this->getPlayerDecksRelatedByLeftLegPlayerItemId()->contains($playerDeckRelatedByLeftLegPlayerItemId)) {
            $pos = $this->collPlayerDecksRelatedByLeftLegPlayerItemId->search($playerDeckRelatedByLeftLegPlayerItemId);
            $this->collPlayerDecksRelatedByLeftLegPlayerItemId->remove($pos);
            if (null === $this->playerDecksRelatedByLeftLegPlayerItemIdScheduledForDeletion) {
                $this->playerDecksRelatedByLeftLegPlayerItemIdScheduledForDeletion = clone $this->collPlayerDecksRelatedByLeftLegPlayerItemId;
                $this->playerDecksRelatedByLeftLegPlayerItemIdScheduledForDeletion->clear();
            }
            $this->playerDecksRelatedByLeftLegPlayerItemIdScheduledForDeletion[]= $playerDeckRelatedByLeftLegPlayerItemId;
            $playerDeckRelatedByLeftLegPlayerItemId->setPlayerItemRelatedByLeftLegPlayerItemId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PlayerItem is new, it will return
     * an empty collection; or if this PlayerItem has previously
     * been saved, it will retrieve related PlayerDecksRelatedByLeftLegPlayerItemId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PlayerItem.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     */
    public function getPlayerDecksRelatedByLeftLegPlayerItemIdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerDeckQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerDecksRelatedByLeftLegPlayerItemId($query, $con);
    }

    /**
     * Clears out the collPlayerDecksRelatedByRightLegPlayerItemId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerDecksRelatedByRightLegPlayerItemId()
     */
    public function clearPlayerDecksRelatedByRightLegPlayerItemId()
    {
        $this->collPlayerDecksRelatedByRightLegPlayerItemId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerDecksRelatedByRightLegPlayerItemId collection loaded partially.
     */
    public function resetPartialPlayerDecksRelatedByRightLegPlayerItemId($v = true)
    {
        $this->collPlayerDecksRelatedByRightLegPlayerItemIdPartial = $v;
    }

    /**
     * Initializes the collPlayerDecksRelatedByRightLegPlayerItemId collection.
     *
     * By default this just sets the collPlayerDecksRelatedByRightLegPlayerItemId collection to an empty array (like clearcollPlayerDecksRelatedByRightLegPlayerItemId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerDecksRelatedByRightLegPlayerItemId($overrideExisting = true)
    {
        if (null !== $this->collPlayerDecksRelatedByRightLegPlayerItemId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerDeckTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerDecksRelatedByRightLegPlayerItemId = new $collectionClassName;
        $this->collPlayerDecksRelatedByRightLegPlayerItemId->setModel('\app\model\PlayerDeck');
    }

    /**
     * Gets an array of ChildPlayerDeck objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayerItem is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     * @throws PropelException
     */
    public function getPlayerDecksRelatedByRightLegPlayerItemId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByRightLegPlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByRightLegPlayerItemId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByRightLegPlayerItemId) {
                // return empty collection
                $this->initPlayerDecksRelatedByRightLegPlayerItemId();
            } else {
                $collPlayerDecksRelatedByRightLegPlayerItemId = ChildPlayerDeckQuery::create(null, $criteria)
                    ->filterByPlayerItemRelatedByRightLegPlayerItemId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerDecksRelatedByRightLegPlayerItemIdPartial && count($collPlayerDecksRelatedByRightLegPlayerItemId)) {
                        $this->initPlayerDecksRelatedByRightLegPlayerItemId(false);

                        foreach ($collPlayerDecksRelatedByRightLegPlayerItemId as $obj) {
                            if (false == $this->collPlayerDecksRelatedByRightLegPlayerItemId->contains($obj)) {
                                $this->collPlayerDecksRelatedByRightLegPlayerItemId->append($obj);
                            }
                        }

                        $this->collPlayerDecksRelatedByRightLegPlayerItemIdPartial = true;
                    }

                    return $collPlayerDecksRelatedByRightLegPlayerItemId;
                }

                if ($partial && $this->collPlayerDecksRelatedByRightLegPlayerItemId) {
                    foreach ($this->collPlayerDecksRelatedByRightLegPlayerItemId as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerDecksRelatedByRightLegPlayerItemId[] = $obj;
                        }
                    }
                }

                $this->collPlayerDecksRelatedByRightLegPlayerItemId = $collPlayerDecksRelatedByRightLegPlayerItemId;
                $this->collPlayerDecksRelatedByRightLegPlayerItemIdPartial = false;
            }
        }

        return $this->collPlayerDecksRelatedByRightLegPlayerItemId;
    }

    /**
     * Sets a collection of ChildPlayerDeck objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerDecksRelatedByRightLegPlayerItemId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function setPlayerDecksRelatedByRightLegPlayerItemId(Collection $playerDecksRelatedByRightLegPlayerItemId, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerDeck[] $playerDecksRelatedByRightLegPlayerItemIdToDelete */
        $playerDecksRelatedByRightLegPlayerItemIdToDelete = $this->getPlayerDecksRelatedByRightLegPlayerItemId(new Criteria(), $con)->diff($playerDecksRelatedByRightLegPlayerItemId);


        $this->playerDecksRelatedByRightLegPlayerItemIdScheduledForDeletion = $playerDecksRelatedByRightLegPlayerItemIdToDelete;

        foreach ($playerDecksRelatedByRightLegPlayerItemIdToDelete as $playerDeckRelatedByRightLegPlayerItemIdRemoved) {
            $playerDeckRelatedByRightLegPlayerItemIdRemoved->setPlayerItemRelatedByRightLegPlayerItemId(null);
        }

        $this->collPlayerDecksRelatedByRightLegPlayerItemId = null;
        foreach ($playerDecksRelatedByRightLegPlayerItemId as $playerDeckRelatedByRightLegPlayerItemId) {
            $this->addPlayerDeckRelatedByRightLegPlayerItemId($playerDeckRelatedByRightLegPlayerItemId);
        }

        $this->collPlayerDecksRelatedByRightLegPlayerItemId = $playerDecksRelatedByRightLegPlayerItemId;
        $this->collPlayerDecksRelatedByRightLegPlayerItemIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PlayerDeck objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PlayerDeck objects.
     * @throws PropelException
     */
    public function countPlayerDecksRelatedByRightLegPlayerItemId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByRightLegPlayerItemIdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByRightLegPlayerItemId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByRightLegPlayerItemId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerDecksRelatedByRightLegPlayerItemId());
            }

            $query = ChildPlayerDeckQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerItemRelatedByRightLegPlayerItemId($this)
                ->count($con);
        }

        return count($this->collPlayerDecksRelatedByRightLegPlayerItemId);
    }

    /**
     * Method called to associate a ChildPlayerDeck object to this object
     * through the ChildPlayerDeck foreign key attribute.
     *
     * @param  ChildPlayerDeck $l ChildPlayerDeck
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function addPlayerDeckRelatedByRightLegPlayerItemId(ChildPlayerDeck $l)
    {
        if ($this->collPlayerDecksRelatedByRightLegPlayerItemId === null) {
            $this->initPlayerDecksRelatedByRightLegPlayerItemId();
            $this->collPlayerDecksRelatedByRightLegPlayerItemIdPartial = true;
        }

        if (!$this->collPlayerDecksRelatedByRightLegPlayerItemId->contains($l)) {
            $this->doAddPlayerDeckRelatedByRightLegPlayerItemId($l);

            if ($this->playerDecksRelatedByRightLegPlayerItemIdScheduledForDeletion and $this->playerDecksRelatedByRightLegPlayerItemIdScheduledForDeletion->contains($l)) {
                $this->playerDecksRelatedByRightLegPlayerItemIdScheduledForDeletion->remove($this->playerDecksRelatedByRightLegPlayerItemIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerDeck $playerDeckRelatedByRightLegPlayerItemId The ChildPlayerDeck object to add.
     */
    protected function doAddPlayerDeckRelatedByRightLegPlayerItemId(ChildPlayerDeck $playerDeckRelatedByRightLegPlayerItemId)
    {
        $this->collPlayerDecksRelatedByRightLegPlayerItemId[]= $playerDeckRelatedByRightLegPlayerItemId;
        $playerDeckRelatedByRightLegPlayerItemId->setPlayerItemRelatedByRightLegPlayerItemId($this);
    }

    /**
     * @param  ChildPlayerDeck $playerDeckRelatedByRightLegPlayerItemId The ChildPlayerDeck object to remove.
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function removePlayerDeckRelatedByRightLegPlayerItemId(ChildPlayerDeck $playerDeckRelatedByRightLegPlayerItemId)
    {
        if ($this->getPlayerDecksRelatedByRightLegPlayerItemId()->contains($playerDeckRelatedByRightLegPlayerItemId)) {
            $pos = $this->collPlayerDecksRelatedByRightLegPlayerItemId->search($playerDeckRelatedByRightLegPlayerItemId);
            $this->collPlayerDecksRelatedByRightLegPlayerItemId->remove($pos);
            if (null === $this->playerDecksRelatedByRightLegPlayerItemIdScheduledForDeletion) {
                $this->playerDecksRelatedByRightLegPlayerItemIdScheduledForDeletion = clone $this->collPlayerDecksRelatedByRightLegPlayerItemId;
                $this->playerDecksRelatedByRightLegPlayerItemIdScheduledForDeletion->clear();
            }
            $this->playerDecksRelatedByRightLegPlayerItemIdScheduledForDeletion[]= $playerDeckRelatedByRightLegPlayerItemId;
            $playerDeckRelatedByRightLegPlayerItemId->setPlayerItemRelatedByRightLegPlayerItemId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PlayerItem is new, it will return
     * an empty collection; or if this PlayerItem has previously
     * been saved, it will retrieve related PlayerDecksRelatedByRightLegPlayerItemId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PlayerItem.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     */
    public function getPlayerDecksRelatedByRightLegPlayerItemIdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerDeckQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerDecksRelatedByRightLegPlayerItemId($query, $con);
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
            if ($this->collPlayerDecksRelatedByHeadPlayerItemId) {
                foreach ($this->collPlayerDecksRelatedByHeadPlayerItemId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerDecksRelatedByLeftArmPlayerItemId) {
                foreach ($this->collPlayerDecksRelatedByLeftArmPlayerItemId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerDecksRelatedByRightArmPlayerItemId) {
                foreach ($this->collPlayerDecksRelatedByRightArmPlayerItemId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerDecksRelatedByLeftLegPlayerItemId) {
                foreach ($this->collPlayerDecksRelatedByLeftLegPlayerItemId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerDecksRelatedByRightLegPlayerItemId) {
                foreach ($this->collPlayerDecksRelatedByRightLegPlayerItemId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collPlayerDecksRelatedByHeadPlayerItemId = null;
        $this->collPlayerDecksRelatedByLeftArmPlayerItemId = null;
        $this->collPlayerDecksRelatedByRightArmPlayerItemId = null;
        $this->collPlayerDecksRelatedByLeftLegPlayerItemId = null;
        $this->collPlayerDecksRelatedByRightLegPlayerItemId = null;
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
