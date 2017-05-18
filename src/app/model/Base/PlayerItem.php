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
    protected $collPlayerDecksRelatedByPlayerItem1Id;
    protected $collPlayerDecksRelatedByPlayerItem1IdPartial;

    /**
     * @var        ObjectCollection|ChildPlayerDeck[] Collection to store aggregation of ChildPlayerDeck objects.
     */
    protected $collPlayerDecksRelatedByPlayerItem2Id;
    protected $collPlayerDecksRelatedByPlayerItem2IdPartial;

    /**
     * @var        ObjectCollection|ChildPlayerDeck[] Collection to store aggregation of ChildPlayerDeck objects.
     */
    protected $collPlayerDecksRelatedByPlayerItem3Id;
    protected $collPlayerDecksRelatedByPlayerItem3IdPartial;

    /**
     * @var        ObjectCollection|ChildPlayerDeck[] Collection to store aggregation of ChildPlayerDeck objects.
     */
    protected $collPlayerDecksRelatedByPlayerItem4Id;
    protected $collPlayerDecksRelatedByPlayerItem4IdPartial;

    /**
     * @var        ObjectCollection|ChildPlayerDeck[] Collection to store aggregation of ChildPlayerDeck objects.
     */
    protected $collPlayerDecksRelatedByPlayerItem5Id;
    protected $collPlayerDecksRelatedByPlayerItem5IdPartial;

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
    protected $playerDecksRelatedByPlayerItem1IdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerDeck[]
     */
    protected $playerDecksRelatedByPlayerItem2IdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerDeck[]
     */
    protected $playerDecksRelatedByPlayerItem3IdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerDeck[]
     */
    protected $playerDecksRelatedByPlayerItem4IdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerDeck[]
     */
    protected $playerDecksRelatedByPlayerItem5IdScheduledForDeletion = null;

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
            $this->collPlayerDecksRelatedByPlayerItem1Id = null;

            $this->collPlayerDecksRelatedByPlayerItem2Id = null;

            $this->collPlayerDecksRelatedByPlayerItem3Id = null;

            $this->collPlayerDecksRelatedByPlayerItem4Id = null;

            $this->collPlayerDecksRelatedByPlayerItem5Id = null;

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

            if ($this->playerDecksRelatedByPlayerItem1IdScheduledForDeletion !== null) {
                if (!$this->playerDecksRelatedByPlayerItem1IdScheduledForDeletion->isEmpty()) {
                    foreach ($this->playerDecksRelatedByPlayerItem1IdScheduledForDeletion as $playerDeckRelatedByPlayerItem1Id) {
                        // need to save related object because we set the relation to null
                        $playerDeckRelatedByPlayerItem1Id->save($con);
                    }
                    $this->playerDecksRelatedByPlayerItem1IdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerDecksRelatedByPlayerItem1Id !== null) {
                foreach ($this->collPlayerDecksRelatedByPlayerItem1Id as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerDecksRelatedByPlayerItem2IdScheduledForDeletion !== null) {
                if (!$this->playerDecksRelatedByPlayerItem2IdScheduledForDeletion->isEmpty()) {
                    foreach ($this->playerDecksRelatedByPlayerItem2IdScheduledForDeletion as $playerDeckRelatedByPlayerItem2Id) {
                        // need to save related object because we set the relation to null
                        $playerDeckRelatedByPlayerItem2Id->save($con);
                    }
                    $this->playerDecksRelatedByPlayerItem2IdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerDecksRelatedByPlayerItem2Id !== null) {
                foreach ($this->collPlayerDecksRelatedByPlayerItem2Id as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerDecksRelatedByPlayerItem3IdScheduledForDeletion !== null) {
                if (!$this->playerDecksRelatedByPlayerItem3IdScheduledForDeletion->isEmpty()) {
                    foreach ($this->playerDecksRelatedByPlayerItem3IdScheduledForDeletion as $playerDeckRelatedByPlayerItem3Id) {
                        // need to save related object because we set the relation to null
                        $playerDeckRelatedByPlayerItem3Id->save($con);
                    }
                    $this->playerDecksRelatedByPlayerItem3IdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerDecksRelatedByPlayerItem3Id !== null) {
                foreach ($this->collPlayerDecksRelatedByPlayerItem3Id as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerDecksRelatedByPlayerItem4IdScheduledForDeletion !== null) {
                if (!$this->playerDecksRelatedByPlayerItem4IdScheduledForDeletion->isEmpty()) {
                    foreach ($this->playerDecksRelatedByPlayerItem4IdScheduledForDeletion as $playerDeckRelatedByPlayerItem4Id) {
                        // need to save related object because we set the relation to null
                        $playerDeckRelatedByPlayerItem4Id->save($con);
                    }
                    $this->playerDecksRelatedByPlayerItem4IdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerDecksRelatedByPlayerItem4Id !== null) {
                foreach ($this->collPlayerDecksRelatedByPlayerItem4Id as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerDecksRelatedByPlayerItem5IdScheduledForDeletion !== null) {
                if (!$this->playerDecksRelatedByPlayerItem5IdScheduledForDeletion->isEmpty()) {
                    foreach ($this->playerDecksRelatedByPlayerItem5IdScheduledForDeletion as $playerDeckRelatedByPlayerItem5Id) {
                        // need to save related object because we set the relation to null
                        $playerDeckRelatedByPlayerItem5Id->save($con);
                    }
                    $this->playerDecksRelatedByPlayerItem5IdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerDecksRelatedByPlayerItem5Id !== null) {
                foreach ($this->collPlayerDecksRelatedByPlayerItem5Id as $referrerFK) {
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
            if (null !== $this->collPlayerDecksRelatedByPlayerItem1Id) {

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

                $result[$key] = $this->collPlayerDecksRelatedByPlayerItem1Id->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerDecksRelatedByPlayerItem2Id) {

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

                $result[$key] = $this->collPlayerDecksRelatedByPlayerItem2Id->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerDecksRelatedByPlayerItem3Id) {

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

                $result[$key] = $this->collPlayerDecksRelatedByPlayerItem3Id->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerDecksRelatedByPlayerItem4Id) {

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

                $result[$key] = $this->collPlayerDecksRelatedByPlayerItem4Id->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerDecksRelatedByPlayerItem5Id) {

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

                $result[$key] = $this->collPlayerDecksRelatedByPlayerItem5Id->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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

            foreach ($this->getPlayerDecksRelatedByPlayerItem1Id() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerDeckRelatedByPlayerItem1Id($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerDecksRelatedByPlayerItem2Id() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerDeckRelatedByPlayerItem2Id($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerDecksRelatedByPlayerItem3Id() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerDeckRelatedByPlayerItem3Id($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerDecksRelatedByPlayerItem4Id() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerDeckRelatedByPlayerItem4Id($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerDecksRelatedByPlayerItem5Id() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerDeckRelatedByPlayerItem5Id($relObj->copy($deepCopy));
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
        if ('PlayerDeckRelatedByPlayerItem1Id' == $relationName) {
            $this->initPlayerDecksRelatedByPlayerItem1Id();
            return;
        }
        if ('PlayerDeckRelatedByPlayerItem2Id' == $relationName) {
            $this->initPlayerDecksRelatedByPlayerItem2Id();
            return;
        }
        if ('PlayerDeckRelatedByPlayerItem3Id' == $relationName) {
            $this->initPlayerDecksRelatedByPlayerItem3Id();
            return;
        }
        if ('PlayerDeckRelatedByPlayerItem4Id' == $relationName) {
            $this->initPlayerDecksRelatedByPlayerItem4Id();
            return;
        }
        if ('PlayerDeckRelatedByPlayerItem5Id' == $relationName) {
            $this->initPlayerDecksRelatedByPlayerItem5Id();
            return;
        }
    }

    /**
     * Clears out the collPlayerDecksRelatedByPlayerItem1Id collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerDecksRelatedByPlayerItem1Id()
     */
    public function clearPlayerDecksRelatedByPlayerItem1Id()
    {
        $this->collPlayerDecksRelatedByPlayerItem1Id = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerDecksRelatedByPlayerItem1Id collection loaded partially.
     */
    public function resetPartialPlayerDecksRelatedByPlayerItem1Id($v = true)
    {
        $this->collPlayerDecksRelatedByPlayerItem1IdPartial = $v;
    }

    /**
     * Initializes the collPlayerDecksRelatedByPlayerItem1Id collection.
     *
     * By default this just sets the collPlayerDecksRelatedByPlayerItem1Id collection to an empty array (like clearcollPlayerDecksRelatedByPlayerItem1Id());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerDecksRelatedByPlayerItem1Id($overrideExisting = true)
    {
        if (null !== $this->collPlayerDecksRelatedByPlayerItem1Id && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerDeckTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerDecksRelatedByPlayerItem1Id = new $collectionClassName;
        $this->collPlayerDecksRelatedByPlayerItem1Id->setModel('\app\model\PlayerDeck');
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
    public function getPlayerDecksRelatedByPlayerItem1Id(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByPlayerItem1IdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByPlayerItem1Id || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByPlayerItem1Id) {
                // return empty collection
                $this->initPlayerDecksRelatedByPlayerItem1Id();
            } else {
                $collPlayerDecksRelatedByPlayerItem1Id = ChildPlayerDeckQuery::create(null, $criteria)
                    ->filterByPlayerItemRelatedByPlayerItem1Id($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerDecksRelatedByPlayerItem1IdPartial && count($collPlayerDecksRelatedByPlayerItem1Id)) {
                        $this->initPlayerDecksRelatedByPlayerItem1Id(false);

                        foreach ($collPlayerDecksRelatedByPlayerItem1Id as $obj) {
                            if (false == $this->collPlayerDecksRelatedByPlayerItem1Id->contains($obj)) {
                                $this->collPlayerDecksRelatedByPlayerItem1Id->append($obj);
                            }
                        }

                        $this->collPlayerDecksRelatedByPlayerItem1IdPartial = true;
                    }

                    return $collPlayerDecksRelatedByPlayerItem1Id;
                }

                if ($partial && $this->collPlayerDecksRelatedByPlayerItem1Id) {
                    foreach ($this->collPlayerDecksRelatedByPlayerItem1Id as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerDecksRelatedByPlayerItem1Id[] = $obj;
                        }
                    }
                }

                $this->collPlayerDecksRelatedByPlayerItem1Id = $collPlayerDecksRelatedByPlayerItem1Id;
                $this->collPlayerDecksRelatedByPlayerItem1IdPartial = false;
            }
        }

        return $this->collPlayerDecksRelatedByPlayerItem1Id;
    }

    /**
     * Sets a collection of ChildPlayerDeck objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerDecksRelatedByPlayerItem1Id A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function setPlayerDecksRelatedByPlayerItem1Id(Collection $playerDecksRelatedByPlayerItem1Id, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerDeck[] $playerDecksRelatedByPlayerItem1IdToDelete */
        $playerDecksRelatedByPlayerItem1IdToDelete = $this->getPlayerDecksRelatedByPlayerItem1Id(new Criteria(), $con)->diff($playerDecksRelatedByPlayerItem1Id);


        $this->playerDecksRelatedByPlayerItem1IdScheduledForDeletion = $playerDecksRelatedByPlayerItem1IdToDelete;

        foreach ($playerDecksRelatedByPlayerItem1IdToDelete as $playerDeckRelatedByPlayerItem1IdRemoved) {
            $playerDeckRelatedByPlayerItem1IdRemoved->setPlayerItemRelatedByPlayerItem1Id(null);
        }

        $this->collPlayerDecksRelatedByPlayerItem1Id = null;
        foreach ($playerDecksRelatedByPlayerItem1Id as $playerDeckRelatedByPlayerItem1Id) {
            $this->addPlayerDeckRelatedByPlayerItem1Id($playerDeckRelatedByPlayerItem1Id);
        }

        $this->collPlayerDecksRelatedByPlayerItem1Id = $playerDecksRelatedByPlayerItem1Id;
        $this->collPlayerDecksRelatedByPlayerItem1IdPartial = false;

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
    public function countPlayerDecksRelatedByPlayerItem1Id(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByPlayerItem1IdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByPlayerItem1Id || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByPlayerItem1Id) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerDecksRelatedByPlayerItem1Id());
            }

            $query = ChildPlayerDeckQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerItemRelatedByPlayerItem1Id($this)
                ->count($con);
        }

        return count($this->collPlayerDecksRelatedByPlayerItem1Id);
    }

    /**
     * Method called to associate a ChildPlayerDeck object to this object
     * through the ChildPlayerDeck foreign key attribute.
     *
     * @param  ChildPlayerDeck $l ChildPlayerDeck
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function addPlayerDeckRelatedByPlayerItem1Id(ChildPlayerDeck $l)
    {
        if ($this->collPlayerDecksRelatedByPlayerItem1Id === null) {
            $this->initPlayerDecksRelatedByPlayerItem1Id();
            $this->collPlayerDecksRelatedByPlayerItem1IdPartial = true;
        }

        if (!$this->collPlayerDecksRelatedByPlayerItem1Id->contains($l)) {
            $this->doAddPlayerDeckRelatedByPlayerItem1Id($l);

            if ($this->playerDecksRelatedByPlayerItem1IdScheduledForDeletion and $this->playerDecksRelatedByPlayerItem1IdScheduledForDeletion->contains($l)) {
                $this->playerDecksRelatedByPlayerItem1IdScheduledForDeletion->remove($this->playerDecksRelatedByPlayerItem1IdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerDeck $playerDeckRelatedByPlayerItem1Id The ChildPlayerDeck object to add.
     */
    protected function doAddPlayerDeckRelatedByPlayerItem1Id(ChildPlayerDeck $playerDeckRelatedByPlayerItem1Id)
    {
        $this->collPlayerDecksRelatedByPlayerItem1Id[]= $playerDeckRelatedByPlayerItem1Id;
        $playerDeckRelatedByPlayerItem1Id->setPlayerItemRelatedByPlayerItem1Id($this);
    }

    /**
     * @param  ChildPlayerDeck $playerDeckRelatedByPlayerItem1Id The ChildPlayerDeck object to remove.
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function removePlayerDeckRelatedByPlayerItem1Id(ChildPlayerDeck $playerDeckRelatedByPlayerItem1Id)
    {
        if ($this->getPlayerDecksRelatedByPlayerItem1Id()->contains($playerDeckRelatedByPlayerItem1Id)) {
            $pos = $this->collPlayerDecksRelatedByPlayerItem1Id->search($playerDeckRelatedByPlayerItem1Id);
            $this->collPlayerDecksRelatedByPlayerItem1Id->remove($pos);
            if (null === $this->playerDecksRelatedByPlayerItem1IdScheduledForDeletion) {
                $this->playerDecksRelatedByPlayerItem1IdScheduledForDeletion = clone $this->collPlayerDecksRelatedByPlayerItem1Id;
                $this->playerDecksRelatedByPlayerItem1IdScheduledForDeletion->clear();
            }
            $this->playerDecksRelatedByPlayerItem1IdScheduledForDeletion[]= $playerDeckRelatedByPlayerItem1Id;
            $playerDeckRelatedByPlayerItem1Id->setPlayerItemRelatedByPlayerItem1Id(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PlayerItem is new, it will return
     * an empty collection; or if this PlayerItem has previously
     * been saved, it will retrieve related PlayerDecksRelatedByPlayerItem1Id from storage.
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
    public function getPlayerDecksRelatedByPlayerItem1IdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerDeckQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerDecksRelatedByPlayerItem1Id($query, $con);
    }

    /**
     * Clears out the collPlayerDecksRelatedByPlayerItem2Id collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerDecksRelatedByPlayerItem2Id()
     */
    public function clearPlayerDecksRelatedByPlayerItem2Id()
    {
        $this->collPlayerDecksRelatedByPlayerItem2Id = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerDecksRelatedByPlayerItem2Id collection loaded partially.
     */
    public function resetPartialPlayerDecksRelatedByPlayerItem2Id($v = true)
    {
        $this->collPlayerDecksRelatedByPlayerItem2IdPartial = $v;
    }

    /**
     * Initializes the collPlayerDecksRelatedByPlayerItem2Id collection.
     *
     * By default this just sets the collPlayerDecksRelatedByPlayerItem2Id collection to an empty array (like clearcollPlayerDecksRelatedByPlayerItem2Id());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerDecksRelatedByPlayerItem2Id($overrideExisting = true)
    {
        if (null !== $this->collPlayerDecksRelatedByPlayerItem2Id && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerDeckTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerDecksRelatedByPlayerItem2Id = new $collectionClassName;
        $this->collPlayerDecksRelatedByPlayerItem2Id->setModel('\app\model\PlayerDeck');
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
    public function getPlayerDecksRelatedByPlayerItem2Id(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByPlayerItem2IdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByPlayerItem2Id || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByPlayerItem2Id) {
                // return empty collection
                $this->initPlayerDecksRelatedByPlayerItem2Id();
            } else {
                $collPlayerDecksRelatedByPlayerItem2Id = ChildPlayerDeckQuery::create(null, $criteria)
                    ->filterByPlayerItemRelatedByPlayerItem2Id($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerDecksRelatedByPlayerItem2IdPartial && count($collPlayerDecksRelatedByPlayerItem2Id)) {
                        $this->initPlayerDecksRelatedByPlayerItem2Id(false);

                        foreach ($collPlayerDecksRelatedByPlayerItem2Id as $obj) {
                            if (false == $this->collPlayerDecksRelatedByPlayerItem2Id->contains($obj)) {
                                $this->collPlayerDecksRelatedByPlayerItem2Id->append($obj);
                            }
                        }

                        $this->collPlayerDecksRelatedByPlayerItem2IdPartial = true;
                    }

                    return $collPlayerDecksRelatedByPlayerItem2Id;
                }

                if ($partial && $this->collPlayerDecksRelatedByPlayerItem2Id) {
                    foreach ($this->collPlayerDecksRelatedByPlayerItem2Id as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerDecksRelatedByPlayerItem2Id[] = $obj;
                        }
                    }
                }

                $this->collPlayerDecksRelatedByPlayerItem2Id = $collPlayerDecksRelatedByPlayerItem2Id;
                $this->collPlayerDecksRelatedByPlayerItem2IdPartial = false;
            }
        }

        return $this->collPlayerDecksRelatedByPlayerItem2Id;
    }

    /**
     * Sets a collection of ChildPlayerDeck objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerDecksRelatedByPlayerItem2Id A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function setPlayerDecksRelatedByPlayerItem2Id(Collection $playerDecksRelatedByPlayerItem2Id, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerDeck[] $playerDecksRelatedByPlayerItem2IdToDelete */
        $playerDecksRelatedByPlayerItem2IdToDelete = $this->getPlayerDecksRelatedByPlayerItem2Id(new Criteria(), $con)->diff($playerDecksRelatedByPlayerItem2Id);


        $this->playerDecksRelatedByPlayerItem2IdScheduledForDeletion = $playerDecksRelatedByPlayerItem2IdToDelete;

        foreach ($playerDecksRelatedByPlayerItem2IdToDelete as $playerDeckRelatedByPlayerItem2IdRemoved) {
            $playerDeckRelatedByPlayerItem2IdRemoved->setPlayerItemRelatedByPlayerItem2Id(null);
        }

        $this->collPlayerDecksRelatedByPlayerItem2Id = null;
        foreach ($playerDecksRelatedByPlayerItem2Id as $playerDeckRelatedByPlayerItem2Id) {
            $this->addPlayerDeckRelatedByPlayerItem2Id($playerDeckRelatedByPlayerItem2Id);
        }

        $this->collPlayerDecksRelatedByPlayerItem2Id = $playerDecksRelatedByPlayerItem2Id;
        $this->collPlayerDecksRelatedByPlayerItem2IdPartial = false;

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
    public function countPlayerDecksRelatedByPlayerItem2Id(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByPlayerItem2IdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByPlayerItem2Id || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByPlayerItem2Id) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerDecksRelatedByPlayerItem2Id());
            }

            $query = ChildPlayerDeckQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerItemRelatedByPlayerItem2Id($this)
                ->count($con);
        }

        return count($this->collPlayerDecksRelatedByPlayerItem2Id);
    }

    /**
     * Method called to associate a ChildPlayerDeck object to this object
     * through the ChildPlayerDeck foreign key attribute.
     *
     * @param  ChildPlayerDeck $l ChildPlayerDeck
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function addPlayerDeckRelatedByPlayerItem2Id(ChildPlayerDeck $l)
    {
        if ($this->collPlayerDecksRelatedByPlayerItem2Id === null) {
            $this->initPlayerDecksRelatedByPlayerItem2Id();
            $this->collPlayerDecksRelatedByPlayerItem2IdPartial = true;
        }

        if (!$this->collPlayerDecksRelatedByPlayerItem2Id->contains($l)) {
            $this->doAddPlayerDeckRelatedByPlayerItem2Id($l);

            if ($this->playerDecksRelatedByPlayerItem2IdScheduledForDeletion and $this->playerDecksRelatedByPlayerItem2IdScheduledForDeletion->contains($l)) {
                $this->playerDecksRelatedByPlayerItem2IdScheduledForDeletion->remove($this->playerDecksRelatedByPlayerItem2IdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerDeck $playerDeckRelatedByPlayerItem2Id The ChildPlayerDeck object to add.
     */
    protected function doAddPlayerDeckRelatedByPlayerItem2Id(ChildPlayerDeck $playerDeckRelatedByPlayerItem2Id)
    {
        $this->collPlayerDecksRelatedByPlayerItem2Id[]= $playerDeckRelatedByPlayerItem2Id;
        $playerDeckRelatedByPlayerItem2Id->setPlayerItemRelatedByPlayerItem2Id($this);
    }

    /**
     * @param  ChildPlayerDeck $playerDeckRelatedByPlayerItem2Id The ChildPlayerDeck object to remove.
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function removePlayerDeckRelatedByPlayerItem2Id(ChildPlayerDeck $playerDeckRelatedByPlayerItem2Id)
    {
        if ($this->getPlayerDecksRelatedByPlayerItem2Id()->contains($playerDeckRelatedByPlayerItem2Id)) {
            $pos = $this->collPlayerDecksRelatedByPlayerItem2Id->search($playerDeckRelatedByPlayerItem2Id);
            $this->collPlayerDecksRelatedByPlayerItem2Id->remove($pos);
            if (null === $this->playerDecksRelatedByPlayerItem2IdScheduledForDeletion) {
                $this->playerDecksRelatedByPlayerItem2IdScheduledForDeletion = clone $this->collPlayerDecksRelatedByPlayerItem2Id;
                $this->playerDecksRelatedByPlayerItem2IdScheduledForDeletion->clear();
            }
            $this->playerDecksRelatedByPlayerItem2IdScheduledForDeletion[]= $playerDeckRelatedByPlayerItem2Id;
            $playerDeckRelatedByPlayerItem2Id->setPlayerItemRelatedByPlayerItem2Id(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PlayerItem is new, it will return
     * an empty collection; or if this PlayerItem has previously
     * been saved, it will retrieve related PlayerDecksRelatedByPlayerItem2Id from storage.
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
    public function getPlayerDecksRelatedByPlayerItem2IdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerDeckQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerDecksRelatedByPlayerItem2Id($query, $con);
    }

    /**
     * Clears out the collPlayerDecksRelatedByPlayerItem3Id collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerDecksRelatedByPlayerItem3Id()
     */
    public function clearPlayerDecksRelatedByPlayerItem3Id()
    {
        $this->collPlayerDecksRelatedByPlayerItem3Id = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerDecksRelatedByPlayerItem3Id collection loaded partially.
     */
    public function resetPartialPlayerDecksRelatedByPlayerItem3Id($v = true)
    {
        $this->collPlayerDecksRelatedByPlayerItem3IdPartial = $v;
    }

    /**
     * Initializes the collPlayerDecksRelatedByPlayerItem3Id collection.
     *
     * By default this just sets the collPlayerDecksRelatedByPlayerItem3Id collection to an empty array (like clearcollPlayerDecksRelatedByPlayerItem3Id());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerDecksRelatedByPlayerItem3Id($overrideExisting = true)
    {
        if (null !== $this->collPlayerDecksRelatedByPlayerItem3Id && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerDeckTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerDecksRelatedByPlayerItem3Id = new $collectionClassName;
        $this->collPlayerDecksRelatedByPlayerItem3Id->setModel('\app\model\PlayerDeck');
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
    public function getPlayerDecksRelatedByPlayerItem3Id(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByPlayerItem3IdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByPlayerItem3Id || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByPlayerItem3Id) {
                // return empty collection
                $this->initPlayerDecksRelatedByPlayerItem3Id();
            } else {
                $collPlayerDecksRelatedByPlayerItem3Id = ChildPlayerDeckQuery::create(null, $criteria)
                    ->filterByPlayerItemRelatedByPlayerItem3Id($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerDecksRelatedByPlayerItem3IdPartial && count($collPlayerDecksRelatedByPlayerItem3Id)) {
                        $this->initPlayerDecksRelatedByPlayerItem3Id(false);

                        foreach ($collPlayerDecksRelatedByPlayerItem3Id as $obj) {
                            if (false == $this->collPlayerDecksRelatedByPlayerItem3Id->contains($obj)) {
                                $this->collPlayerDecksRelatedByPlayerItem3Id->append($obj);
                            }
                        }

                        $this->collPlayerDecksRelatedByPlayerItem3IdPartial = true;
                    }

                    return $collPlayerDecksRelatedByPlayerItem3Id;
                }

                if ($partial && $this->collPlayerDecksRelatedByPlayerItem3Id) {
                    foreach ($this->collPlayerDecksRelatedByPlayerItem3Id as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerDecksRelatedByPlayerItem3Id[] = $obj;
                        }
                    }
                }

                $this->collPlayerDecksRelatedByPlayerItem3Id = $collPlayerDecksRelatedByPlayerItem3Id;
                $this->collPlayerDecksRelatedByPlayerItem3IdPartial = false;
            }
        }

        return $this->collPlayerDecksRelatedByPlayerItem3Id;
    }

    /**
     * Sets a collection of ChildPlayerDeck objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerDecksRelatedByPlayerItem3Id A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function setPlayerDecksRelatedByPlayerItem3Id(Collection $playerDecksRelatedByPlayerItem3Id, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerDeck[] $playerDecksRelatedByPlayerItem3IdToDelete */
        $playerDecksRelatedByPlayerItem3IdToDelete = $this->getPlayerDecksRelatedByPlayerItem3Id(new Criteria(), $con)->diff($playerDecksRelatedByPlayerItem3Id);


        $this->playerDecksRelatedByPlayerItem3IdScheduledForDeletion = $playerDecksRelatedByPlayerItem3IdToDelete;

        foreach ($playerDecksRelatedByPlayerItem3IdToDelete as $playerDeckRelatedByPlayerItem3IdRemoved) {
            $playerDeckRelatedByPlayerItem3IdRemoved->setPlayerItemRelatedByPlayerItem3Id(null);
        }

        $this->collPlayerDecksRelatedByPlayerItem3Id = null;
        foreach ($playerDecksRelatedByPlayerItem3Id as $playerDeckRelatedByPlayerItem3Id) {
            $this->addPlayerDeckRelatedByPlayerItem3Id($playerDeckRelatedByPlayerItem3Id);
        }

        $this->collPlayerDecksRelatedByPlayerItem3Id = $playerDecksRelatedByPlayerItem3Id;
        $this->collPlayerDecksRelatedByPlayerItem3IdPartial = false;

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
    public function countPlayerDecksRelatedByPlayerItem3Id(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByPlayerItem3IdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByPlayerItem3Id || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByPlayerItem3Id) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerDecksRelatedByPlayerItem3Id());
            }

            $query = ChildPlayerDeckQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerItemRelatedByPlayerItem3Id($this)
                ->count($con);
        }

        return count($this->collPlayerDecksRelatedByPlayerItem3Id);
    }

    /**
     * Method called to associate a ChildPlayerDeck object to this object
     * through the ChildPlayerDeck foreign key attribute.
     *
     * @param  ChildPlayerDeck $l ChildPlayerDeck
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function addPlayerDeckRelatedByPlayerItem3Id(ChildPlayerDeck $l)
    {
        if ($this->collPlayerDecksRelatedByPlayerItem3Id === null) {
            $this->initPlayerDecksRelatedByPlayerItem3Id();
            $this->collPlayerDecksRelatedByPlayerItem3IdPartial = true;
        }

        if (!$this->collPlayerDecksRelatedByPlayerItem3Id->contains($l)) {
            $this->doAddPlayerDeckRelatedByPlayerItem3Id($l);

            if ($this->playerDecksRelatedByPlayerItem3IdScheduledForDeletion and $this->playerDecksRelatedByPlayerItem3IdScheduledForDeletion->contains($l)) {
                $this->playerDecksRelatedByPlayerItem3IdScheduledForDeletion->remove($this->playerDecksRelatedByPlayerItem3IdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerDeck $playerDeckRelatedByPlayerItem3Id The ChildPlayerDeck object to add.
     */
    protected function doAddPlayerDeckRelatedByPlayerItem3Id(ChildPlayerDeck $playerDeckRelatedByPlayerItem3Id)
    {
        $this->collPlayerDecksRelatedByPlayerItem3Id[]= $playerDeckRelatedByPlayerItem3Id;
        $playerDeckRelatedByPlayerItem3Id->setPlayerItemRelatedByPlayerItem3Id($this);
    }

    /**
     * @param  ChildPlayerDeck $playerDeckRelatedByPlayerItem3Id The ChildPlayerDeck object to remove.
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function removePlayerDeckRelatedByPlayerItem3Id(ChildPlayerDeck $playerDeckRelatedByPlayerItem3Id)
    {
        if ($this->getPlayerDecksRelatedByPlayerItem3Id()->contains($playerDeckRelatedByPlayerItem3Id)) {
            $pos = $this->collPlayerDecksRelatedByPlayerItem3Id->search($playerDeckRelatedByPlayerItem3Id);
            $this->collPlayerDecksRelatedByPlayerItem3Id->remove($pos);
            if (null === $this->playerDecksRelatedByPlayerItem3IdScheduledForDeletion) {
                $this->playerDecksRelatedByPlayerItem3IdScheduledForDeletion = clone $this->collPlayerDecksRelatedByPlayerItem3Id;
                $this->playerDecksRelatedByPlayerItem3IdScheduledForDeletion->clear();
            }
            $this->playerDecksRelatedByPlayerItem3IdScheduledForDeletion[]= $playerDeckRelatedByPlayerItem3Id;
            $playerDeckRelatedByPlayerItem3Id->setPlayerItemRelatedByPlayerItem3Id(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PlayerItem is new, it will return
     * an empty collection; or if this PlayerItem has previously
     * been saved, it will retrieve related PlayerDecksRelatedByPlayerItem3Id from storage.
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
    public function getPlayerDecksRelatedByPlayerItem3IdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerDeckQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerDecksRelatedByPlayerItem3Id($query, $con);
    }

    /**
     * Clears out the collPlayerDecksRelatedByPlayerItem4Id collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerDecksRelatedByPlayerItem4Id()
     */
    public function clearPlayerDecksRelatedByPlayerItem4Id()
    {
        $this->collPlayerDecksRelatedByPlayerItem4Id = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerDecksRelatedByPlayerItem4Id collection loaded partially.
     */
    public function resetPartialPlayerDecksRelatedByPlayerItem4Id($v = true)
    {
        $this->collPlayerDecksRelatedByPlayerItem4IdPartial = $v;
    }

    /**
     * Initializes the collPlayerDecksRelatedByPlayerItem4Id collection.
     *
     * By default this just sets the collPlayerDecksRelatedByPlayerItem4Id collection to an empty array (like clearcollPlayerDecksRelatedByPlayerItem4Id());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerDecksRelatedByPlayerItem4Id($overrideExisting = true)
    {
        if (null !== $this->collPlayerDecksRelatedByPlayerItem4Id && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerDeckTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerDecksRelatedByPlayerItem4Id = new $collectionClassName;
        $this->collPlayerDecksRelatedByPlayerItem4Id->setModel('\app\model\PlayerDeck');
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
    public function getPlayerDecksRelatedByPlayerItem4Id(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByPlayerItem4IdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByPlayerItem4Id || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByPlayerItem4Id) {
                // return empty collection
                $this->initPlayerDecksRelatedByPlayerItem4Id();
            } else {
                $collPlayerDecksRelatedByPlayerItem4Id = ChildPlayerDeckQuery::create(null, $criteria)
                    ->filterByPlayerItemRelatedByPlayerItem4Id($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerDecksRelatedByPlayerItem4IdPartial && count($collPlayerDecksRelatedByPlayerItem4Id)) {
                        $this->initPlayerDecksRelatedByPlayerItem4Id(false);

                        foreach ($collPlayerDecksRelatedByPlayerItem4Id as $obj) {
                            if (false == $this->collPlayerDecksRelatedByPlayerItem4Id->contains($obj)) {
                                $this->collPlayerDecksRelatedByPlayerItem4Id->append($obj);
                            }
                        }

                        $this->collPlayerDecksRelatedByPlayerItem4IdPartial = true;
                    }

                    return $collPlayerDecksRelatedByPlayerItem4Id;
                }

                if ($partial && $this->collPlayerDecksRelatedByPlayerItem4Id) {
                    foreach ($this->collPlayerDecksRelatedByPlayerItem4Id as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerDecksRelatedByPlayerItem4Id[] = $obj;
                        }
                    }
                }

                $this->collPlayerDecksRelatedByPlayerItem4Id = $collPlayerDecksRelatedByPlayerItem4Id;
                $this->collPlayerDecksRelatedByPlayerItem4IdPartial = false;
            }
        }

        return $this->collPlayerDecksRelatedByPlayerItem4Id;
    }

    /**
     * Sets a collection of ChildPlayerDeck objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerDecksRelatedByPlayerItem4Id A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function setPlayerDecksRelatedByPlayerItem4Id(Collection $playerDecksRelatedByPlayerItem4Id, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerDeck[] $playerDecksRelatedByPlayerItem4IdToDelete */
        $playerDecksRelatedByPlayerItem4IdToDelete = $this->getPlayerDecksRelatedByPlayerItem4Id(new Criteria(), $con)->diff($playerDecksRelatedByPlayerItem4Id);


        $this->playerDecksRelatedByPlayerItem4IdScheduledForDeletion = $playerDecksRelatedByPlayerItem4IdToDelete;

        foreach ($playerDecksRelatedByPlayerItem4IdToDelete as $playerDeckRelatedByPlayerItem4IdRemoved) {
            $playerDeckRelatedByPlayerItem4IdRemoved->setPlayerItemRelatedByPlayerItem4Id(null);
        }

        $this->collPlayerDecksRelatedByPlayerItem4Id = null;
        foreach ($playerDecksRelatedByPlayerItem4Id as $playerDeckRelatedByPlayerItem4Id) {
            $this->addPlayerDeckRelatedByPlayerItem4Id($playerDeckRelatedByPlayerItem4Id);
        }

        $this->collPlayerDecksRelatedByPlayerItem4Id = $playerDecksRelatedByPlayerItem4Id;
        $this->collPlayerDecksRelatedByPlayerItem4IdPartial = false;

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
    public function countPlayerDecksRelatedByPlayerItem4Id(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByPlayerItem4IdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByPlayerItem4Id || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByPlayerItem4Id) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerDecksRelatedByPlayerItem4Id());
            }

            $query = ChildPlayerDeckQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerItemRelatedByPlayerItem4Id($this)
                ->count($con);
        }

        return count($this->collPlayerDecksRelatedByPlayerItem4Id);
    }

    /**
     * Method called to associate a ChildPlayerDeck object to this object
     * through the ChildPlayerDeck foreign key attribute.
     *
     * @param  ChildPlayerDeck $l ChildPlayerDeck
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function addPlayerDeckRelatedByPlayerItem4Id(ChildPlayerDeck $l)
    {
        if ($this->collPlayerDecksRelatedByPlayerItem4Id === null) {
            $this->initPlayerDecksRelatedByPlayerItem4Id();
            $this->collPlayerDecksRelatedByPlayerItem4IdPartial = true;
        }

        if (!$this->collPlayerDecksRelatedByPlayerItem4Id->contains($l)) {
            $this->doAddPlayerDeckRelatedByPlayerItem4Id($l);

            if ($this->playerDecksRelatedByPlayerItem4IdScheduledForDeletion and $this->playerDecksRelatedByPlayerItem4IdScheduledForDeletion->contains($l)) {
                $this->playerDecksRelatedByPlayerItem4IdScheduledForDeletion->remove($this->playerDecksRelatedByPlayerItem4IdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerDeck $playerDeckRelatedByPlayerItem4Id The ChildPlayerDeck object to add.
     */
    protected function doAddPlayerDeckRelatedByPlayerItem4Id(ChildPlayerDeck $playerDeckRelatedByPlayerItem4Id)
    {
        $this->collPlayerDecksRelatedByPlayerItem4Id[]= $playerDeckRelatedByPlayerItem4Id;
        $playerDeckRelatedByPlayerItem4Id->setPlayerItemRelatedByPlayerItem4Id($this);
    }

    /**
     * @param  ChildPlayerDeck $playerDeckRelatedByPlayerItem4Id The ChildPlayerDeck object to remove.
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function removePlayerDeckRelatedByPlayerItem4Id(ChildPlayerDeck $playerDeckRelatedByPlayerItem4Id)
    {
        if ($this->getPlayerDecksRelatedByPlayerItem4Id()->contains($playerDeckRelatedByPlayerItem4Id)) {
            $pos = $this->collPlayerDecksRelatedByPlayerItem4Id->search($playerDeckRelatedByPlayerItem4Id);
            $this->collPlayerDecksRelatedByPlayerItem4Id->remove($pos);
            if (null === $this->playerDecksRelatedByPlayerItem4IdScheduledForDeletion) {
                $this->playerDecksRelatedByPlayerItem4IdScheduledForDeletion = clone $this->collPlayerDecksRelatedByPlayerItem4Id;
                $this->playerDecksRelatedByPlayerItem4IdScheduledForDeletion->clear();
            }
            $this->playerDecksRelatedByPlayerItem4IdScheduledForDeletion[]= $playerDeckRelatedByPlayerItem4Id;
            $playerDeckRelatedByPlayerItem4Id->setPlayerItemRelatedByPlayerItem4Id(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PlayerItem is new, it will return
     * an empty collection; or if this PlayerItem has previously
     * been saved, it will retrieve related PlayerDecksRelatedByPlayerItem4Id from storage.
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
    public function getPlayerDecksRelatedByPlayerItem4IdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerDeckQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerDecksRelatedByPlayerItem4Id($query, $con);
    }

    /**
     * Clears out the collPlayerDecksRelatedByPlayerItem5Id collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerDecksRelatedByPlayerItem5Id()
     */
    public function clearPlayerDecksRelatedByPlayerItem5Id()
    {
        $this->collPlayerDecksRelatedByPlayerItem5Id = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerDecksRelatedByPlayerItem5Id collection loaded partially.
     */
    public function resetPartialPlayerDecksRelatedByPlayerItem5Id($v = true)
    {
        $this->collPlayerDecksRelatedByPlayerItem5IdPartial = $v;
    }

    /**
     * Initializes the collPlayerDecksRelatedByPlayerItem5Id collection.
     *
     * By default this just sets the collPlayerDecksRelatedByPlayerItem5Id collection to an empty array (like clearcollPlayerDecksRelatedByPlayerItem5Id());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerDecksRelatedByPlayerItem5Id($overrideExisting = true)
    {
        if (null !== $this->collPlayerDecksRelatedByPlayerItem5Id && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerDeckTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerDecksRelatedByPlayerItem5Id = new $collectionClassName;
        $this->collPlayerDecksRelatedByPlayerItem5Id->setModel('\app\model\PlayerDeck');
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
    public function getPlayerDecksRelatedByPlayerItem5Id(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByPlayerItem5IdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByPlayerItem5Id || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByPlayerItem5Id) {
                // return empty collection
                $this->initPlayerDecksRelatedByPlayerItem5Id();
            } else {
                $collPlayerDecksRelatedByPlayerItem5Id = ChildPlayerDeckQuery::create(null, $criteria)
                    ->filterByPlayerItemRelatedByPlayerItem5Id($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerDecksRelatedByPlayerItem5IdPartial && count($collPlayerDecksRelatedByPlayerItem5Id)) {
                        $this->initPlayerDecksRelatedByPlayerItem5Id(false);

                        foreach ($collPlayerDecksRelatedByPlayerItem5Id as $obj) {
                            if (false == $this->collPlayerDecksRelatedByPlayerItem5Id->contains($obj)) {
                                $this->collPlayerDecksRelatedByPlayerItem5Id->append($obj);
                            }
                        }

                        $this->collPlayerDecksRelatedByPlayerItem5IdPartial = true;
                    }

                    return $collPlayerDecksRelatedByPlayerItem5Id;
                }

                if ($partial && $this->collPlayerDecksRelatedByPlayerItem5Id) {
                    foreach ($this->collPlayerDecksRelatedByPlayerItem5Id as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerDecksRelatedByPlayerItem5Id[] = $obj;
                        }
                    }
                }

                $this->collPlayerDecksRelatedByPlayerItem5Id = $collPlayerDecksRelatedByPlayerItem5Id;
                $this->collPlayerDecksRelatedByPlayerItem5IdPartial = false;
            }
        }

        return $this->collPlayerDecksRelatedByPlayerItem5Id;
    }

    /**
     * Sets a collection of ChildPlayerDeck objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerDecksRelatedByPlayerItem5Id A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function setPlayerDecksRelatedByPlayerItem5Id(Collection $playerDecksRelatedByPlayerItem5Id, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerDeck[] $playerDecksRelatedByPlayerItem5IdToDelete */
        $playerDecksRelatedByPlayerItem5IdToDelete = $this->getPlayerDecksRelatedByPlayerItem5Id(new Criteria(), $con)->diff($playerDecksRelatedByPlayerItem5Id);


        $this->playerDecksRelatedByPlayerItem5IdScheduledForDeletion = $playerDecksRelatedByPlayerItem5IdToDelete;

        foreach ($playerDecksRelatedByPlayerItem5IdToDelete as $playerDeckRelatedByPlayerItem5IdRemoved) {
            $playerDeckRelatedByPlayerItem5IdRemoved->setPlayerItemRelatedByPlayerItem5Id(null);
        }

        $this->collPlayerDecksRelatedByPlayerItem5Id = null;
        foreach ($playerDecksRelatedByPlayerItem5Id as $playerDeckRelatedByPlayerItem5Id) {
            $this->addPlayerDeckRelatedByPlayerItem5Id($playerDeckRelatedByPlayerItem5Id);
        }

        $this->collPlayerDecksRelatedByPlayerItem5Id = $playerDecksRelatedByPlayerItem5Id;
        $this->collPlayerDecksRelatedByPlayerItem5IdPartial = false;

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
    public function countPlayerDecksRelatedByPlayerItem5Id(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByPlayerItem5IdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByPlayerItem5Id || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByPlayerItem5Id) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerDecksRelatedByPlayerItem5Id());
            }

            $query = ChildPlayerDeckQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerItemRelatedByPlayerItem5Id($this)
                ->count($con);
        }

        return count($this->collPlayerDecksRelatedByPlayerItem5Id);
    }

    /**
     * Method called to associate a ChildPlayerDeck object to this object
     * through the ChildPlayerDeck foreign key attribute.
     *
     * @param  ChildPlayerDeck $l ChildPlayerDeck
     * @return $this|\app\model\PlayerItem The current object (for fluent API support)
     */
    public function addPlayerDeckRelatedByPlayerItem5Id(ChildPlayerDeck $l)
    {
        if ($this->collPlayerDecksRelatedByPlayerItem5Id === null) {
            $this->initPlayerDecksRelatedByPlayerItem5Id();
            $this->collPlayerDecksRelatedByPlayerItem5IdPartial = true;
        }

        if (!$this->collPlayerDecksRelatedByPlayerItem5Id->contains($l)) {
            $this->doAddPlayerDeckRelatedByPlayerItem5Id($l);

            if ($this->playerDecksRelatedByPlayerItem5IdScheduledForDeletion and $this->playerDecksRelatedByPlayerItem5IdScheduledForDeletion->contains($l)) {
                $this->playerDecksRelatedByPlayerItem5IdScheduledForDeletion->remove($this->playerDecksRelatedByPlayerItem5IdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerDeck $playerDeckRelatedByPlayerItem5Id The ChildPlayerDeck object to add.
     */
    protected function doAddPlayerDeckRelatedByPlayerItem5Id(ChildPlayerDeck $playerDeckRelatedByPlayerItem5Id)
    {
        $this->collPlayerDecksRelatedByPlayerItem5Id[]= $playerDeckRelatedByPlayerItem5Id;
        $playerDeckRelatedByPlayerItem5Id->setPlayerItemRelatedByPlayerItem5Id($this);
    }

    /**
     * @param  ChildPlayerDeck $playerDeckRelatedByPlayerItem5Id The ChildPlayerDeck object to remove.
     * @return $this|ChildPlayerItem The current object (for fluent API support)
     */
    public function removePlayerDeckRelatedByPlayerItem5Id(ChildPlayerDeck $playerDeckRelatedByPlayerItem5Id)
    {
        if ($this->getPlayerDecksRelatedByPlayerItem5Id()->contains($playerDeckRelatedByPlayerItem5Id)) {
            $pos = $this->collPlayerDecksRelatedByPlayerItem5Id->search($playerDeckRelatedByPlayerItem5Id);
            $this->collPlayerDecksRelatedByPlayerItem5Id->remove($pos);
            if (null === $this->playerDecksRelatedByPlayerItem5IdScheduledForDeletion) {
                $this->playerDecksRelatedByPlayerItem5IdScheduledForDeletion = clone $this->collPlayerDecksRelatedByPlayerItem5Id;
                $this->playerDecksRelatedByPlayerItem5IdScheduledForDeletion->clear();
            }
            $this->playerDecksRelatedByPlayerItem5IdScheduledForDeletion[]= $playerDeckRelatedByPlayerItem5Id;
            $playerDeckRelatedByPlayerItem5Id->setPlayerItemRelatedByPlayerItem5Id(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PlayerItem is new, it will return
     * an empty collection; or if this PlayerItem has previously
     * been saved, it will retrieve related PlayerDecksRelatedByPlayerItem5Id from storage.
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
    public function getPlayerDecksRelatedByPlayerItem5IdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerDeckQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerDecksRelatedByPlayerItem5Id($query, $con);
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
            if ($this->collPlayerDecksRelatedByPlayerItem1Id) {
                foreach ($this->collPlayerDecksRelatedByPlayerItem1Id as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerDecksRelatedByPlayerItem2Id) {
                foreach ($this->collPlayerDecksRelatedByPlayerItem2Id as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerDecksRelatedByPlayerItem3Id) {
                foreach ($this->collPlayerDecksRelatedByPlayerItem3Id as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerDecksRelatedByPlayerItem4Id) {
                foreach ($this->collPlayerDecksRelatedByPlayerItem4Id as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerDecksRelatedByPlayerItem5Id) {
                foreach ($this->collPlayerDecksRelatedByPlayerItem5Id as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collPlayerDecksRelatedByPlayerItem1Id = null;
        $this->collPlayerDecksRelatedByPlayerItem2Id = null;
        $this->collPlayerDecksRelatedByPlayerItem3Id = null;
        $this->collPlayerDecksRelatedByPlayerItem4Id = null;
        $this->collPlayerDecksRelatedByPlayerItem5Id = null;
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
