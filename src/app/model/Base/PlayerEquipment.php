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
use app\model\PlayerEquipment as ChildPlayerEquipment;
use app\model\PlayerEquipmentQuery as ChildPlayerEquipmentQuery;
use app\model\PlayerItem as ChildPlayerItem;
use app\model\PlayerItemQuery as ChildPlayerItemQuery;
use app\model\PlayerQuery as ChildPlayerQuery;
use app\model\Map\PlayerEquipmentTableMap;

/**
 * Base class that represents a row from the 'player_equipment' table.
 *
 *
 *
 * @package    propel.generator.app.model.Base
 */
abstract class PlayerEquipment implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\app\\model\\Map\\PlayerEquipmentTableMap';


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
     * The value for the weapon1_player_item_id field.
     *
     * @var        int
     */
    protected $weapon1_player_item_id;

    /**
     * The value for the weapon2_player_item_id field.
     *
     * @var        int
     */
    protected $weapon2_player_item_id;

    /**
     * The value for the head_player_item_id field.
     *
     * @var        int
     */
    protected $head_player_item_id;

    /**
     * The value for the left_arm_player_item_id field.
     *
     * @var        int
     */
    protected $left_arm_player_item_id;

    /**
     * The value for the right_arm_player_item_id field.
     *
     * @var        int
     */
    protected $right_arm_player_item_id;

    /**
     * The value for the left_leg_player_item_id field.
     *
     * @var        int
     */
    protected $left_leg_player_item_id;

    /**
     * The value for the right_leg_player_item_id field.
     *
     * @var        int
     */
    protected $right_leg_player_item_id;

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
     * @var        ChildPlayerItem
     */
    protected $aPlayerItemRelatedByWeapon1PlayerItemId;

    /**
     * @var        ChildPlayerItem
     */
    protected $aPlayerItemRelatedByWeapon2PlayerItemId;

    /**
     * @var        ChildPlayerItem
     */
    protected $aPlayerItemRelatedByHeadPlayerItemId;

    /**
     * @var        ChildPlayerItem
     */
    protected $aPlayerItemRelatedByLeftArmPlayerItemId;

    /**
     * @var        ChildPlayerItem
     */
    protected $aPlayerItemRelatedByRightArmPlayerItemId;

    /**
     * @var        ChildPlayerItem
     */
    protected $aPlayerItemRelatedByLeftLegPlayerItemId;

    /**
     * @var        ChildPlayerItem
     */
    protected $aPlayerItemRelatedByRightLegPlayerItemId;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of app\model\Base\PlayerEquipment object.
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
     * Compares this with another <code>PlayerEquipment</code> instance.  If
     * <code>obj</code> is an instance of <code>PlayerEquipment</code>, delegates to
     * <code>equals(PlayerEquipment)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|PlayerEquipment The current object, for fluid interface
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
     * Get the [weapon1_player_item_id] column value.
     *
     * @return int
     */
    public function getWeapon1PlayerItemId()
    {
        return $this->weapon1_player_item_id;
    }

    /**
     * Get the [weapon2_player_item_id] column value.
     *
     * @return int
     */
    public function getWeapon2PlayerItemId()
    {
        return $this->weapon2_player_item_id;
    }

    /**
     * Get the [head_player_item_id] column value.
     *
     * @return int
     */
    public function getHeadPlayerItemId()
    {
        return $this->head_player_item_id;
    }

    /**
     * Get the [left_arm_player_item_id] column value.
     *
     * @return int
     */
    public function getLeftArmPlayerItemId()
    {
        return $this->left_arm_player_item_id;
    }

    /**
     * Get the [right_arm_player_item_id] column value.
     *
     * @return int
     */
    public function getRightArmPlayerItemId()
    {
        return $this->right_arm_player_item_id;
    }

    /**
     * Get the [left_leg_player_item_id] column value.
     *
     * @return int
     */
    public function getLeftLegPlayerItemId()
    {
        return $this->left_leg_player_item_id;
    }

    /**
     * Get the [right_leg_player_item_id] column value.
     *
     * @return int
     */
    public function getRightLegPlayerItemId()
    {
        return $this->right_leg_player_item_id;
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
     * @return $this|\app\model\PlayerEquipment The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[PlayerEquipmentTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [player_id] column.
     *
     * @param int $v new value
     * @return $this|\app\model\PlayerEquipment The current object (for fluent API support)
     */
    public function setPlayerId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->player_id !== $v) {
            $this->player_id = $v;
            $this->modifiedColumns[PlayerEquipmentTableMap::COL_PLAYER_ID] = true;
        }

        if ($this->aPlayer !== null && $this->aPlayer->getId() !== $v) {
            $this->aPlayer = null;
        }

        return $this;
    } // setPlayerId()

    /**
     * Set the value of [weapon1_player_item_id] column.
     *
     * @param int $v new value
     * @return $this|\app\model\PlayerEquipment The current object (for fluent API support)
     */
    public function setWeapon1PlayerItemId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->weapon1_player_item_id !== $v) {
            $this->weapon1_player_item_id = $v;
            $this->modifiedColumns[PlayerEquipmentTableMap::COL_WEAPON1_PLAYER_ITEM_ID] = true;
        }

        if ($this->aPlayerItemRelatedByWeapon1PlayerItemId !== null && $this->aPlayerItemRelatedByWeapon1PlayerItemId->getId() !== $v) {
            $this->aPlayerItemRelatedByWeapon1PlayerItemId = null;
        }

        return $this;
    } // setWeapon1PlayerItemId()

    /**
     * Set the value of [weapon2_player_item_id] column.
     *
     * @param int $v new value
     * @return $this|\app\model\PlayerEquipment The current object (for fluent API support)
     */
    public function setWeapon2PlayerItemId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->weapon2_player_item_id !== $v) {
            $this->weapon2_player_item_id = $v;
            $this->modifiedColumns[PlayerEquipmentTableMap::COL_WEAPON2_PLAYER_ITEM_ID] = true;
        }

        if ($this->aPlayerItemRelatedByWeapon2PlayerItemId !== null && $this->aPlayerItemRelatedByWeapon2PlayerItemId->getId() !== $v) {
            $this->aPlayerItemRelatedByWeapon2PlayerItemId = null;
        }

        return $this;
    } // setWeapon2PlayerItemId()

    /**
     * Set the value of [head_player_item_id] column.
     *
     * @param int $v new value
     * @return $this|\app\model\PlayerEquipment The current object (for fluent API support)
     */
    public function setHeadPlayerItemId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->head_player_item_id !== $v) {
            $this->head_player_item_id = $v;
            $this->modifiedColumns[PlayerEquipmentTableMap::COL_HEAD_PLAYER_ITEM_ID] = true;
        }

        if ($this->aPlayerItemRelatedByHeadPlayerItemId !== null && $this->aPlayerItemRelatedByHeadPlayerItemId->getId() !== $v) {
            $this->aPlayerItemRelatedByHeadPlayerItemId = null;
        }

        return $this;
    } // setHeadPlayerItemId()

    /**
     * Set the value of [left_arm_player_item_id] column.
     *
     * @param int $v new value
     * @return $this|\app\model\PlayerEquipment The current object (for fluent API support)
     */
    public function setLeftArmPlayerItemId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->left_arm_player_item_id !== $v) {
            $this->left_arm_player_item_id = $v;
            $this->modifiedColumns[PlayerEquipmentTableMap::COL_LEFT_ARM_PLAYER_ITEM_ID] = true;
        }

        if ($this->aPlayerItemRelatedByLeftArmPlayerItemId !== null && $this->aPlayerItemRelatedByLeftArmPlayerItemId->getId() !== $v) {
            $this->aPlayerItemRelatedByLeftArmPlayerItemId = null;
        }

        return $this;
    } // setLeftArmPlayerItemId()

    /**
     * Set the value of [right_arm_player_item_id] column.
     *
     * @param int $v new value
     * @return $this|\app\model\PlayerEquipment The current object (for fluent API support)
     */
    public function setRightArmPlayerItemId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->right_arm_player_item_id !== $v) {
            $this->right_arm_player_item_id = $v;
            $this->modifiedColumns[PlayerEquipmentTableMap::COL_RIGHT_ARM_PLAYER_ITEM_ID] = true;
        }

        if ($this->aPlayerItemRelatedByRightArmPlayerItemId !== null && $this->aPlayerItemRelatedByRightArmPlayerItemId->getId() !== $v) {
            $this->aPlayerItemRelatedByRightArmPlayerItemId = null;
        }

        return $this;
    } // setRightArmPlayerItemId()

    /**
     * Set the value of [left_leg_player_item_id] column.
     *
     * @param int $v new value
     * @return $this|\app\model\PlayerEquipment The current object (for fluent API support)
     */
    public function setLeftLegPlayerItemId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->left_leg_player_item_id !== $v) {
            $this->left_leg_player_item_id = $v;
            $this->modifiedColumns[PlayerEquipmentTableMap::COL_LEFT_LEG_PLAYER_ITEM_ID] = true;
        }

        if ($this->aPlayerItemRelatedByLeftLegPlayerItemId !== null && $this->aPlayerItemRelatedByLeftLegPlayerItemId->getId() !== $v) {
            $this->aPlayerItemRelatedByLeftLegPlayerItemId = null;
        }

        return $this;
    } // setLeftLegPlayerItemId()

    /**
     * Set the value of [right_leg_player_item_id] column.
     *
     * @param int $v new value
     * @return $this|\app\model\PlayerEquipment The current object (for fluent API support)
     */
    public function setRightLegPlayerItemId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->right_leg_player_item_id !== $v) {
            $this->right_leg_player_item_id = $v;
            $this->modifiedColumns[PlayerEquipmentTableMap::COL_RIGHT_LEG_PLAYER_ITEM_ID] = true;
        }

        if ($this->aPlayerItemRelatedByRightLegPlayerItemId !== null && $this->aPlayerItemRelatedByRightLegPlayerItemId->getId() !== $v) {
            $this->aPlayerItemRelatedByRightLegPlayerItemId = null;
        }

        return $this;
    } // setRightLegPlayerItemId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\app\model\PlayerEquipment The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PlayerEquipmentTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\app\model\PlayerEquipment The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PlayerEquipmentTableMap::COL_UPDATED_AT] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PlayerEquipmentTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PlayerEquipmentTableMap::translateFieldName('PlayerId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->player_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : PlayerEquipmentTableMap::translateFieldName('Weapon1PlayerItemId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->weapon1_player_item_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : PlayerEquipmentTableMap::translateFieldName('Weapon2PlayerItemId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->weapon2_player_item_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : PlayerEquipmentTableMap::translateFieldName('HeadPlayerItemId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->head_player_item_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : PlayerEquipmentTableMap::translateFieldName('LeftArmPlayerItemId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->left_arm_player_item_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : PlayerEquipmentTableMap::translateFieldName('RightArmPlayerItemId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->right_arm_player_item_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : PlayerEquipmentTableMap::translateFieldName('LeftLegPlayerItemId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->left_leg_player_item_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : PlayerEquipmentTableMap::translateFieldName('RightLegPlayerItemId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->right_leg_player_item_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : PlayerEquipmentTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : PlayerEquipmentTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 11; // 11 = PlayerEquipmentTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\app\\model\\PlayerEquipment'), 0, $e);
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
        if ($this->aPlayerItemRelatedByWeapon1PlayerItemId !== null && $this->weapon1_player_item_id !== $this->aPlayerItemRelatedByWeapon1PlayerItemId->getId()) {
            $this->aPlayerItemRelatedByWeapon1PlayerItemId = null;
        }
        if ($this->aPlayerItemRelatedByWeapon2PlayerItemId !== null && $this->weapon2_player_item_id !== $this->aPlayerItemRelatedByWeapon2PlayerItemId->getId()) {
            $this->aPlayerItemRelatedByWeapon2PlayerItemId = null;
        }
        if ($this->aPlayerItemRelatedByHeadPlayerItemId !== null && $this->head_player_item_id !== $this->aPlayerItemRelatedByHeadPlayerItemId->getId()) {
            $this->aPlayerItemRelatedByHeadPlayerItemId = null;
        }
        if ($this->aPlayerItemRelatedByLeftArmPlayerItemId !== null && $this->left_arm_player_item_id !== $this->aPlayerItemRelatedByLeftArmPlayerItemId->getId()) {
            $this->aPlayerItemRelatedByLeftArmPlayerItemId = null;
        }
        if ($this->aPlayerItemRelatedByRightArmPlayerItemId !== null && $this->right_arm_player_item_id !== $this->aPlayerItemRelatedByRightArmPlayerItemId->getId()) {
            $this->aPlayerItemRelatedByRightArmPlayerItemId = null;
        }
        if ($this->aPlayerItemRelatedByLeftLegPlayerItemId !== null && $this->left_leg_player_item_id !== $this->aPlayerItemRelatedByLeftLegPlayerItemId->getId()) {
            $this->aPlayerItemRelatedByLeftLegPlayerItemId = null;
        }
        if ($this->aPlayerItemRelatedByRightLegPlayerItemId !== null && $this->right_leg_player_item_id !== $this->aPlayerItemRelatedByRightLegPlayerItemId->getId()) {
            $this->aPlayerItemRelatedByRightLegPlayerItemId = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(PlayerEquipmentTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildPlayerEquipmentQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPlayer = null;
            $this->aPlayerItemRelatedByWeapon1PlayerItemId = null;
            $this->aPlayerItemRelatedByWeapon2PlayerItemId = null;
            $this->aPlayerItemRelatedByHeadPlayerItemId = null;
            $this->aPlayerItemRelatedByLeftArmPlayerItemId = null;
            $this->aPlayerItemRelatedByRightArmPlayerItemId = null;
            $this->aPlayerItemRelatedByLeftLegPlayerItemId = null;
            $this->aPlayerItemRelatedByRightLegPlayerItemId = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see PlayerEquipment::setDeleted()
     * @see PlayerEquipment::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerEquipmentTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildPlayerEquipmentQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerEquipmentTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(PlayerEquipmentTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
                if (!$this->isColumnModified(PlayerEquipmentTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PlayerEquipmentTableMap::COL_UPDATED_AT)) {
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
                PlayerEquipmentTableMap::addInstanceToPool($this);
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

            if ($this->aPlayerItemRelatedByWeapon1PlayerItemId !== null) {
                if ($this->aPlayerItemRelatedByWeapon1PlayerItemId->isModified() || $this->aPlayerItemRelatedByWeapon1PlayerItemId->isNew()) {
                    $affectedRows += $this->aPlayerItemRelatedByWeapon1PlayerItemId->save($con);
                }
                $this->setPlayerItemRelatedByWeapon1PlayerItemId($this->aPlayerItemRelatedByWeapon1PlayerItemId);
            }

            if ($this->aPlayerItemRelatedByWeapon2PlayerItemId !== null) {
                if ($this->aPlayerItemRelatedByWeapon2PlayerItemId->isModified() || $this->aPlayerItemRelatedByWeapon2PlayerItemId->isNew()) {
                    $affectedRows += $this->aPlayerItemRelatedByWeapon2PlayerItemId->save($con);
                }
                $this->setPlayerItemRelatedByWeapon2PlayerItemId($this->aPlayerItemRelatedByWeapon2PlayerItemId);
            }

            if ($this->aPlayerItemRelatedByHeadPlayerItemId !== null) {
                if ($this->aPlayerItemRelatedByHeadPlayerItemId->isModified() || $this->aPlayerItemRelatedByHeadPlayerItemId->isNew()) {
                    $affectedRows += $this->aPlayerItemRelatedByHeadPlayerItemId->save($con);
                }
                $this->setPlayerItemRelatedByHeadPlayerItemId($this->aPlayerItemRelatedByHeadPlayerItemId);
            }

            if ($this->aPlayerItemRelatedByLeftArmPlayerItemId !== null) {
                if ($this->aPlayerItemRelatedByLeftArmPlayerItemId->isModified() || $this->aPlayerItemRelatedByLeftArmPlayerItemId->isNew()) {
                    $affectedRows += $this->aPlayerItemRelatedByLeftArmPlayerItemId->save($con);
                }
                $this->setPlayerItemRelatedByLeftArmPlayerItemId($this->aPlayerItemRelatedByLeftArmPlayerItemId);
            }

            if ($this->aPlayerItemRelatedByRightArmPlayerItemId !== null) {
                if ($this->aPlayerItemRelatedByRightArmPlayerItemId->isModified() || $this->aPlayerItemRelatedByRightArmPlayerItemId->isNew()) {
                    $affectedRows += $this->aPlayerItemRelatedByRightArmPlayerItemId->save($con);
                }
                $this->setPlayerItemRelatedByRightArmPlayerItemId($this->aPlayerItemRelatedByRightArmPlayerItemId);
            }

            if ($this->aPlayerItemRelatedByLeftLegPlayerItemId !== null) {
                if ($this->aPlayerItemRelatedByLeftLegPlayerItemId->isModified() || $this->aPlayerItemRelatedByLeftLegPlayerItemId->isNew()) {
                    $affectedRows += $this->aPlayerItemRelatedByLeftLegPlayerItemId->save($con);
                }
                $this->setPlayerItemRelatedByLeftLegPlayerItemId($this->aPlayerItemRelatedByLeftLegPlayerItemId);
            }

            if ($this->aPlayerItemRelatedByRightLegPlayerItemId !== null) {
                if ($this->aPlayerItemRelatedByRightLegPlayerItemId->isModified() || $this->aPlayerItemRelatedByRightLegPlayerItemId->isNew()) {
                    $affectedRows += $this->aPlayerItemRelatedByRightLegPlayerItemId->save($con);
                }
                $this->setPlayerItemRelatedByRightLegPlayerItemId($this->aPlayerItemRelatedByRightLegPlayerItemId);
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

        $this->modifiedColumns[PlayerEquipmentTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PlayerEquipmentTableMap::COL_ID . ')');
        }
        if (null === $this->id) {
            try {
                $dataFetcher = $con->query("SELECT nextval('player_equipment_id_seq')");
                $this->id = (int) $dataFetcher->fetchColumn();
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', 0, $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_PLAYER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'player_id';
        }
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_WEAPON1_PLAYER_ITEM_ID)) {
            $modifiedColumns[':p' . $index++]  = 'weapon1_player_item_id';
        }
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_WEAPON2_PLAYER_ITEM_ID)) {
            $modifiedColumns[':p' . $index++]  = 'weapon2_player_item_id';
        }
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_HEAD_PLAYER_ITEM_ID)) {
            $modifiedColumns[':p' . $index++]  = 'head_player_item_id';
        }
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_LEFT_ARM_PLAYER_ITEM_ID)) {
            $modifiedColumns[':p' . $index++]  = 'left_arm_player_item_id';
        }
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_RIGHT_ARM_PLAYER_ITEM_ID)) {
            $modifiedColumns[':p' . $index++]  = 'right_arm_player_item_id';
        }
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_LEFT_LEG_PLAYER_ITEM_ID)) {
            $modifiedColumns[':p' . $index++]  = 'left_leg_player_item_id';
        }
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_RIGHT_LEG_PLAYER_ITEM_ID)) {
            $modifiedColumns[':p' . $index++]  = 'right_leg_player_item_id';
        }
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO player_equipment (%s) VALUES (%s)',
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
                    case 'weapon1_player_item_id':
                        $stmt->bindValue($identifier, $this->weapon1_player_item_id, PDO::PARAM_INT);
                        break;
                    case 'weapon2_player_item_id':
                        $stmt->bindValue($identifier, $this->weapon2_player_item_id, PDO::PARAM_INT);
                        break;
                    case 'head_player_item_id':
                        $stmt->bindValue($identifier, $this->head_player_item_id, PDO::PARAM_INT);
                        break;
                    case 'left_arm_player_item_id':
                        $stmt->bindValue($identifier, $this->left_arm_player_item_id, PDO::PARAM_INT);
                        break;
                    case 'right_arm_player_item_id':
                        $stmt->bindValue($identifier, $this->right_arm_player_item_id, PDO::PARAM_INT);
                        break;
                    case 'left_leg_player_item_id':
                        $stmt->bindValue($identifier, $this->left_leg_player_item_id, PDO::PARAM_INT);
                        break;
                    case 'right_leg_player_item_id':
                        $stmt->bindValue($identifier, $this->right_leg_player_item_id, PDO::PARAM_INT);
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
        $pos = PlayerEquipmentTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getWeapon1PlayerItemId();
                break;
            case 3:
                return $this->getWeapon2PlayerItemId();
                break;
            case 4:
                return $this->getHeadPlayerItemId();
                break;
            case 5:
                return $this->getLeftArmPlayerItemId();
                break;
            case 6:
                return $this->getRightArmPlayerItemId();
                break;
            case 7:
                return $this->getLeftLegPlayerItemId();
                break;
            case 8:
                return $this->getRightLegPlayerItemId();
                break;
            case 9:
                return $this->getCreatedAt();
                break;
            case 10:
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

        if (isset($alreadyDumpedObjects['PlayerEquipment'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PlayerEquipment'][$this->hashCode()] = true;
        $keys = PlayerEquipmentTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getPlayerId(),
            $keys[2] => $this->getWeapon1PlayerItemId(),
            $keys[3] => $this->getWeapon2PlayerItemId(),
            $keys[4] => $this->getHeadPlayerItemId(),
            $keys[5] => $this->getLeftArmPlayerItemId(),
            $keys[6] => $this->getRightArmPlayerItemId(),
            $keys[7] => $this->getLeftLegPlayerItemId(),
            $keys[8] => $this->getRightLegPlayerItemId(),
            $keys[9] => $this->getCreatedAt(),
            $keys[10] => $this->getUpdatedAt(),
        );
        if ($result[$keys[9]] instanceof \DateTimeInterface) {
            $result[$keys[9]] = $result[$keys[9]]->format('c');
        }

        if ($result[$keys[10]] instanceof \DateTimeInterface) {
            $result[$keys[10]] = $result[$keys[10]]->format('c');
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
            if (null !== $this->aPlayerItemRelatedByWeapon1PlayerItemId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerItem';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_item';
                        break;
                    default:
                        $key = 'PlayerItem';
                }

                $result[$key] = $this->aPlayerItemRelatedByWeapon1PlayerItemId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPlayerItemRelatedByWeapon2PlayerItemId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerItem';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_item';
                        break;
                    default:
                        $key = 'PlayerItem';
                }

                $result[$key] = $this->aPlayerItemRelatedByWeapon2PlayerItemId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPlayerItemRelatedByHeadPlayerItemId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerItem';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_item';
                        break;
                    default:
                        $key = 'PlayerItem';
                }

                $result[$key] = $this->aPlayerItemRelatedByHeadPlayerItemId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPlayerItemRelatedByLeftArmPlayerItemId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerItem';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_item';
                        break;
                    default:
                        $key = 'PlayerItem';
                }

                $result[$key] = $this->aPlayerItemRelatedByLeftArmPlayerItemId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPlayerItemRelatedByRightArmPlayerItemId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerItem';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_item';
                        break;
                    default:
                        $key = 'PlayerItem';
                }

                $result[$key] = $this->aPlayerItemRelatedByRightArmPlayerItemId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPlayerItemRelatedByLeftLegPlayerItemId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerItem';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_item';
                        break;
                    default:
                        $key = 'PlayerItem';
                }

                $result[$key] = $this->aPlayerItemRelatedByLeftLegPlayerItemId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPlayerItemRelatedByRightLegPlayerItemId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerItem';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_item';
                        break;
                    default:
                        $key = 'PlayerItem';
                }

                $result[$key] = $this->aPlayerItemRelatedByRightLegPlayerItemId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
     * @return $this|\app\model\PlayerEquipment
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PlayerEquipmentTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\app\model\PlayerEquipment
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
                $this->setWeapon1PlayerItemId($value);
                break;
            case 3:
                $this->setWeapon2PlayerItemId($value);
                break;
            case 4:
                $this->setHeadPlayerItemId($value);
                break;
            case 5:
                $this->setLeftArmPlayerItemId($value);
                break;
            case 6:
                $this->setRightArmPlayerItemId($value);
                break;
            case 7:
                $this->setLeftLegPlayerItemId($value);
                break;
            case 8:
                $this->setRightLegPlayerItemId($value);
                break;
            case 9:
                $this->setCreatedAt($value);
                break;
            case 10:
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
        $keys = PlayerEquipmentTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setPlayerId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setWeapon1PlayerItemId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setWeapon2PlayerItemId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setHeadPlayerItemId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setLeftArmPlayerItemId($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setRightArmPlayerItemId($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setLeftLegPlayerItemId($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setRightLegPlayerItemId($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setCreatedAt($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setUpdatedAt($arr[$keys[10]]);
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
     * @return $this|\app\model\PlayerEquipment The current object, for fluid interface
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
        $criteria = new Criteria(PlayerEquipmentTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_ID)) {
            $criteria->add(PlayerEquipmentTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_PLAYER_ID)) {
            $criteria->add(PlayerEquipmentTableMap::COL_PLAYER_ID, $this->player_id);
        }
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_WEAPON1_PLAYER_ITEM_ID)) {
            $criteria->add(PlayerEquipmentTableMap::COL_WEAPON1_PLAYER_ITEM_ID, $this->weapon1_player_item_id);
        }
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_WEAPON2_PLAYER_ITEM_ID)) {
            $criteria->add(PlayerEquipmentTableMap::COL_WEAPON2_PLAYER_ITEM_ID, $this->weapon2_player_item_id);
        }
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_HEAD_PLAYER_ITEM_ID)) {
            $criteria->add(PlayerEquipmentTableMap::COL_HEAD_PLAYER_ITEM_ID, $this->head_player_item_id);
        }
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_LEFT_ARM_PLAYER_ITEM_ID)) {
            $criteria->add(PlayerEquipmentTableMap::COL_LEFT_ARM_PLAYER_ITEM_ID, $this->left_arm_player_item_id);
        }
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_RIGHT_ARM_PLAYER_ITEM_ID)) {
            $criteria->add(PlayerEquipmentTableMap::COL_RIGHT_ARM_PLAYER_ITEM_ID, $this->right_arm_player_item_id);
        }
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_LEFT_LEG_PLAYER_ITEM_ID)) {
            $criteria->add(PlayerEquipmentTableMap::COL_LEFT_LEG_PLAYER_ITEM_ID, $this->left_leg_player_item_id);
        }
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_RIGHT_LEG_PLAYER_ITEM_ID)) {
            $criteria->add(PlayerEquipmentTableMap::COL_RIGHT_LEG_PLAYER_ITEM_ID, $this->right_leg_player_item_id);
        }
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_CREATED_AT)) {
            $criteria->add(PlayerEquipmentTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(PlayerEquipmentTableMap::COL_UPDATED_AT)) {
            $criteria->add(PlayerEquipmentTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildPlayerEquipmentQuery::create();
        $criteria->add(PlayerEquipmentTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \app\model\PlayerEquipment (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPlayerId($this->getPlayerId());
        $copyObj->setWeapon1PlayerItemId($this->getWeapon1PlayerItemId());
        $copyObj->setWeapon2PlayerItemId($this->getWeapon2PlayerItemId());
        $copyObj->setHeadPlayerItemId($this->getHeadPlayerItemId());
        $copyObj->setLeftArmPlayerItemId($this->getLeftArmPlayerItemId());
        $copyObj->setRightArmPlayerItemId($this->getRightArmPlayerItemId());
        $copyObj->setLeftLegPlayerItemId($this->getLeftLegPlayerItemId());
        $copyObj->setRightLegPlayerItemId($this->getRightLegPlayerItemId());
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
     * @return \app\model\PlayerEquipment Clone of current object.
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
     * @return $this|\app\model\PlayerEquipment The current object (for fluent API support)
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
            $v->addPlayerEquipment($this);
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
                $this->aPlayer->addPlayerEquipments($this);
             */
        }

        return $this->aPlayer;
    }

    /**
     * Declares an association between this object and a ChildPlayerItem object.
     *
     * @param  ChildPlayerItem $v
     * @return $this|\app\model\PlayerEquipment The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPlayerItemRelatedByWeapon1PlayerItemId(ChildPlayerItem $v = null)
    {
        if ($v === null) {
            $this->setWeapon1PlayerItemId(NULL);
        } else {
            $this->setWeapon1PlayerItemId($v->getId());
        }

        $this->aPlayerItemRelatedByWeapon1PlayerItemId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPlayerItem object, it will not be re-added.
        if ($v !== null) {
            $v->addPlayerEquipmentRelatedByWeapon1PlayerItemId($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPlayerItem object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildPlayerItem The associated ChildPlayerItem object.
     * @throws PropelException
     */
    public function getPlayerItemRelatedByWeapon1PlayerItemId(ConnectionInterface $con = null)
    {
        if ($this->aPlayerItemRelatedByWeapon1PlayerItemId === null && ($this->weapon1_player_item_id !== null)) {
            $this->aPlayerItemRelatedByWeapon1PlayerItemId = ChildPlayerItemQuery::create()->findPk($this->weapon1_player_item_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPlayerItemRelatedByWeapon1PlayerItemId->addPlayerEquipmentsRelatedByWeapon1PlayerItemId($this);
             */
        }

        return $this->aPlayerItemRelatedByWeapon1PlayerItemId;
    }

    /**
     * Declares an association between this object and a ChildPlayerItem object.
     *
     * @param  ChildPlayerItem $v
     * @return $this|\app\model\PlayerEquipment The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPlayerItemRelatedByWeapon2PlayerItemId(ChildPlayerItem $v = null)
    {
        if ($v === null) {
            $this->setWeapon2PlayerItemId(NULL);
        } else {
            $this->setWeapon2PlayerItemId($v->getId());
        }

        $this->aPlayerItemRelatedByWeapon2PlayerItemId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPlayerItem object, it will not be re-added.
        if ($v !== null) {
            $v->addPlayerEquipmentRelatedByWeapon2PlayerItemId($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPlayerItem object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildPlayerItem The associated ChildPlayerItem object.
     * @throws PropelException
     */
    public function getPlayerItemRelatedByWeapon2PlayerItemId(ConnectionInterface $con = null)
    {
        if ($this->aPlayerItemRelatedByWeapon2PlayerItemId === null && ($this->weapon2_player_item_id !== null)) {
            $this->aPlayerItemRelatedByWeapon2PlayerItemId = ChildPlayerItemQuery::create()->findPk($this->weapon2_player_item_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPlayerItemRelatedByWeapon2PlayerItemId->addPlayerEquipmentsRelatedByWeapon2PlayerItemId($this);
             */
        }

        return $this->aPlayerItemRelatedByWeapon2PlayerItemId;
    }

    /**
     * Declares an association between this object and a ChildPlayerItem object.
     *
     * @param  ChildPlayerItem $v
     * @return $this|\app\model\PlayerEquipment The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPlayerItemRelatedByHeadPlayerItemId(ChildPlayerItem $v = null)
    {
        if ($v === null) {
            $this->setHeadPlayerItemId(NULL);
        } else {
            $this->setHeadPlayerItemId($v->getId());
        }

        $this->aPlayerItemRelatedByHeadPlayerItemId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPlayerItem object, it will not be re-added.
        if ($v !== null) {
            $v->addPlayerEquipmentRelatedByHeadPlayerItemId($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPlayerItem object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildPlayerItem The associated ChildPlayerItem object.
     * @throws PropelException
     */
    public function getPlayerItemRelatedByHeadPlayerItemId(ConnectionInterface $con = null)
    {
        if ($this->aPlayerItemRelatedByHeadPlayerItemId === null && ($this->head_player_item_id !== null)) {
            $this->aPlayerItemRelatedByHeadPlayerItemId = ChildPlayerItemQuery::create()->findPk($this->head_player_item_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPlayerItemRelatedByHeadPlayerItemId->addPlayerEquipmentsRelatedByHeadPlayerItemId($this);
             */
        }

        return $this->aPlayerItemRelatedByHeadPlayerItemId;
    }

    /**
     * Declares an association between this object and a ChildPlayerItem object.
     *
     * @param  ChildPlayerItem $v
     * @return $this|\app\model\PlayerEquipment The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPlayerItemRelatedByLeftArmPlayerItemId(ChildPlayerItem $v = null)
    {
        if ($v === null) {
            $this->setLeftArmPlayerItemId(NULL);
        } else {
            $this->setLeftArmPlayerItemId($v->getId());
        }

        $this->aPlayerItemRelatedByLeftArmPlayerItemId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPlayerItem object, it will not be re-added.
        if ($v !== null) {
            $v->addPlayerEquipmentRelatedByLeftArmPlayerItemId($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPlayerItem object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildPlayerItem The associated ChildPlayerItem object.
     * @throws PropelException
     */
    public function getPlayerItemRelatedByLeftArmPlayerItemId(ConnectionInterface $con = null)
    {
        if ($this->aPlayerItemRelatedByLeftArmPlayerItemId === null && ($this->left_arm_player_item_id !== null)) {
            $this->aPlayerItemRelatedByLeftArmPlayerItemId = ChildPlayerItemQuery::create()->findPk($this->left_arm_player_item_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPlayerItemRelatedByLeftArmPlayerItemId->addPlayerEquipmentsRelatedByLeftArmPlayerItemId($this);
             */
        }

        return $this->aPlayerItemRelatedByLeftArmPlayerItemId;
    }

    /**
     * Declares an association between this object and a ChildPlayerItem object.
     *
     * @param  ChildPlayerItem $v
     * @return $this|\app\model\PlayerEquipment The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPlayerItemRelatedByRightArmPlayerItemId(ChildPlayerItem $v = null)
    {
        if ($v === null) {
            $this->setRightArmPlayerItemId(NULL);
        } else {
            $this->setRightArmPlayerItemId($v->getId());
        }

        $this->aPlayerItemRelatedByRightArmPlayerItemId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPlayerItem object, it will not be re-added.
        if ($v !== null) {
            $v->addPlayerEquipmentRelatedByRightArmPlayerItemId($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPlayerItem object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildPlayerItem The associated ChildPlayerItem object.
     * @throws PropelException
     */
    public function getPlayerItemRelatedByRightArmPlayerItemId(ConnectionInterface $con = null)
    {
        if ($this->aPlayerItemRelatedByRightArmPlayerItemId === null && ($this->right_arm_player_item_id !== null)) {
            $this->aPlayerItemRelatedByRightArmPlayerItemId = ChildPlayerItemQuery::create()->findPk($this->right_arm_player_item_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPlayerItemRelatedByRightArmPlayerItemId->addPlayerEquipmentsRelatedByRightArmPlayerItemId($this);
             */
        }

        return $this->aPlayerItemRelatedByRightArmPlayerItemId;
    }

    /**
     * Declares an association between this object and a ChildPlayerItem object.
     *
     * @param  ChildPlayerItem $v
     * @return $this|\app\model\PlayerEquipment The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPlayerItemRelatedByLeftLegPlayerItemId(ChildPlayerItem $v = null)
    {
        if ($v === null) {
            $this->setLeftLegPlayerItemId(NULL);
        } else {
            $this->setLeftLegPlayerItemId($v->getId());
        }

        $this->aPlayerItemRelatedByLeftLegPlayerItemId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPlayerItem object, it will not be re-added.
        if ($v !== null) {
            $v->addPlayerEquipmentRelatedByLeftLegPlayerItemId($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPlayerItem object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildPlayerItem The associated ChildPlayerItem object.
     * @throws PropelException
     */
    public function getPlayerItemRelatedByLeftLegPlayerItemId(ConnectionInterface $con = null)
    {
        if ($this->aPlayerItemRelatedByLeftLegPlayerItemId === null && ($this->left_leg_player_item_id !== null)) {
            $this->aPlayerItemRelatedByLeftLegPlayerItemId = ChildPlayerItemQuery::create()->findPk($this->left_leg_player_item_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPlayerItemRelatedByLeftLegPlayerItemId->addPlayerEquipmentsRelatedByLeftLegPlayerItemId($this);
             */
        }

        return $this->aPlayerItemRelatedByLeftLegPlayerItemId;
    }

    /**
     * Declares an association between this object and a ChildPlayerItem object.
     *
     * @param  ChildPlayerItem $v
     * @return $this|\app\model\PlayerEquipment The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPlayerItemRelatedByRightLegPlayerItemId(ChildPlayerItem $v = null)
    {
        if ($v === null) {
            $this->setRightLegPlayerItemId(NULL);
        } else {
            $this->setRightLegPlayerItemId($v->getId());
        }

        $this->aPlayerItemRelatedByRightLegPlayerItemId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPlayerItem object, it will not be re-added.
        if ($v !== null) {
            $v->addPlayerEquipmentRelatedByRightLegPlayerItemId($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPlayerItem object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildPlayerItem The associated ChildPlayerItem object.
     * @throws PropelException
     */
    public function getPlayerItemRelatedByRightLegPlayerItemId(ConnectionInterface $con = null)
    {
        if ($this->aPlayerItemRelatedByRightLegPlayerItemId === null && ($this->right_leg_player_item_id !== null)) {
            $this->aPlayerItemRelatedByRightLegPlayerItemId = ChildPlayerItemQuery::create()->findPk($this->right_leg_player_item_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPlayerItemRelatedByRightLegPlayerItemId->addPlayerEquipmentsRelatedByRightLegPlayerItemId($this);
             */
        }

        return $this->aPlayerItemRelatedByRightLegPlayerItemId;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aPlayer) {
            $this->aPlayer->removePlayerEquipment($this);
        }
        if (null !== $this->aPlayerItemRelatedByWeapon1PlayerItemId) {
            $this->aPlayerItemRelatedByWeapon1PlayerItemId->removePlayerEquipmentRelatedByWeapon1PlayerItemId($this);
        }
        if (null !== $this->aPlayerItemRelatedByWeapon2PlayerItemId) {
            $this->aPlayerItemRelatedByWeapon2PlayerItemId->removePlayerEquipmentRelatedByWeapon2PlayerItemId($this);
        }
        if (null !== $this->aPlayerItemRelatedByHeadPlayerItemId) {
            $this->aPlayerItemRelatedByHeadPlayerItemId->removePlayerEquipmentRelatedByHeadPlayerItemId($this);
        }
        if (null !== $this->aPlayerItemRelatedByLeftArmPlayerItemId) {
            $this->aPlayerItemRelatedByLeftArmPlayerItemId->removePlayerEquipmentRelatedByLeftArmPlayerItemId($this);
        }
        if (null !== $this->aPlayerItemRelatedByRightArmPlayerItemId) {
            $this->aPlayerItemRelatedByRightArmPlayerItemId->removePlayerEquipmentRelatedByRightArmPlayerItemId($this);
        }
        if (null !== $this->aPlayerItemRelatedByLeftLegPlayerItemId) {
            $this->aPlayerItemRelatedByLeftLegPlayerItemId->removePlayerEquipmentRelatedByLeftLegPlayerItemId($this);
        }
        if (null !== $this->aPlayerItemRelatedByRightLegPlayerItemId) {
            $this->aPlayerItemRelatedByRightLegPlayerItemId->removePlayerEquipmentRelatedByRightLegPlayerItemId($this);
        }
        $this->id = null;
        $this->player_id = null;
        $this->weapon1_player_item_id = null;
        $this->weapon2_player_item_id = null;
        $this->head_player_item_id = null;
        $this->left_arm_player_item_id = null;
        $this->right_arm_player_item_id = null;
        $this->left_leg_player_item_id = null;
        $this->right_leg_player_item_id = null;
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
        $this->aPlayerItemRelatedByWeapon1PlayerItemId = null;
        $this->aPlayerItemRelatedByWeapon2PlayerItemId = null;
        $this->aPlayerItemRelatedByHeadPlayerItemId = null;
        $this->aPlayerItemRelatedByLeftArmPlayerItemId = null;
        $this->aPlayerItemRelatedByRightArmPlayerItemId = null;
        $this->aPlayerItemRelatedByLeftLegPlayerItemId = null;
        $this->aPlayerItemRelatedByRightLegPlayerItemId = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PlayerEquipmentTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildPlayerEquipment The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[PlayerEquipmentTableMap::COL_UPDATED_AT] = true;

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
