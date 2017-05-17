<?php

namespace app\model\Base;

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
use app\model\Player as ChildPlayer;
use app\model\PlayerBattleLog as ChildPlayerBattleLog;
use app\model\PlayerBattleLogQuery as ChildPlayerBattleLogQuery;
use app\model\PlayerDeck as ChildPlayerDeck;
use app\model\PlayerDeckQuery as ChildPlayerDeckQuery;
use app\model\PlayerItem as ChildPlayerItem;
use app\model\PlayerItemQuery as ChildPlayerItemQuery;
use app\model\PlayerQuery as ChildPlayerQuery;
use app\model\Map\PlayerBattleLogTableMap;
use app\model\Map\PlayerDeckTableMap;
use app\model\Map\PlayerItemTableMap;
use app\model\Map\PlayerTableMap;

/**
 * Base class that represents a row from the 'player' table.
 *
 *
 *
 * @package    propel.generator.app.model.Base
 */
abstract class Player implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\app\\model\\Map\\PlayerTableMap';


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
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * @var        ObjectCollection|ChildPlayerItem[] Collection to store aggregation of ChildPlayerItem objects.
     */
    protected $collPlayerItems;
    protected $collPlayerItemsPartial;

    /**
     * @var        ObjectCollection|ChildPlayerDeck[] Collection to store aggregation of ChildPlayerDeck objects.
     */
    protected $collPlayerDecks;
    protected $collPlayerDecksPartial;

    /**
     * @var        ObjectCollection|ChildPlayerBattleLog[] Collection to store aggregation of ChildPlayerBattleLog objects.
     */
    protected $collPlayerBattleLogsRelatedByPlayerId;
    protected $collPlayerBattleLogsRelatedByPlayerIdPartial;

    /**
     * @var        ObjectCollection|ChildPlayerBattleLog[] Collection to store aggregation of ChildPlayerBattleLog objects.
     */
    protected $collPlayerBattleLogsRelatedByEnemyPlayerId;
    protected $collPlayerBattleLogsRelatedByEnemyPlayerIdPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerItem[]
     */
    protected $playerItemsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerDeck[]
     */
    protected $playerDecksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerBattleLog[]
     */
    protected $playerBattleLogsRelatedByPlayerIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerBattleLog[]
     */
    protected $playerBattleLogsRelatedByEnemyPlayerIdScheduledForDeletion = null;

    /**
     * Initializes internal state of app\model\Base\Player object.
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
     * Compares this with another <code>Player</code> instance.  If
     * <code>obj</code> is an instance of <code>Player</code>, delegates to
     * <code>equals(Player)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Player The current object, for fluid interface
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
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\app\model\Player The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[PlayerTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\app\model\Player The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[PlayerTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PlayerTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PlayerTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 2; // 2 = PlayerTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\app\\model\\Player'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(PlayerTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildPlayerQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collPlayerItems = null;

            $this->collPlayerDecks = null;

            $this->collPlayerBattleLogsRelatedByPlayerId = null;

            $this->collPlayerBattleLogsRelatedByEnemyPlayerId = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Player::setDeleted()
     * @see Player::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildPlayerQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                PlayerTableMap::addInstanceToPool($this);
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

            if ($this->playerItemsScheduledForDeletion !== null) {
                if (!$this->playerItemsScheduledForDeletion->isEmpty()) {
                    \app\model\PlayerItemQuery::create()
                        ->filterByPrimaryKeys($this->playerItemsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerItemsScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerItems !== null) {
                foreach ($this->collPlayerItems as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerDecksScheduledForDeletion !== null) {
                if (!$this->playerDecksScheduledForDeletion->isEmpty()) {
                    \app\model\PlayerDeckQuery::create()
                        ->filterByPrimaryKeys($this->playerDecksScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerDecksScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerDecks !== null) {
                foreach ($this->collPlayerDecks as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerBattleLogsRelatedByPlayerIdScheduledForDeletion !== null) {
                if (!$this->playerBattleLogsRelatedByPlayerIdScheduledForDeletion->isEmpty()) {
                    \app\model\PlayerBattleLogQuery::create()
                        ->filterByPrimaryKeys($this->playerBattleLogsRelatedByPlayerIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerBattleLogsRelatedByPlayerIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerBattleLogsRelatedByPlayerId !== null) {
                foreach ($this->collPlayerBattleLogsRelatedByPlayerId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerBattleLogsRelatedByEnemyPlayerIdScheduledForDeletion !== null) {
                if (!$this->playerBattleLogsRelatedByEnemyPlayerIdScheduledForDeletion->isEmpty()) {
                    \app\model\PlayerBattleLogQuery::create()
                        ->filterByPrimaryKeys($this->playerBattleLogsRelatedByEnemyPlayerIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerBattleLogsRelatedByEnemyPlayerIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerBattleLogsRelatedByEnemyPlayerId !== null) {
                foreach ($this->collPlayerBattleLogsRelatedByEnemyPlayerId as $referrerFK) {
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

        $this->modifiedColumns[PlayerTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PlayerTableMap::COL_ID . ')');
        }
        if (null === $this->id) {
            try {
                $dataFetcher = $con->query("SELECT nextval('player_id_seq')");
                $this->id = (int) $dataFetcher->fetchColumn();
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', 0, $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PlayerTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(PlayerTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }

        $sql = sprintf(
            'INSERT INTO player (%s) VALUES (%s)',
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
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
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
        $pos = PlayerTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getName();
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

        if (isset($alreadyDumpedObjects['Player'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Player'][$this->hashCode()] = true;
        $keys = PlayerTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collPlayerItems) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerItems';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_items';
                        break;
                    default:
                        $key = 'PlayerItems';
                }

                $result[$key] = $this->collPlayerItems->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerDecks) {

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

                $result[$key] = $this->collPlayerDecks->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerBattleLogsRelatedByPlayerId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerBattleLogs';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_battle_logs';
                        break;
                    default:
                        $key = 'PlayerBattleLogs';
                }

                $result[$key] = $this->collPlayerBattleLogsRelatedByPlayerId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerBattleLogsRelatedByEnemyPlayerId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerBattleLogs';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'player_battle_logs';
                        break;
                    default:
                        $key = 'PlayerBattleLogs';
                }

                $result[$key] = $this->collPlayerBattleLogsRelatedByEnemyPlayerId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\app\model\Player
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PlayerTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\app\model\Player
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
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
        $keys = PlayerTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
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
     * @return $this|\app\model\Player The current object, for fluid interface
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
        $criteria = new Criteria(PlayerTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PlayerTableMap::COL_ID)) {
            $criteria->add(PlayerTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(PlayerTableMap::COL_NAME)) {
            $criteria->add(PlayerTableMap::COL_NAME, $this->name);
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
        $criteria = ChildPlayerQuery::create();
        $criteria->add(PlayerTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \app\model\Player (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getPlayerItems() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerItem($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerDecks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerDeck($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerBattleLogsRelatedByPlayerId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerBattleLogRelatedByPlayerId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerBattleLogsRelatedByEnemyPlayerId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerBattleLogRelatedByEnemyPlayerId($relObj->copy($deepCopy));
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
     * @return \app\model\Player Clone of current object.
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('PlayerItem' == $relationName) {
            $this->initPlayerItems();
            return;
        }
        if ('PlayerDeck' == $relationName) {
            $this->initPlayerDecks();
            return;
        }
        if ('PlayerBattleLogRelatedByPlayerId' == $relationName) {
            $this->initPlayerBattleLogsRelatedByPlayerId();
            return;
        }
        if ('PlayerBattleLogRelatedByEnemyPlayerId' == $relationName) {
            $this->initPlayerBattleLogsRelatedByEnemyPlayerId();
            return;
        }
    }

    /**
     * Clears out the collPlayerItems collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerItems()
     */
    public function clearPlayerItems()
    {
        $this->collPlayerItems = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerItems collection loaded partially.
     */
    public function resetPartialPlayerItems($v = true)
    {
        $this->collPlayerItemsPartial = $v;
    }

    /**
     * Initializes the collPlayerItems collection.
     *
     * By default this just sets the collPlayerItems collection to an empty array (like clearcollPlayerItems());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerItems($overrideExisting = true)
    {
        if (null !== $this->collPlayerItems && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerItemTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerItems = new $collectionClassName;
        $this->collPlayerItems->setModel('\app\model\PlayerItem');
    }

    /**
     * Gets an array of ChildPlayerItem objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayer is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerItem[] List of ChildPlayerItem objects
     * @throws PropelException
     */
    public function getPlayerItems(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerItemsPartial && !$this->isNew();
        if (null === $this->collPlayerItems || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerItems) {
                // return empty collection
                $this->initPlayerItems();
            } else {
                $collPlayerItems = ChildPlayerItemQuery::create(null, $criteria)
                    ->filterByPlayer($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerItemsPartial && count($collPlayerItems)) {
                        $this->initPlayerItems(false);

                        foreach ($collPlayerItems as $obj) {
                            if (false == $this->collPlayerItems->contains($obj)) {
                                $this->collPlayerItems->append($obj);
                            }
                        }

                        $this->collPlayerItemsPartial = true;
                    }

                    return $collPlayerItems;
                }

                if ($partial && $this->collPlayerItems) {
                    foreach ($this->collPlayerItems as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerItems[] = $obj;
                        }
                    }
                }

                $this->collPlayerItems = $collPlayerItems;
                $this->collPlayerItemsPartial = false;
            }
        }

        return $this->collPlayerItems;
    }

    /**
     * Sets a collection of ChildPlayerItem objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerItems A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayer The current object (for fluent API support)
     */
    public function setPlayerItems(Collection $playerItems, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerItem[] $playerItemsToDelete */
        $playerItemsToDelete = $this->getPlayerItems(new Criteria(), $con)->diff($playerItems);


        $this->playerItemsScheduledForDeletion = $playerItemsToDelete;

        foreach ($playerItemsToDelete as $playerItemRemoved) {
            $playerItemRemoved->setPlayer(null);
        }

        $this->collPlayerItems = null;
        foreach ($playerItems as $playerItem) {
            $this->addPlayerItem($playerItem);
        }

        $this->collPlayerItems = $playerItems;
        $this->collPlayerItemsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PlayerItem objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PlayerItem objects.
     * @throws PropelException
     */
    public function countPlayerItems(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerItemsPartial && !$this->isNew();
        if (null === $this->collPlayerItems || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerItems) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerItems());
            }

            $query = ChildPlayerItemQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayer($this)
                ->count($con);
        }

        return count($this->collPlayerItems);
    }

    /**
     * Method called to associate a ChildPlayerItem object to this object
     * through the ChildPlayerItem foreign key attribute.
     *
     * @param  ChildPlayerItem $l ChildPlayerItem
     * @return $this|\app\model\Player The current object (for fluent API support)
     */
    public function addPlayerItem(ChildPlayerItem $l)
    {
        if ($this->collPlayerItems === null) {
            $this->initPlayerItems();
            $this->collPlayerItemsPartial = true;
        }

        if (!$this->collPlayerItems->contains($l)) {
            $this->doAddPlayerItem($l);

            if ($this->playerItemsScheduledForDeletion and $this->playerItemsScheduledForDeletion->contains($l)) {
                $this->playerItemsScheduledForDeletion->remove($this->playerItemsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerItem $playerItem The ChildPlayerItem object to add.
     */
    protected function doAddPlayerItem(ChildPlayerItem $playerItem)
    {
        $this->collPlayerItems[]= $playerItem;
        $playerItem->setPlayer($this);
    }

    /**
     * @param  ChildPlayerItem $playerItem The ChildPlayerItem object to remove.
     * @return $this|ChildPlayer The current object (for fluent API support)
     */
    public function removePlayerItem(ChildPlayerItem $playerItem)
    {
        if ($this->getPlayerItems()->contains($playerItem)) {
            $pos = $this->collPlayerItems->search($playerItem);
            $this->collPlayerItems->remove($pos);
            if (null === $this->playerItemsScheduledForDeletion) {
                $this->playerItemsScheduledForDeletion = clone $this->collPlayerItems;
                $this->playerItemsScheduledForDeletion->clear();
            }
            $this->playerItemsScheduledForDeletion[]= clone $playerItem;
            $playerItem->setPlayer(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Player is new, it will return
     * an empty collection; or if this Player has previously
     * been saved, it will retrieve related PlayerItems from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Player.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerItem[] List of ChildPlayerItem objects
     */
    public function getPlayerItemsJoinItem(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerItemQuery::create(null, $criteria);
        $query->joinWith('Item', $joinBehavior);

        return $this->getPlayerItems($query, $con);
    }

    /**
     * Clears out the collPlayerDecks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerDecks()
     */
    public function clearPlayerDecks()
    {
        $this->collPlayerDecks = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerDecks collection loaded partially.
     */
    public function resetPartialPlayerDecks($v = true)
    {
        $this->collPlayerDecksPartial = $v;
    }

    /**
     * Initializes the collPlayerDecks collection.
     *
     * By default this just sets the collPlayerDecks collection to an empty array (like clearcollPlayerDecks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerDecks($overrideExisting = true)
    {
        if (null !== $this->collPlayerDecks && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerDeckTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerDecks = new $collectionClassName;
        $this->collPlayerDecks->setModel('\app\model\PlayerDeck');
    }

    /**
     * Gets an array of ChildPlayerDeck objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayer is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     * @throws PropelException
     */
    public function getPlayerDecks(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksPartial && !$this->isNew();
        if (null === $this->collPlayerDecks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecks) {
                // return empty collection
                $this->initPlayerDecks();
            } else {
                $collPlayerDecks = ChildPlayerDeckQuery::create(null, $criteria)
                    ->filterByPlayer($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerDecksPartial && count($collPlayerDecks)) {
                        $this->initPlayerDecks(false);

                        foreach ($collPlayerDecks as $obj) {
                            if (false == $this->collPlayerDecks->contains($obj)) {
                                $this->collPlayerDecks->append($obj);
                            }
                        }

                        $this->collPlayerDecksPartial = true;
                    }

                    return $collPlayerDecks;
                }

                if ($partial && $this->collPlayerDecks) {
                    foreach ($this->collPlayerDecks as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerDecks[] = $obj;
                        }
                    }
                }

                $this->collPlayerDecks = $collPlayerDecks;
                $this->collPlayerDecksPartial = false;
            }
        }

        return $this->collPlayerDecks;
    }

    /**
     * Sets a collection of ChildPlayerDeck objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerDecks A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayer The current object (for fluent API support)
     */
    public function setPlayerDecks(Collection $playerDecks, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerDeck[] $playerDecksToDelete */
        $playerDecksToDelete = $this->getPlayerDecks(new Criteria(), $con)->diff($playerDecks);


        $this->playerDecksScheduledForDeletion = $playerDecksToDelete;

        foreach ($playerDecksToDelete as $playerDeckRemoved) {
            $playerDeckRemoved->setPlayer(null);
        }

        $this->collPlayerDecks = null;
        foreach ($playerDecks as $playerDeck) {
            $this->addPlayerDeck($playerDeck);
        }

        $this->collPlayerDecks = $playerDecks;
        $this->collPlayerDecksPartial = false;

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
    public function countPlayerDecks(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksPartial && !$this->isNew();
        if (null === $this->collPlayerDecks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecks) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerDecks());
            }

            $query = ChildPlayerDeckQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayer($this)
                ->count($con);
        }

        return count($this->collPlayerDecks);
    }

    /**
     * Method called to associate a ChildPlayerDeck object to this object
     * through the ChildPlayerDeck foreign key attribute.
     *
     * @param  ChildPlayerDeck $l ChildPlayerDeck
     * @return $this|\app\model\Player The current object (for fluent API support)
     */
    public function addPlayerDeck(ChildPlayerDeck $l)
    {
        if ($this->collPlayerDecks === null) {
            $this->initPlayerDecks();
            $this->collPlayerDecksPartial = true;
        }

        if (!$this->collPlayerDecks->contains($l)) {
            $this->doAddPlayerDeck($l);

            if ($this->playerDecksScheduledForDeletion and $this->playerDecksScheduledForDeletion->contains($l)) {
                $this->playerDecksScheduledForDeletion->remove($this->playerDecksScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerDeck $playerDeck The ChildPlayerDeck object to add.
     */
    protected function doAddPlayerDeck(ChildPlayerDeck $playerDeck)
    {
        $this->collPlayerDecks[]= $playerDeck;
        $playerDeck->setPlayer($this);
    }

    /**
     * @param  ChildPlayerDeck $playerDeck The ChildPlayerDeck object to remove.
     * @return $this|ChildPlayer The current object (for fluent API support)
     */
    public function removePlayerDeck(ChildPlayerDeck $playerDeck)
    {
        if ($this->getPlayerDecks()->contains($playerDeck)) {
            $pos = $this->collPlayerDecks->search($playerDeck);
            $this->collPlayerDecks->remove($pos);
            if (null === $this->playerDecksScheduledForDeletion) {
                $this->playerDecksScheduledForDeletion = clone $this->collPlayerDecks;
                $this->playerDecksScheduledForDeletion->clear();
            }
            $this->playerDecksScheduledForDeletion[]= clone $playerDeck;
            $playerDeck->setPlayer(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Player is new, it will return
     * an empty collection; or if this Player has previously
     * been saved, it will retrieve related PlayerDecks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Player.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     */
    public function getPlayerDecksJoinPlayerItemRelatedByPlayerItem1Id(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerDeckQuery::create(null, $criteria);
        $query->joinWith('PlayerItemRelatedByPlayerItem1Id', $joinBehavior);

        return $this->getPlayerDecks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Player is new, it will return
     * an empty collection; or if this Player has previously
     * been saved, it will retrieve related PlayerDecks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Player.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     */
    public function getPlayerDecksJoinPlayerItemRelatedByPlayerItem2Id(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerDeckQuery::create(null, $criteria);
        $query->joinWith('PlayerItemRelatedByPlayerItem2Id', $joinBehavior);

        return $this->getPlayerDecks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Player is new, it will return
     * an empty collection; or if this Player has previously
     * been saved, it will retrieve related PlayerDecks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Player.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     */
    public function getPlayerDecksJoinPlayerItemRelatedByPlayerItem3Id(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerDeckQuery::create(null, $criteria);
        $query->joinWith('PlayerItemRelatedByPlayerItem3Id', $joinBehavior);

        return $this->getPlayerDecks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Player is new, it will return
     * an empty collection; or if this Player has previously
     * been saved, it will retrieve related PlayerDecks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Player.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     */
    public function getPlayerDecksJoinPlayerItemRelatedByPlayerItem4Id(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerDeckQuery::create(null, $criteria);
        $query->joinWith('PlayerItemRelatedByPlayerItem4Id', $joinBehavior);

        return $this->getPlayerDecks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Player is new, it will return
     * an empty collection; or if this Player has previously
     * been saved, it will retrieve related PlayerDecks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Player.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     */
    public function getPlayerDecksJoinPlayerItemRelatedByPlayerItem5Id(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerDeckQuery::create(null, $criteria);
        $query->joinWith('PlayerItemRelatedByPlayerItem5Id', $joinBehavior);

        return $this->getPlayerDecks($query, $con);
    }

    /**
     * Clears out the collPlayerBattleLogsRelatedByPlayerId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerBattleLogsRelatedByPlayerId()
     */
    public function clearPlayerBattleLogsRelatedByPlayerId()
    {
        $this->collPlayerBattleLogsRelatedByPlayerId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerBattleLogsRelatedByPlayerId collection loaded partially.
     */
    public function resetPartialPlayerBattleLogsRelatedByPlayerId($v = true)
    {
        $this->collPlayerBattleLogsRelatedByPlayerIdPartial = $v;
    }

    /**
     * Initializes the collPlayerBattleLogsRelatedByPlayerId collection.
     *
     * By default this just sets the collPlayerBattleLogsRelatedByPlayerId collection to an empty array (like clearcollPlayerBattleLogsRelatedByPlayerId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerBattleLogsRelatedByPlayerId($overrideExisting = true)
    {
        if (null !== $this->collPlayerBattleLogsRelatedByPlayerId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerBattleLogTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerBattleLogsRelatedByPlayerId = new $collectionClassName;
        $this->collPlayerBattleLogsRelatedByPlayerId->setModel('\app\model\PlayerBattleLog');
    }

    /**
     * Gets an array of ChildPlayerBattleLog objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayer is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerBattleLog[] List of ChildPlayerBattleLog objects
     * @throws PropelException
     */
    public function getPlayerBattleLogsRelatedByPlayerId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerBattleLogsRelatedByPlayerIdPartial && !$this->isNew();
        if (null === $this->collPlayerBattleLogsRelatedByPlayerId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerBattleLogsRelatedByPlayerId) {
                // return empty collection
                $this->initPlayerBattleLogsRelatedByPlayerId();
            } else {
                $collPlayerBattleLogsRelatedByPlayerId = ChildPlayerBattleLogQuery::create(null, $criteria)
                    ->filterByPlayerRelatedByPlayerId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerBattleLogsRelatedByPlayerIdPartial && count($collPlayerBattleLogsRelatedByPlayerId)) {
                        $this->initPlayerBattleLogsRelatedByPlayerId(false);

                        foreach ($collPlayerBattleLogsRelatedByPlayerId as $obj) {
                            if (false == $this->collPlayerBattleLogsRelatedByPlayerId->contains($obj)) {
                                $this->collPlayerBattleLogsRelatedByPlayerId->append($obj);
                            }
                        }

                        $this->collPlayerBattleLogsRelatedByPlayerIdPartial = true;
                    }

                    return $collPlayerBattleLogsRelatedByPlayerId;
                }

                if ($partial && $this->collPlayerBattleLogsRelatedByPlayerId) {
                    foreach ($this->collPlayerBattleLogsRelatedByPlayerId as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerBattleLogsRelatedByPlayerId[] = $obj;
                        }
                    }
                }

                $this->collPlayerBattleLogsRelatedByPlayerId = $collPlayerBattleLogsRelatedByPlayerId;
                $this->collPlayerBattleLogsRelatedByPlayerIdPartial = false;
            }
        }

        return $this->collPlayerBattleLogsRelatedByPlayerId;
    }

    /**
     * Sets a collection of ChildPlayerBattleLog objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerBattleLogsRelatedByPlayerId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayer The current object (for fluent API support)
     */
    public function setPlayerBattleLogsRelatedByPlayerId(Collection $playerBattleLogsRelatedByPlayerId, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerBattleLog[] $playerBattleLogsRelatedByPlayerIdToDelete */
        $playerBattleLogsRelatedByPlayerIdToDelete = $this->getPlayerBattleLogsRelatedByPlayerId(new Criteria(), $con)->diff($playerBattleLogsRelatedByPlayerId);


        $this->playerBattleLogsRelatedByPlayerIdScheduledForDeletion = $playerBattleLogsRelatedByPlayerIdToDelete;

        foreach ($playerBattleLogsRelatedByPlayerIdToDelete as $playerBattleLogRelatedByPlayerIdRemoved) {
            $playerBattleLogRelatedByPlayerIdRemoved->setPlayerRelatedByPlayerId(null);
        }

        $this->collPlayerBattleLogsRelatedByPlayerId = null;
        foreach ($playerBattleLogsRelatedByPlayerId as $playerBattleLogRelatedByPlayerId) {
            $this->addPlayerBattleLogRelatedByPlayerId($playerBattleLogRelatedByPlayerId);
        }

        $this->collPlayerBattleLogsRelatedByPlayerId = $playerBattleLogsRelatedByPlayerId;
        $this->collPlayerBattleLogsRelatedByPlayerIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PlayerBattleLog objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PlayerBattleLog objects.
     * @throws PropelException
     */
    public function countPlayerBattleLogsRelatedByPlayerId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerBattleLogsRelatedByPlayerIdPartial && !$this->isNew();
        if (null === $this->collPlayerBattleLogsRelatedByPlayerId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerBattleLogsRelatedByPlayerId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerBattleLogsRelatedByPlayerId());
            }

            $query = ChildPlayerBattleLogQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerRelatedByPlayerId($this)
                ->count($con);
        }

        return count($this->collPlayerBattleLogsRelatedByPlayerId);
    }

    /**
     * Method called to associate a ChildPlayerBattleLog object to this object
     * through the ChildPlayerBattleLog foreign key attribute.
     *
     * @param  ChildPlayerBattleLog $l ChildPlayerBattleLog
     * @return $this|\app\model\Player The current object (for fluent API support)
     */
    public function addPlayerBattleLogRelatedByPlayerId(ChildPlayerBattleLog $l)
    {
        if ($this->collPlayerBattleLogsRelatedByPlayerId === null) {
            $this->initPlayerBattleLogsRelatedByPlayerId();
            $this->collPlayerBattleLogsRelatedByPlayerIdPartial = true;
        }

        if (!$this->collPlayerBattleLogsRelatedByPlayerId->contains($l)) {
            $this->doAddPlayerBattleLogRelatedByPlayerId($l);

            if ($this->playerBattleLogsRelatedByPlayerIdScheduledForDeletion and $this->playerBattleLogsRelatedByPlayerIdScheduledForDeletion->contains($l)) {
                $this->playerBattleLogsRelatedByPlayerIdScheduledForDeletion->remove($this->playerBattleLogsRelatedByPlayerIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerBattleLog $playerBattleLogRelatedByPlayerId The ChildPlayerBattleLog object to add.
     */
    protected function doAddPlayerBattleLogRelatedByPlayerId(ChildPlayerBattleLog $playerBattleLogRelatedByPlayerId)
    {
        $this->collPlayerBattleLogsRelatedByPlayerId[]= $playerBattleLogRelatedByPlayerId;
        $playerBattleLogRelatedByPlayerId->setPlayerRelatedByPlayerId($this);
    }

    /**
     * @param  ChildPlayerBattleLog $playerBattleLogRelatedByPlayerId The ChildPlayerBattleLog object to remove.
     * @return $this|ChildPlayer The current object (for fluent API support)
     */
    public function removePlayerBattleLogRelatedByPlayerId(ChildPlayerBattleLog $playerBattleLogRelatedByPlayerId)
    {
        if ($this->getPlayerBattleLogsRelatedByPlayerId()->contains($playerBattleLogRelatedByPlayerId)) {
            $pos = $this->collPlayerBattleLogsRelatedByPlayerId->search($playerBattleLogRelatedByPlayerId);
            $this->collPlayerBattleLogsRelatedByPlayerId->remove($pos);
            if (null === $this->playerBattleLogsRelatedByPlayerIdScheduledForDeletion) {
                $this->playerBattleLogsRelatedByPlayerIdScheduledForDeletion = clone $this->collPlayerBattleLogsRelatedByPlayerId;
                $this->playerBattleLogsRelatedByPlayerIdScheduledForDeletion->clear();
            }
            $this->playerBattleLogsRelatedByPlayerIdScheduledForDeletion[]= clone $playerBattleLogRelatedByPlayerId;
            $playerBattleLogRelatedByPlayerId->setPlayerRelatedByPlayerId(null);
        }

        return $this;
    }

    /**
     * Clears out the collPlayerBattleLogsRelatedByEnemyPlayerId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerBattleLogsRelatedByEnemyPlayerId()
     */
    public function clearPlayerBattleLogsRelatedByEnemyPlayerId()
    {
        $this->collPlayerBattleLogsRelatedByEnemyPlayerId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerBattleLogsRelatedByEnemyPlayerId collection loaded partially.
     */
    public function resetPartialPlayerBattleLogsRelatedByEnemyPlayerId($v = true)
    {
        $this->collPlayerBattleLogsRelatedByEnemyPlayerIdPartial = $v;
    }

    /**
     * Initializes the collPlayerBattleLogsRelatedByEnemyPlayerId collection.
     *
     * By default this just sets the collPlayerBattleLogsRelatedByEnemyPlayerId collection to an empty array (like clearcollPlayerBattleLogsRelatedByEnemyPlayerId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerBattleLogsRelatedByEnemyPlayerId($overrideExisting = true)
    {
        if (null !== $this->collPlayerBattleLogsRelatedByEnemyPlayerId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerBattleLogTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerBattleLogsRelatedByEnemyPlayerId = new $collectionClassName;
        $this->collPlayerBattleLogsRelatedByEnemyPlayerId->setModel('\app\model\PlayerBattleLog');
    }

    /**
     * Gets an array of ChildPlayerBattleLog objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayer is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerBattleLog[] List of ChildPlayerBattleLog objects
     * @throws PropelException
     */
    public function getPlayerBattleLogsRelatedByEnemyPlayerId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerBattleLogsRelatedByEnemyPlayerIdPartial && !$this->isNew();
        if (null === $this->collPlayerBattleLogsRelatedByEnemyPlayerId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerBattleLogsRelatedByEnemyPlayerId) {
                // return empty collection
                $this->initPlayerBattleLogsRelatedByEnemyPlayerId();
            } else {
                $collPlayerBattleLogsRelatedByEnemyPlayerId = ChildPlayerBattleLogQuery::create(null, $criteria)
                    ->filterByPlayerRelatedByEnemyPlayerId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerBattleLogsRelatedByEnemyPlayerIdPartial && count($collPlayerBattleLogsRelatedByEnemyPlayerId)) {
                        $this->initPlayerBattleLogsRelatedByEnemyPlayerId(false);

                        foreach ($collPlayerBattleLogsRelatedByEnemyPlayerId as $obj) {
                            if (false == $this->collPlayerBattleLogsRelatedByEnemyPlayerId->contains($obj)) {
                                $this->collPlayerBattleLogsRelatedByEnemyPlayerId->append($obj);
                            }
                        }

                        $this->collPlayerBattleLogsRelatedByEnemyPlayerIdPartial = true;
                    }

                    return $collPlayerBattleLogsRelatedByEnemyPlayerId;
                }

                if ($partial && $this->collPlayerBattleLogsRelatedByEnemyPlayerId) {
                    foreach ($this->collPlayerBattleLogsRelatedByEnemyPlayerId as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerBattleLogsRelatedByEnemyPlayerId[] = $obj;
                        }
                    }
                }

                $this->collPlayerBattleLogsRelatedByEnemyPlayerId = $collPlayerBattleLogsRelatedByEnemyPlayerId;
                $this->collPlayerBattleLogsRelatedByEnemyPlayerIdPartial = false;
            }
        }

        return $this->collPlayerBattleLogsRelatedByEnemyPlayerId;
    }

    /**
     * Sets a collection of ChildPlayerBattleLog objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerBattleLogsRelatedByEnemyPlayerId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayer The current object (for fluent API support)
     */
    public function setPlayerBattleLogsRelatedByEnemyPlayerId(Collection $playerBattleLogsRelatedByEnemyPlayerId, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerBattleLog[] $playerBattleLogsRelatedByEnemyPlayerIdToDelete */
        $playerBattleLogsRelatedByEnemyPlayerIdToDelete = $this->getPlayerBattleLogsRelatedByEnemyPlayerId(new Criteria(), $con)->diff($playerBattleLogsRelatedByEnemyPlayerId);


        $this->playerBattleLogsRelatedByEnemyPlayerIdScheduledForDeletion = $playerBattleLogsRelatedByEnemyPlayerIdToDelete;

        foreach ($playerBattleLogsRelatedByEnemyPlayerIdToDelete as $playerBattleLogRelatedByEnemyPlayerIdRemoved) {
            $playerBattleLogRelatedByEnemyPlayerIdRemoved->setPlayerRelatedByEnemyPlayerId(null);
        }

        $this->collPlayerBattleLogsRelatedByEnemyPlayerId = null;
        foreach ($playerBattleLogsRelatedByEnemyPlayerId as $playerBattleLogRelatedByEnemyPlayerId) {
            $this->addPlayerBattleLogRelatedByEnemyPlayerId($playerBattleLogRelatedByEnemyPlayerId);
        }

        $this->collPlayerBattleLogsRelatedByEnemyPlayerId = $playerBattleLogsRelatedByEnemyPlayerId;
        $this->collPlayerBattleLogsRelatedByEnemyPlayerIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PlayerBattleLog objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PlayerBattleLog objects.
     * @throws PropelException
     */
    public function countPlayerBattleLogsRelatedByEnemyPlayerId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerBattleLogsRelatedByEnemyPlayerIdPartial && !$this->isNew();
        if (null === $this->collPlayerBattleLogsRelatedByEnemyPlayerId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerBattleLogsRelatedByEnemyPlayerId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerBattleLogsRelatedByEnemyPlayerId());
            }

            $query = ChildPlayerBattleLogQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerRelatedByEnemyPlayerId($this)
                ->count($con);
        }

        return count($this->collPlayerBattleLogsRelatedByEnemyPlayerId);
    }

    /**
     * Method called to associate a ChildPlayerBattleLog object to this object
     * through the ChildPlayerBattleLog foreign key attribute.
     *
     * @param  ChildPlayerBattleLog $l ChildPlayerBattleLog
     * @return $this|\app\model\Player The current object (for fluent API support)
     */
    public function addPlayerBattleLogRelatedByEnemyPlayerId(ChildPlayerBattleLog $l)
    {
        if ($this->collPlayerBattleLogsRelatedByEnemyPlayerId === null) {
            $this->initPlayerBattleLogsRelatedByEnemyPlayerId();
            $this->collPlayerBattleLogsRelatedByEnemyPlayerIdPartial = true;
        }

        if (!$this->collPlayerBattleLogsRelatedByEnemyPlayerId->contains($l)) {
            $this->doAddPlayerBattleLogRelatedByEnemyPlayerId($l);

            if ($this->playerBattleLogsRelatedByEnemyPlayerIdScheduledForDeletion and $this->playerBattleLogsRelatedByEnemyPlayerIdScheduledForDeletion->contains($l)) {
                $this->playerBattleLogsRelatedByEnemyPlayerIdScheduledForDeletion->remove($this->playerBattleLogsRelatedByEnemyPlayerIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerBattleLog $playerBattleLogRelatedByEnemyPlayerId The ChildPlayerBattleLog object to add.
     */
    protected function doAddPlayerBattleLogRelatedByEnemyPlayerId(ChildPlayerBattleLog $playerBattleLogRelatedByEnemyPlayerId)
    {
        $this->collPlayerBattleLogsRelatedByEnemyPlayerId[]= $playerBattleLogRelatedByEnemyPlayerId;
        $playerBattleLogRelatedByEnemyPlayerId->setPlayerRelatedByEnemyPlayerId($this);
    }

    /**
     * @param  ChildPlayerBattleLog $playerBattleLogRelatedByEnemyPlayerId The ChildPlayerBattleLog object to remove.
     * @return $this|ChildPlayer The current object (for fluent API support)
     */
    public function removePlayerBattleLogRelatedByEnemyPlayerId(ChildPlayerBattleLog $playerBattleLogRelatedByEnemyPlayerId)
    {
        if ($this->getPlayerBattleLogsRelatedByEnemyPlayerId()->contains($playerBattleLogRelatedByEnemyPlayerId)) {
            $pos = $this->collPlayerBattleLogsRelatedByEnemyPlayerId->search($playerBattleLogRelatedByEnemyPlayerId);
            $this->collPlayerBattleLogsRelatedByEnemyPlayerId->remove($pos);
            if (null === $this->playerBattleLogsRelatedByEnemyPlayerIdScheduledForDeletion) {
                $this->playerBattleLogsRelatedByEnemyPlayerIdScheduledForDeletion = clone $this->collPlayerBattleLogsRelatedByEnemyPlayerId;
                $this->playerBattleLogsRelatedByEnemyPlayerIdScheduledForDeletion->clear();
            }
            $this->playerBattleLogsRelatedByEnemyPlayerIdScheduledForDeletion[]= clone $playerBattleLogRelatedByEnemyPlayerId;
            $playerBattleLogRelatedByEnemyPlayerId->setPlayerRelatedByEnemyPlayerId(null);
        }

        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
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
            if ($this->collPlayerItems) {
                foreach ($this->collPlayerItems as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerDecks) {
                foreach ($this->collPlayerDecks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerBattleLogsRelatedByPlayerId) {
                foreach ($this->collPlayerBattleLogsRelatedByPlayerId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerBattleLogsRelatedByEnemyPlayerId) {
                foreach ($this->collPlayerBattleLogsRelatedByEnemyPlayerId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collPlayerItems = null;
        $this->collPlayerDecks = null;
        $this->collPlayerBattleLogsRelatedByPlayerId = null;
        $this->collPlayerBattleLogsRelatedByEnemyPlayerId = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PlayerTableMap::DEFAULT_STRING_FORMAT);
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
