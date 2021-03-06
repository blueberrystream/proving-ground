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
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;
use app\model\Player as ChildPlayer;
use app\model\PlayerDeck as ChildPlayerDeck;
use app\model\PlayerDeckQuery as ChildPlayerDeckQuery;
use app\model\PlayerQuery as ChildPlayerQuery;
use app\model\Proprium as ChildProprium;
use app\model\PropriumQuery as ChildPropriumQuery;
use app\model\Map\PlayerDeckTableMap;

/**
 * Base class that represents a row from the 'player_deck' table.
 *
 *
 *
 * @package    propel.generator.app.model.Base
 */
abstract class PlayerDeck implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\app\\model\\Map\\PlayerDeckTableMap';


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
     * The value for the first_proprium_id field.
     *
     * @var        int
     */
    protected $first_proprium_id;

    /**
     * The value for the second_proprium_id field.
     *
     * @var        int
     */
    protected $second_proprium_id;

    /**
     * The value for the third_proprium_id field.
     *
     * @var        int
     */
    protected $third_proprium_id;

    /**
     * The value for the fourth_proprium_id field.
     *
     * @var        int
     */
    protected $fourth_proprium_id;

    /**
     * The value for the fifth_proprium_id field.
     *
     * @var        int
     */
    protected $fifth_proprium_id;

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
     * @var        ChildProprium
     */
    protected $aPropriumRelatedByFirstPropriumId;

    /**
     * @var        ChildProprium
     */
    protected $aPropriumRelatedBySecondPropriumId;

    /**
     * @var        ChildProprium
     */
    protected $aPropriumRelatedByThirdPropriumId;

    /**
     * @var        ChildProprium
     */
    protected $aPropriumRelatedByFourthPropriumId;

    /**
     * @var        ChildProprium
     */
    protected $aPropriumRelatedByFifthPropriumId;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of app\model\Base\PlayerDeck object.
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
     * Compares this with another <code>PlayerDeck</code> instance.  If
     * <code>obj</code> is an instance of <code>PlayerDeck</code>, delegates to
     * <code>equals(PlayerDeck)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|PlayerDeck The current object, for fluid interface
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
     * Get the [first_proprium_id] column value.
     *
     * @return int
     */
    public function getFirstPropriumId()
    {
        return $this->first_proprium_id;
    }

    /**
     * Get the [second_proprium_id] column value.
     *
     * @return int
     */
    public function getSecondPropriumId()
    {
        return $this->second_proprium_id;
    }

    /**
     * Get the [third_proprium_id] column value.
     *
     * @return int
     */
    public function getThirdPropriumId()
    {
        return $this->third_proprium_id;
    }

    /**
     * Get the [fourth_proprium_id] column value.
     *
     * @return int
     */
    public function getFourthPropriumId()
    {
        return $this->fourth_proprium_id;
    }

    /**
     * Get the [fifth_proprium_id] column value.
     *
     * @return int
     */
    public function getFifthPropriumId()
    {
        return $this->fifth_proprium_id;
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
     * @return $this|\app\model\PlayerDeck The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[PlayerDeckTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [player_id] column.
     *
     * @param int $v new value
     * @return $this|\app\model\PlayerDeck The current object (for fluent API support)
     */
    public function setPlayerId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->player_id !== $v) {
            $this->player_id = $v;
            $this->modifiedColumns[PlayerDeckTableMap::COL_PLAYER_ID] = true;
        }

        if ($this->aPlayer !== null && $this->aPlayer->getId() !== $v) {
            $this->aPlayer = null;
        }

        return $this;
    } // setPlayerId()

    /**
     * Set the value of [first_proprium_id] column.
     *
     * @param int $v new value
     * @return $this|\app\model\PlayerDeck The current object (for fluent API support)
     */
    public function setFirstPropriumId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->first_proprium_id !== $v) {
            $this->first_proprium_id = $v;
            $this->modifiedColumns[PlayerDeckTableMap::COL_FIRST_PROPRIUM_ID] = true;
        }

        if ($this->aPropriumRelatedByFirstPropriumId !== null && $this->aPropriumRelatedByFirstPropriumId->getId() !== $v) {
            $this->aPropriumRelatedByFirstPropriumId = null;
        }

        return $this;
    } // setFirstPropriumId()

    /**
     * Set the value of [second_proprium_id] column.
     *
     * @param int $v new value
     * @return $this|\app\model\PlayerDeck The current object (for fluent API support)
     */
    public function setSecondPropriumId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->second_proprium_id !== $v) {
            $this->second_proprium_id = $v;
            $this->modifiedColumns[PlayerDeckTableMap::COL_SECOND_PROPRIUM_ID] = true;
        }

        if ($this->aPropriumRelatedBySecondPropriumId !== null && $this->aPropriumRelatedBySecondPropriumId->getId() !== $v) {
            $this->aPropriumRelatedBySecondPropriumId = null;
        }

        return $this;
    } // setSecondPropriumId()

    /**
     * Set the value of [third_proprium_id] column.
     *
     * @param int $v new value
     * @return $this|\app\model\PlayerDeck The current object (for fluent API support)
     */
    public function setThirdPropriumId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->third_proprium_id !== $v) {
            $this->third_proprium_id = $v;
            $this->modifiedColumns[PlayerDeckTableMap::COL_THIRD_PROPRIUM_ID] = true;
        }

        if ($this->aPropriumRelatedByThirdPropriumId !== null && $this->aPropriumRelatedByThirdPropriumId->getId() !== $v) {
            $this->aPropriumRelatedByThirdPropriumId = null;
        }

        return $this;
    } // setThirdPropriumId()

    /**
     * Set the value of [fourth_proprium_id] column.
     *
     * @param int $v new value
     * @return $this|\app\model\PlayerDeck The current object (for fluent API support)
     */
    public function setFourthPropriumId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->fourth_proprium_id !== $v) {
            $this->fourth_proprium_id = $v;
            $this->modifiedColumns[PlayerDeckTableMap::COL_FOURTH_PROPRIUM_ID] = true;
        }

        if ($this->aPropriumRelatedByFourthPropriumId !== null && $this->aPropriumRelatedByFourthPropriumId->getId() !== $v) {
            $this->aPropriumRelatedByFourthPropriumId = null;
        }

        return $this;
    } // setFourthPropriumId()

    /**
     * Set the value of [fifth_proprium_id] column.
     *
     * @param int $v new value
     * @return $this|\app\model\PlayerDeck The current object (for fluent API support)
     */
    public function setFifthPropriumId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->fifth_proprium_id !== $v) {
            $this->fifth_proprium_id = $v;
            $this->modifiedColumns[PlayerDeckTableMap::COL_FIFTH_PROPRIUM_ID] = true;
        }

        if ($this->aPropriumRelatedByFifthPropriumId !== null && $this->aPropriumRelatedByFifthPropriumId->getId() !== $v) {
            $this->aPropriumRelatedByFifthPropriumId = null;
        }

        return $this;
    } // setFifthPropriumId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\app\model\PlayerDeck The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PlayerDeckTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\app\model\PlayerDeck The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PlayerDeckTableMap::COL_UPDATED_AT] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PlayerDeckTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PlayerDeckTableMap::translateFieldName('PlayerId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->player_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : PlayerDeckTableMap::translateFieldName('FirstPropriumId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->first_proprium_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : PlayerDeckTableMap::translateFieldName('SecondPropriumId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->second_proprium_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : PlayerDeckTableMap::translateFieldName('ThirdPropriumId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->third_proprium_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : PlayerDeckTableMap::translateFieldName('FourthPropriumId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->fourth_proprium_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : PlayerDeckTableMap::translateFieldName('FifthPropriumId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->fifth_proprium_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : PlayerDeckTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : PlayerDeckTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = PlayerDeckTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\app\\model\\PlayerDeck'), 0, $e);
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
        if ($this->aPropriumRelatedByFirstPropriumId !== null && $this->first_proprium_id !== $this->aPropriumRelatedByFirstPropriumId->getId()) {
            $this->aPropriumRelatedByFirstPropriumId = null;
        }
        if ($this->aPropriumRelatedBySecondPropriumId !== null && $this->second_proprium_id !== $this->aPropriumRelatedBySecondPropriumId->getId()) {
            $this->aPropriumRelatedBySecondPropriumId = null;
        }
        if ($this->aPropriumRelatedByThirdPropriumId !== null && $this->third_proprium_id !== $this->aPropriumRelatedByThirdPropriumId->getId()) {
            $this->aPropriumRelatedByThirdPropriumId = null;
        }
        if ($this->aPropriumRelatedByFourthPropriumId !== null && $this->fourth_proprium_id !== $this->aPropriumRelatedByFourthPropriumId->getId()) {
            $this->aPropriumRelatedByFourthPropriumId = null;
        }
        if ($this->aPropriumRelatedByFifthPropriumId !== null && $this->fifth_proprium_id !== $this->aPropriumRelatedByFifthPropriumId->getId()) {
            $this->aPropriumRelatedByFifthPropriumId = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(PlayerDeckTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildPlayerDeckQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPlayer = null;
            $this->aPropriumRelatedByFirstPropriumId = null;
            $this->aPropriumRelatedBySecondPropriumId = null;
            $this->aPropriumRelatedByThirdPropriumId = null;
            $this->aPropriumRelatedByFourthPropriumId = null;
            $this->aPropriumRelatedByFifthPropriumId = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see PlayerDeck::setDeleted()
     * @see PlayerDeck::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerDeckTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildPlayerDeckQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerDeckTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(PlayerDeckTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
                if (!$this->isColumnModified(PlayerDeckTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PlayerDeckTableMap::COL_UPDATED_AT)) {
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
                PlayerDeckTableMap::addInstanceToPool($this);
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

            if ($this->aPropriumRelatedByFirstPropriumId !== null) {
                if ($this->aPropriumRelatedByFirstPropriumId->isModified() || $this->aPropriumRelatedByFirstPropriumId->isNew()) {
                    $affectedRows += $this->aPropriumRelatedByFirstPropriumId->save($con);
                }
                $this->setPropriumRelatedByFirstPropriumId($this->aPropriumRelatedByFirstPropriumId);
            }

            if ($this->aPropriumRelatedBySecondPropriumId !== null) {
                if ($this->aPropriumRelatedBySecondPropriumId->isModified() || $this->aPropriumRelatedBySecondPropriumId->isNew()) {
                    $affectedRows += $this->aPropriumRelatedBySecondPropriumId->save($con);
                }
                $this->setPropriumRelatedBySecondPropriumId($this->aPropriumRelatedBySecondPropriumId);
            }

            if ($this->aPropriumRelatedByThirdPropriumId !== null) {
                if ($this->aPropriumRelatedByThirdPropriumId->isModified() || $this->aPropriumRelatedByThirdPropriumId->isNew()) {
                    $affectedRows += $this->aPropriumRelatedByThirdPropriumId->save($con);
                }
                $this->setPropriumRelatedByThirdPropriumId($this->aPropriumRelatedByThirdPropriumId);
            }

            if ($this->aPropriumRelatedByFourthPropriumId !== null) {
                if ($this->aPropriumRelatedByFourthPropriumId->isModified() || $this->aPropriumRelatedByFourthPropriumId->isNew()) {
                    $affectedRows += $this->aPropriumRelatedByFourthPropriumId->save($con);
                }
                $this->setPropriumRelatedByFourthPropriumId($this->aPropriumRelatedByFourthPropriumId);
            }

            if ($this->aPropriumRelatedByFifthPropriumId !== null) {
                if ($this->aPropriumRelatedByFifthPropriumId->isModified() || $this->aPropriumRelatedByFifthPropriumId->isNew()) {
                    $affectedRows += $this->aPropriumRelatedByFifthPropriumId->save($con);
                }
                $this->setPropriumRelatedByFifthPropriumId($this->aPropriumRelatedByFifthPropriumId);
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

        $this->modifiedColumns[PlayerDeckTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PlayerDeckTableMap::COL_ID . ')');
        }
        if (null === $this->id) {
            try {
                $dataFetcher = $con->query("SELECT nextval('player_deck_id_seq')");
                $this->id = (int) $dataFetcher->fetchColumn();
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', 0, $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PlayerDeckTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(PlayerDeckTableMap::COL_PLAYER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'player_id';
        }
        if ($this->isColumnModified(PlayerDeckTableMap::COL_FIRST_PROPRIUM_ID)) {
            $modifiedColumns[':p' . $index++]  = 'first_proprium_id';
        }
        if ($this->isColumnModified(PlayerDeckTableMap::COL_SECOND_PROPRIUM_ID)) {
            $modifiedColumns[':p' . $index++]  = 'second_proprium_id';
        }
        if ($this->isColumnModified(PlayerDeckTableMap::COL_THIRD_PROPRIUM_ID)) {
            $modifiedColumns[':p' . $index++]  = 'third_proprium_id';
        }
        if ($this->isColumnModified(PlayerDeckTableMap::COL_FOURTH_PROPRIUM_ID)) {
            $modifiedColumns[':p' . $index++]  = 'fourth_proprium_id';
        }
        if ($this->isColumnModified(PlayerDeckTableMap::COL_FIFTH_PROPRIUM_ID)) {
            $modifiedColumns[':p' . $index++]  = 'fifth_proprium_id';
        }
        if ($this->isColumnModified(PlayerDeckTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(PlayerDeckTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO player_deck (%s) VALUES (%s)',
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
                    case 'first_proprium_id':
                        $stmt->bindValue($identifier, $this->first_proprium_id, PDO::PARAM_INT);
                        break;
                    case 'second_proprium_id':
                        $stmt->bindValue($identifier, $this->second_proprium_id, PDO::PARAM_INT);
                        break;
                    case 'third_proprium_id':
                        $stmt->bindValue($identifier, $this->third_proprium_id, PDO::PARAM_INT);
                        break;
                    case 'fourth_proprium_id':
                        $stmt->bindValue($identifier, $this->fourth_proprium_id, PDO::PARAM_INT);
                        break;
                    case 'fifth_proprium_id':
                        $stmt->bindValue($identifier, $this->fifth_proprium_id, PDO::PARAM_INT);
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
        $pos = PlayerDeckTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getFirstPropriumId();
                break;
            case 3:
                return $this->getSecondPropriumId();
                break;
            case 4:
                return $this->getThirdPropriumId();
                break;
            case 5:
                return $this->getFourthPropriumId();
                break;
            case 6:
                return $this->getFifthPropriumId();
                break;
            case 7:
                return $this->getCreatedAt();
                break;
            case 8:
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

        if (isset($alreadyDumpedObjects['PlayerDeck'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PlayerDeck'][$this->hashCode()] = true;
        $keys = PlayerDeckTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getPlayerId(),
            $keys[2] => $this->getFirstPropriumId(),
            $keys[3] => $this->getSecondPropriumId(),
            $keys[4] => $this->getThirdPropriumId(),
            $keys[5] => $this->getFourthPropriumId(),
            $keys[6] => $this->getFifthPropriumId(),
            $keys[7] => $this->getCreatedAt(),
            $keys[8] => $this->getUpdatedAt(),
        );
        if ($result[$keys[7]] instanceof \DateTimeInterface) {
            $result[$keys[7]] = $result[$keys[7]]->format('c');
        }

        if ($result[$keys[8]] instanceof \DateTimeInterface) {
            $result[$keys[8]] = $result[$keys[8]]->format('c');
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
            if (null !== $this->aPropriumRelatedByFirstPropriumId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'proprium';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'proprium';
                        break;
                    default:
                        $key = 'Proprium';
                }

                $result[$key] = $this->aPropriumRelatedByFirstPropriumId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPropriumRelatedBySecondPropriumId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'proprium';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'proprium';
                        break;
                    default:
                        $key = 'Proprium';
                }

                $result[$key] = $this->aPropriumRelatedBySecondPropriumId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPropriumRelatedByThirdPropriumId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'proprium';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'proprium';
                        break;
                    default:
                        $key = 'Proprium';
                }

                $result[$key] = $this->aPropriumRelatedByThirdPropriumId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPropriumRelatedByFourthPropriumId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'proprium';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'proprium';
                        break;
                    default:
                        $key = 'Proprium';
                }

                $result[$key] = $this->aPropriumRelatedByFourthPropriumId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPropriumRelatedByFifthPropriumId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'proprium';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'proprium';
                        break;
                    default:
                        $key = 'Proprium';
                }

                $result[$key] = $this->aPropriumRelatedByFifthPropriumId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
     * @return $this|\app\model\PlayerDeck
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PlayerDeckTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\app\model\PlayerDeck
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
                $this->setFirstPropriumId($value);
                break;
            case 3:
                $this->setSecondPropriumId($value);
                break;
            case 4:
                $this->setThirdPropriumId($value);
                break;
            case 5:
                $this->setFourthPropriumId($value);
                break;
            case 6:
                $this->setFifthPropriumId($value);
                break;
            case 7:
                $this->setCreatedAt($value);
                break;
            case 8:
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
        $keys = PlayerDeckTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setPlayerId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setFirstPropriumId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setSecondPropriumId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setThirdPropriumId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setFourthPropriumId($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setFifthPropriumId($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setCreatedAt($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setUpdatedAt($arr[$keys[8]]);
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
     * @return $this|\app\model\PlayerDeck The current object, for fluid interface
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
        $criteria = new Criteria(PlayerDeckTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PlayerDeckTableMap::COL_ID)) {
            $criteria->add(PlayerDeckTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(PlayerDeckTableMap::COL_PLAYER_ID)) {
            $criteria->add(PlayerDeckTableMap::COL_PLAYER_ID, $this->player_id);
        }
        if ($this->isColumnModified(PlayerDeckTableMap::COL_FIRST_PROPRIUM_ID)) {
            $criteria->add(PlayerDeckTableMap::COL_FIRST_PROPRIUM_ID, $this->first_proprium_id);
        }
        if ($this->isColumnModified(PlayerDeckTableMap::COL_SECOND_PROPRIUM_ID)) {
            $criteria->add(PlayerDeckTableMap::COL_SECOND_PROPRIUM_ID, $this->second_proprium_id);
        }
        if ($this->isColumnModified(PlayerDeckTableMap::COL_THIRD_PROPRIUM_ID)) {
            $criteria->add(PlayerDeckTableMap::COL_THIRD_PROPRIUM_ID, $this->third_proprium_id);
        }
        if ($this->isColumnModified(PlayerDeckTableMap::COL_FOURTH_PROPRIUM_ID)) {
            $criteria->add(PlayerDeckTableMap::COL_FOURTH_PROPRIUM_ID, $this->fourth_proprium_id);
        }
        if ($this->isColumnModified(PlayerDeckTableMap::COL_FIFTH_PROPRIUM_ID)) {
            $criteria->add(PlayerDeckTableMap::COL_FIFTH_PROPRIUM_ID, $this->fifth_proprium_id);
        }
        if ($this->isColumnModified(PlayerDeckTableMap::COL_CREATED_AT)) {
            $criteria->add(PlayerDeckTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(PlayerDeckTableMap::COL_UPDATED_AT)) {
            $criteria->add(PlayerDeckTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildPlayerDeckQuery::create();
        $criteria->add(PlayerDeckTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \app\model\PlayerDeck (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPlayerId($this->getPlayerId());
        $copyObj->setFirstPropriumId($this->getFirstPropriumId());
        $copyObj->setSecondPropriumId($this->getSecondPropriumId());
        $copyObj->setThirdPropriumId($this->getThirdPropriumId());
        $copyObj->setFourthPropriumId($this->getFourthPropriumId());
        $copyObj->setFifthPropriumId($this->getFifthPropriumId());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
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
     * @return \app\model\PlayerDeck Clone of current object.
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
     * @return $this|\app\model\PlayerDeck The current object (for fluent API support)
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
            $v->addPlayerDeck($this);
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
                $this->aPlayer->addPlayerDecks($this);
             */
        }

        return $this->aPlayer;
    }

    /**
     * Declares an association between this object and a ChildProprium object.
     *
     * @param  ChildProprium $v
     * @return $this|\app\model\PlayerDeck The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPropriumRelatedByFirstPropriumId(ChildProprium $v = null)
    {
        if ($v === null) {
            $this->setFirstPropriumId(NULL);
        } else {
            $this->setFirstPropriumId($v->getId());
        }

        $this->aPropriumRelatedByFirstPropriumId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildProprium object, it will not be re-added.
        if ($v !== null) {
            $v->addPlayerDeckRelatedByFirstPropriumId($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildProprium object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildProprium The associated ChildProprium object.
     * @throws PropelException
     */
    public function getPropriumRelatedByFirstPropriumId(ConnectionInterface $con = null)
    {
        if ($this->aPropriumRelatedByFirstPropriumId === null && ($this->first_proprium_id !== null)) {
            $this->aPropriumRelatedByFirstPropriumId = ChildPropriumQuery::create()->findPk($this->first_proprium_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPropriumRelatedByFirstPropriumId->addPlayerDecksRelatedByFirstPropriumId($this);
             */
        }

        return $this->aPropriumRelatedByFirstPropriumId;
    }

    /**
     * Declares an association between this object and a ChildProprium object.
     *
     * @param  ChildProprium $v
     * @return $this|\app\model\PlayerDeck The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPropriumRelatedBySecondPropriumId(ChildProprium $v = null)
    {
        if ($v === null) {
            $this->setSecondPropriumId(NULL);
        } else {
            $this->setSecondPropriumId($v->getId());
        }

        $this->aPropriumRelatedBySecondPropriumId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildProprium object, it will not be re-added.
        if ($v !== null) {
            $v->addPlayerDeckRelatedBySecondPropriumId($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildProprium object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildProprium The associated ChildProprium object.
     * @throws PropelException
     */
    public function getPropriumRelatedBySecondPropriumId(ConnectionInterface $con = null)
    {
        if ($this->aPropriumRelatedBySecondPropriumId === null && ($this->second_proprium_id !== null)) {
            $this->aPropriumRelatedBySecondPropriumId = ChildPropriumQuery::create()->findPk($this->second_proprium_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPropriumRelatedBySecondPropriumId->addPlayerDecksRelatedBySecondPropriumId($this);
             */
        }

        return $this->aPropriumRelatedBySecondPropriumId;
    }

    /**
     * Declares an association between this object and a ChildProprium object.
     *
     * @param  ChildProprium $v
     * @return $this|\app\model\PlayerDeck The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPropriumRelatedByThirdPropriumId(ChildProprium $v = null)
    {
        if ($v === null) {
            $this->setThirdPropriumId(NULL);
        } else {
            $this->setThirdPropriumId($v->getId());
        }

        $this->aPropriumRelatedByThirdPropriumId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildProprium object, it will not be re-added.
        if ($v !== null) {
            $v->addPlayerDeckRelatedByThirdPropriumId($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildProprium object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildProprium The associated ChildProprium object.
     * @throws PropelException
     */
    public function getPropriumRelatedByThirdPropriumId(ConnectionInterface $con = null)
    {
        if ($this->aPropriumRelatedByThirdPropriumId === null && ($this->third_proprium_id !== null)) {
            $this->aPropriumRelatedByThirdPropriumId = ChildPropriumQuery::create()->findPk($this->third_proprium_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPropriumRelatedByThirdPropriumId->addPlayerDecksRelatedByThirdPropriumId($this);
             */
        }

        return $this->aPropriumRelatedByThirdPropriumId;
    }

    /**
     * Declares an association between this object and a ChildProprium object.
     *
     * @param  ChildProprium $v
     * @return $this|\app\model\PlayerDeck The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPropriumRelatedByFourthPropriumId(ChildProprium $v = null)
    {
        if ($v === null) {
            $this->setFourthPropriumId(NULL);
        } else {
            $this->setFourthPropriumId($v->getId());
        }

        $this->aPropriumRelatedByFourthPropriumId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildProprium object, it will not be re-added.
        if ($v !== null) {
            $v->addPlayerDeckRelatedByFourthPropriumId($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildProprium object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildProprium The associated ChildProprium object.
     * @throws PropelException
     */
    public function getPropriumRelatedByFourthPropriumId(ConnectionInterface $con = null)
    {
        if ($this->aPropriumRelatedByFourthPropriumId === null && ($this->fourth_proprium_id !== null)) {
            $this->aPropriumRelatedByFourthPropriumId = ChildPropriumQuery::create()->findPk($this->fourth_proprium_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPropriumRelatedByFourthPropriumId->addPlayerDecksRelatedByFourthPropriumId($this);
             */
        }

        return $this->aPropriumRelatedByFourthPropriumId;
    }

    /**
     * Declares an association between this object and a ChildProprium object.
     *
     * @param  ChildProprium $v
     * @return $this|\app\model\PlayerDeck The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPropriumRelatedByFifthPropriumId(ChildProprium $v = null)
    {
        if ($v === null) {
            $this->setFifthPropriumId(NULL);
        } else {
            $this->setFifthPropriumId($v->getId());
        }

        $this->aPropriumRelatedByFifthPropriumId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildProprium object, it will not be re-added.
        if ($v !== null) {
            $v->addPlayerDeckRelatedByFifthPropriumId($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildProprium object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildProprium The associated ChildProprium object.
     * @throws PropelException
     */
    public function getPropriumRelatedByFifthPropriumId(ConnectionInterface $con = null)
    {
        if ($this->aPropriumRelatedByFifthPropriumId === null && ($this->fifth_proprium_id !== null)) {
            $this->aPropriumRelatedByFifthPropriumId = ChildPropriumQuery::create()->findPk($this->fifth_proprium_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPropriumRelatedByFifthPropriumId->addPlayerDecksRelatedByFifthPropriumId($this);
             */
        }

        return $this->aPropriumRelatedByFifthPropriumId;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aPlayer) {
            $this->aPlayer->removePlayerDeck($this);
        }
        if (null !== $this->aPropriumRelatedByFirstPropriumId) {
            $this->aPropriumRelatedByFirstPropriumId->removePlayerDeckRelatedByFirstPropriumId($this);
        }
        if (null !== $this->aPropriumRelatedBySecondPropriumId) {
            $this->aPropriumRelatedBySecondPropriumId->removePlayerDeckRelatedBySecondPropriumId($this);
        }
        if (null !== $this->aPropriumRelatedByThirdPropriumId) {
            $this->aPropriumRelatedByThirdPropriumId->removePlayerDeckRelatedByThirdPropriumId($this);
        }
        if (null !== $this->aPropriumRelatedByFourthPropriumId) {
            $this->aPropriumRelatedByFourthPropriumId->removePlayerDeckRelatedByFourthPropriumId($this);
        }
        if (null !== $this->aPropriumRelatedByFifthPropriumId) {
            $this->aPropriumRelatedByFifthPropriumId->removePlayerDeckRelatedByFifthPropriumId($this);
        }
        $this->id = null;
        $this->player_id = null;
        $this->first_proprium_id = null;
        $this->second_proprium_id = null;
        $this->third_proprium_id = null;
        $this->fourth_proprium_id = null;
        $this->fifth_proprium_id = null;
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
        } // if ($deep)

        $this->aPlayer = null;
        $this->aPropriumRelatedByFirstPropriumId = null;
        $this->aPropriumRelatedBySecondPropriumId = null;
        $this->aPropriumRelatedByThirdPropriumId = null;
        $this->aPropriumRelatedByFourthPropriumId = null;
        $this->aPropriumRelatedByFifthPropriumId = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PlayerDeckTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildPlayerDeck The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[PlayerDeckTableMap::COL_UPDATED_AT] = true;

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
