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
use app\model\Item as ChildItem;
use app\model\ItemQuery as ChildItemQuery;
use app\model\PlayerDeck as ChildPlayerDeck;
use app\model\PlayerDeckQuery as ChildPlayerDeckQuery;
use app\model\Proprium as ChildProprium;
use app\model\PropriumQuery as ChildPropriumQuery;
use app\model\Map\ItemTableMap;
use app\model\Map\PlayerDeckTableMap;
use app\model\Map\PropriumTableMap;

/**
 * Base class that represents a row from the 'proprium' table.
 *
 *
 *
 * @package    propel.generator.app.model.Base
 */
abstract class Proprium implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\app\\model\\Map\\PropriumTableMap';


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
     * @var        ObjectCollection|ChildItem[] Collection to store aggregation of ChildItem objects.
     */
    protected $collItems;
    protected $collItemsPartial;

    /**
     * @var        ObjectCollection|ChildPlayerDeck[] Collection to store aggregation of ChildPlayerDeck objects.
     */
    protected $collPlayerDecksRelatedByFirstPropriumId;
    protected $collPlayerDecksRelatedByFirstPropriumIdPartial;

    /**
     * @var        ObjectCollection|ChildPlayerDeck[] Collection to store aggregation of ChildPlayerDeck objects.
     */
    protected $collPlayerDecksRelatedBySecondPropriumId;
    protected $collPlayerDecksRelatedBySecondPropriumIdPartial;

    /**
     * @var        ObjectCollection|ChildPlayerDeck[] Collection to store aggregation of ChildPlayerDeck objects.
     */
    protected $collPlayerDecksRelatedByThirdPropriumId;
    protected $collPlayerDecksRelatedByThirdPropriumIdPartial;

    /**
     * @var        ObjectCollection|ChildPlayerDeck[] Collection to store aggregation of ChildPlayerDeck objects.
     */
    protected $collPlayerDecksRelatedByFourthPropriumId;
    protected $collPlayerDecksRelatedByFourthPropriumIdPartial;

    /**
     * @var        ObjectCollection|ChildPlayerDeck[] Collection to store aggregation of ChildPlayerDeck objects.
     */
    protected $collPlayerDecksRelatedByFifthPropriumId;
    protected $collPlayerDecksRelatedByFifthPropriumIdPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildItem[]
     */
    protected $itemsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerDeck[]
     */
    protected $playerDecksRelatedByFirstPropriumIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerDeck[]
     */
    protected $playerDecksRelatedBySecondPropriumIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerDeck[]
     */
    protected $playerDecksRelatedByThirdPropriumIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerDeck[]
     */
    protected $playerDecksRelatedByFourthPropriumIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerDeck[]
     */
    protected $playerDecksRelatedByFifthPropriumIdScheduledForDeletion = null;

    /**
     * Initializes internal state of app\model\Base\Proprium object.
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
     * Compares this with another <code>Proprium</code> instance.  If
     * <code>obj</code> is an instance of <code>Proprium</code>, delegates to
     * <code>equals(Proprium)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Proprium The current object, for fluid interface
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
     * @return $this|\app\model\Proprium The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[PropriumTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\app\model\Proprium The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[PropriumTableMap::COL_NAME] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PropriumTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PropriumTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 2; // 2 = PropriumTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\app\\model\\Proprium'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(PropriumTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildPropriumQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collItems = null;

            $this->collPlayerDecksRelatedByFirstPropriumId = null;

            $this->collPlayerDecksRelatedBySecondPropriumId = null;

            $this->collPlayerDecksRelatedByThirdPropriumId = null;

            $this->collPlayerDecksRelatedByFourthPropriumId = null;

            $this->collPlayerDecksRelatedByFifthPropriumId = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Proprium::setDeleted()
     * @see Proprium::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PropriumTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildPropriumQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(PropriumTableMap::DATABASE_NAME);
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
                PropriumTableMap::addInstanceToPool($this);
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

            if ($this->itemsScheduledForDeletion !== null) {
                if (!$this->itemsScheduledForDeletion->isEmpty()) {
                    \app\model\ItemQuery::create()
                        ->filterByPrimaryKeys($this->itemsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->itemsScheduledForDeletion = null;
                }
            }

            if ($this->collItems !== null) {
                foreach ($this->collItems as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerDecksRelatedByFirstPropriumIdScheduledForDeletion !== null) {
                if (!$this->playerDecksRelatedByFirstPropriumIdScheduledForDeletion->isEmpty()) {
                    \app\model\PlayerDeckQuery::create()
                        ->filterByPrimaryKeys($this->playerDecksRelatedByFirstPropriumIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerDecksRelatedByFirstPropriumIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerDecksRelatedByFirstPropriumId !== null) {
                foreach ($this->collPlayerDecksRelatedByFirstPropriumId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerDecksRelatedBySecondPropriumIdScheduledForDeletion !== null) {
                if (!$this->playerDecksRelatedBySecondPropriumIdScheduledForDeletion->isEmpty()) {
                    \app\model\PlayerDeckQuery::create()
                        ->filterByPrimaryKeys($this->playerDecksRelatedBySecondPropriumIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerDecksRelatedBySecondPropriumIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerDecksRelatedBySecondPropriumId !== null) {
                foreach ($this->collPlayerDecksRelatedBySecondPropriumId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerDecksRelatedByThirdPropriumIdScheduledForDeletion !== null) {
                if (!$this->playerDecksRelatedByThirdPropriumIdScheduledForDeletion->isEmpty()) {
                    \app\model\PlayerDeckQuery::create()
                        ->filterByPrimaryKeys($this->playerDecksRelatedByThirdPropriumIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerDecksRelatedByThirdPropriumIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerDecksRelatedByThirdPropriumId !== null) {
                foreach ($this->collPlayerDecksRelatedByThirdPropriumId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerDecksRelatedByFourthPropriumIdScheduledForDeletion !== null) {
                if (!$this->playerDecksRelatedByFourthPropriumIdScheduledForDeletion->isEmpty()) {
                    \app\model\PlayerDeckQuery::create()
                        ->filterByPrimaryKeys($this->playerDecksRelatedByFourthPropriumIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerDecksRelatedByFourthPropriumIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerDecksRelatedByFourthPropriumId !== null) {
                foreach ($this->collPlayerDecksRelatedByFourthPropriumId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerDecksRelatedByFifthPropriumIdScheduledForDeletion !== null) {
                if (!$this->playerDecksRelatedByFifthPropriumIdScheduledForDeletion->isEmpty()) {
                    \app\model\PlayerDeckQuery::create()
                        ->filterByPrimaryKeys($this->playerDecksRelatedByFifthPropriumIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerDecksRelatedByFifthPropriumIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerDecksRelatedByFifthPropriumId !== null) {
                foreach ($this->collPlayerDecksRelatedByFifthPropriumId as $referrerFK) {
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

        $this->modifiedColumns[PropriumTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PropriumTableMap::COL_ID . ')');
        }
        if (null === $this->id) {
            try {
                $dataFetcher = $con->query("SELECT nextval('proprium_id_seq')");
                $this->id = (int) $dataFetcher->fetchColumn();
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', 0, $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PropriumTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(PropriumTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }

        $sql = sprintf(
            'INSERT INTO proprium (%s) VALUES (%s)',
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
        $pos = PropriumTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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

        if (isset($alreadyDumpedObjects['Proprium'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Proprium'][$this->hashCode()] = true;
        $keys = PropriumTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collItems) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'items';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'items';
                        break;
                    default:
                        $key = 'Items';
                }

                $result[$key] = $this->collItems->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerDecksRelatedByFirstPropriumId) {

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

                $result[$key] = $this->collPlayerDecksRelatedByFirstPropriumId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerDecksRelatedBySecondPropriumId) {

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

                $result[$key] = $this->collPlayerDecksRelatedBySecondPropriumId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerDecksRelatedByThirdPropriumId) {

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

                $result[$key] = $this->collPlayerDecksRelatedByThirdPropriumId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerDecksRelatedByFourthPropriumId) {

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

                $result[$key] = $this->collPlayerDecksRelatedByFourthPropriumId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerDecksRelatedByFifthPropriumId) {

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

                $result[$key] = $this->collPlayerDecksRelatedByFifthPropriumId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\app\model\Proprium
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PropriumTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\app\model\Proprium
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
        $keys = PropriumTableMap::getFieldNames($keyType);

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
     * @return $this|\app\model\Proprium The current object, for fluid interface
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
        $criteria = new Criteria(PropriumTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PropriumTableMap::COL_ID)) {
            $criteria->add(PropriumTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(PropriumTableMap::COL_NAME)) {
            $criteria->add(PropriumTableMap::COL_NAME, $this->name);
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
        $criteria = ChildPropriumQuery::create();
        $criteria->add(PropriumTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \app\model\Proprium (or compatible) type.
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

            foreach ($this->getItems() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addItem($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerDecksRelatedByFirstPropriumId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerDeckRelatedByFirstPropriumId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerDecksRelatedBySecondPropriumId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerDeckRelatedBySecondPropriumId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerDecksRelatedByThirdPropriumId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerDeckRelatedByThirdPropriumId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerDecksRelatedByFourthPropriumId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerDeckRelatedByFourthPropriumId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerDecksRelatedByFifthPropriumId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerDeckRelatedByFifthPropriumId($relObj->copy($deepCopy));
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
     * @return \app\model\Proprium Clone of current object.
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
        if ('Item' == $relationName) {
            $this->initItems();
            return;
        }
        if ('PlayerDeckRelatedByFirstPropriumId' == $relationName) {
            $this->initPlayerDecksRelatedByFirstPropriumId();
            return;
        }
        if ('PlayerDeckRelatedBySecondPropriumId' == $relationName) {
            $this->initPlayerDecksRelatedBySecondPropriumId();
            return;
        }
        if ('PlayerDeckRelatedByThirdPropriumId' == $relationName) {
            $this->initPlayerDecksRelatedByThirdPropriumId();
            return;
        }
        if ('PlayerDeckRelatedByFourthPropriumId' == $relationName) {
            $this->initPlayerDecksRelatedByFourthPropriumId();
            return;
        }
        if ('PlayerDeckRelatedByFifthPropriumId' == $relationName) {
            $this->initPlayerDecksRelatedByFifthPropriumId();
            return;
        }
    }

    /**
     * Clears out the collItems collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addItems()
     */
    public function clearItems()
    {
        $this->collItems = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collItems collection loaded partially.
     */
    public function resetPartialItems($v = true)
    {
        $this->collItemsPartial = $v;
    }

    /**
     * Initializes the collItems collection.
     *
     * By default this just sets the collItems collection to an empty array (like clearcollItems());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initItems($overrideExisting = true)
    {
        if (null !== $this->collItems && !$overrideExisting) {
            return;
        }

        $collectionClassName = ItemTableMap::getTableMap()->getCollectionClassName();

        $this->collItems = new $collectionClassName;
        $this->collItems->setModel('\app\model\Item');
    }

    /**
     * Gets an array of ChildItem objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildProprium is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildItem[] List of ChildItem objects
     * @throws PropelException
     */
    public function getItems(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collItemsPartial && !$this->isNew();
        if (null === $this->collItems || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collItems) {
                // return empty collection
                $this->initItems();
            } else {
                $collItems = ChildItemQuery::create(null, $criteria)
                    ->filterByProprium($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collItemsPartial && count($collItems)) {
                        $this->initItems(false);

                        foreach ($collItems as $obj) {
                            if (false == $this->collItems->contains($obj)) {
                                $this->collItems->append($obj);
                            }
                        }

                        $this->collItemsPartial = true;
                    }

                    return $collItems;
                }

                if ($partial && $this->collItems) {
                    foreach ($this->collItems as $obj) {
                        if ($obj->isNew()) {
                            $collItems[] = $obj;
                        }
                    }
                }

                $this->collItems = $collItems;
                $this->collItemsPartial = false;
            }
        }

        return $this->collItems;
    }

    /**
     * Sets a collection of ChildItem objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $items A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildProprium The current object (for fluent API support)
     */
    public function setItems(Collection $items, ConnectionInterface $con = null)
    {
        /** @var ChildItem[] $itemsToDelete */
        $itemsToDelete = $this->getItems(new Criteria(), $con)->diff($items);


        $this->itemsScheduledForDeletion = $itemsToDelete;

        foreach ($itemsToDelete as $itemRemoved) {
            $itemRemoved->setProprium(null);
        }

        $this->collItems = null;
        foreach ($items as $item) {
            $this->addItem($item);
        }

        $this->collItems = $items;
        $this->collItemsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Item objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Item objects.
     * @throws PropelException
     */
    public function countItems(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collItemsPartial && !$this->isNew();
        if (null === $this->collItems || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collItems) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getItems());
            }

            $query = ChildItemQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByProprium($this)
                ->count($con);
        }

        return count($this->collItems);
    }

    /**
     * Method called to associate a ChildItem object to this object
     * through the ChildItem foreign key attribute.
     *
     * @param  ChildItem $l ChildItem
     * @return $this|\app\model\Proprium The current object (for fluent API support)
     */
    public function addItem(ChildItem $l)
    {
        if ($this->collItems === null) {
            $this->initItems();
            $this->collItemsPartial = true;
        }

        if (!$this->collItems->contains($l)) {
            $this->doAddItem($l);

            if ($this->itemsScheduledForDeletion and $this->itemsScheduledForDeletion->contains($l)) {
                $this->itemsScheduledForDeletion->remove($this->itemsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildItem $item The ChildItem object to add.
     */
    protected function doAddItem(ChildItem $item)
    {
        $this->collItems[]= $item;
        $item->setProprium($this);
    }

    /**
     * @param  ChildItem $item The ChildItem object to remove.
     * @return $this|ChildProprium The current object (for fluent API support)
     */
    public function removeItem(ChildItem $item)
    {
        if ($this->getItems()->contains($item)) {
            $pos = $this->collItems->search($item);
            $this->collItems->remove($pos);
            if (null === $this->itemsScheduledForDeletion) {
                $this->itemsScheduledForDeletion = clone $this->collItems;
                $this->itemsScheduledForDeletion->clear();
            }
            $this->itemsScheduledForDeletion[]= clone $item;
            $item->setProprium(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Proprium is new, it will return
     * an empty collection; or if this Proprium has previously
     * been saved, it will retrieve related Items from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Proprium.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildItem[] List of ChildItem objects
     */
    public function getItemsJoinPart(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildItemQuery::create(null, $criteria);
        $query->joinWith('Part', $joinBehavior);

        return $this->getItems($query, $con);
    }

    /**
     * Clears out the collPlayerDecksRelatedByFirstPropriumId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerDecksRelatedByFirstPropriumId()
     */
    public function clearPlayerDecksRelatedByFirstPropriumId()
    {
        $this->collPlayerDecksRelatedByFirstPropriumId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerDecksRelatedByFirstPropriumId collection loaded partially.
     */
    public function resetPartialPlayerDecksRelatedByFirstPropriumId($v = true)
    {
        $this->collPlayerDecksRelatedByFirstPropriumIdPartial = $v;
    }

    /**
     * Initializes the collPlayerDecksRelatedByFirstPropriumId collection.
     *
     * By default this just sets the collPlayerDecksRelatedByFirstPropriumId collection to an empty array (like clearcollPlayerDecksRelatedByFirstPropriumId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerDecksRelatedByFirstPropriumId($overrideExisting = true)
    {
        if (null !== $this->collPlayerDecksRelatedByFirstPropriumId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerDeckTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerDecksRelatedByFirstPropriumId = new $collectionClassName;
        $this->collPlayerDecksRelatedByFirstPropriumId->setModel('\app\model\PlayerDeck');
    }

    /**
     * Gets an array of ChildPlayerDeck objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildProprium is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     * @throws PropelException
     */
    public function getPlayerDecksRelatedByFirstPropriumId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByFirstPropriumIdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByFirstPropriumId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByFirstPropriumId) {
                // return empty collection
                $this->initPlayerDecksRelatedByFirstPropriumId();
            } else {
                $collPlayerDecksRelatedByFirstPropriumId = ChildPlayerDeckQuery::create(null, $criteria)
                    ->filterByPropriumRelatedByFirstPropriumId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerDecksRelatedByFirstPropriumIdPartial && count($collPlayerDecksRelatedByFirstPropriumId)) {
                        $this->initPlayerDecksRelatedByFirstPropriumId(false);

                        foreach ($collPlayerDecksRelatedByFirstPropriumId as $obj) {
                            if (false == $this->collPlayerDecksRelatedByFirstPropriumId->contains($obj)) {
                                $this->collPlayerDecksRelatedByFirstPropriumId->append($obj);
                            }
                        }

                        $this->collPlayerDecksRelatedByFirstPropriumIdPartial = true;
                    }

                    return $collPlayerDecksRelatedByFirstPropriumId;
                }

                if ($partial && $this->collPlayerDecksRelatedByFirstPropriumId) {
                    foreach ($this->collPlayerDecksRelatedByFirstPropriumId as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerDecksRelatedByFirstPropriumId[] = $obj;
                        }
                    }
                }

                $this->collPlayerDecksRelatedByFirstPropriumId = $collPlayerDecksRelatedByFirstPropriumId;
                $this->collPlayerDecksRelatedByFirstPropriumIdPartial = false;
            }
        }

        return $this->collPlayerDecksRelatedByFirstPropriumId;
    }

    /**
     * Sets a collection of ChildPlayerDeck objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerDecksRelatedByFirstPropriumId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildProprium The current object (for fluent API support)
     */
    public function setPlayerDecksRelatedByFirstPropriumId(Collection $playerDecksRelatedByFirstPropriumId, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerDeck[] $playerDecksRelatedByFirstPropriumIdToDelete */
        $playerDecksRelatedByFirstPropriumIdToDelete = $this->getPlayerDecksRelatedByFirstPropriumId(new Criteria(), $con)->diff($playerDecksRelatedByFirstPropriumId);


        $this->playerDecksRelatedByFirstPropriumIdScheduledForDeletion = $playerDecksRelatedByFirstPropriumIdToDelete;

        foreach ($playerDecksRelatedByFirstPropriumIdToDelete as $playerDeckRelatedByFirstPropriumIdRemoved) {
            $playerDeckRelatedByFirstPropriumIdRemoved->setPropriumRelatedByFirstPropriumId(null);
        }

        $this->collPlayerDecksRelatedByFirstPropriumId = null;
        foreach ($playerDecksRelatedByFirstPropriumId as $playerDeckRelatedByFirstPropriumId) {
            $this->addPlayerDeckRelatedByFirstPropriumId($playerDeckRelatedByFirstPropriumId);
        }

        $this->collPlayerDecksRelatedByFirstPropriumId = $playerDecksRelatedByFirstPropriumId;
        $this->collPlayerDecksRelatedByFirstPropriumIdPartial = false;

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
    public function countPlayerDecksRelatedByFirstPropriumId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByFirstPropriumIdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByFirstPropriumId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByFirstPropriumId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerDecksRelatedByFirstPropriumId());
            }

            $query = ChildPlayerDeckQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPropriumRelatedByFirstPropriumId($this)
                ->count($con);
        }

        return count($this->collPlayerDecksRelatedByFirstPropriumId);
    }

    /**
     * Method called to associate a ChildPlayerDeck object to this object
     * through the ChildPlayerDeck foreign key attribute.
     *
     * @param  ChildPlayerDeck $l ChildPlayerDeck
     * @return $this|\app\model\Proprium The current object (for fluent API support)
     */
    public function addPlayerDeckRelatedByFirstPropriumId(ChildPlayerDeck $l)
    {
        if ($this->collPlayerDecksRelatedByFirstPropriumId === null) {
            $this->initPlayerDecksRelatedByFirstPropriumId();
            $this->collPlayerDecksRelatedByFirstPropriumIdPartial = true;
        }

        if (!$this->collPlayerDecksRelatedByFirstPropriumId->contains($l)) {
            $this->doAddPlayerDeckRelatedByFirstPropriumId($l);

            if ($this->playerDecksRelatedByFirstPropriumIdScheduledForDeletion and $this->playerDecksRelatedByFirstPropriumIdScheduledForDeletion->contains($l)) {
                $this->playerDecksRelatedByFirstPropriumIdScheduledForDeletion->remove($this->playerDecksRelatedByFirstPropriumIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerDeck $playerDeckRelatedByFirstPropriumId The ChildPlayerDeck object to add.
     */
    protected function doAddPlayerDeckRelatedByFirstPropriumId(ChildPlayerDeck $playerDeckRelatedByFirstPropriumId)
    {
        $this->collPlayerDecksRelatedByFirstPropriumId[]= $playerDeckRelatedByFirstPropriumId;
        $playerDeckRelatedByFirstPropriumId->setPropriumRelatedByFirstPropriumId($this);
    }

    /**
     * @param  ChildPlayerDeck $playerDeckRelatedByFirstPropriumId The ChildPlayerDeck object to remove.
     * @return $this|ChildProprium The current object (for fluent API support)
     */
    public function removePlayerDeckRelatedByFirstPropriumId(ChildPlayerDeck $playerDeckRelatedByFirstPropriumId)
    {
        if ($this->getPlayerDecksRelatedByFirstPropriumId()->contains($playerDeckRelatedByFirstPropriumId)) {
            $pos = $this->collPlayerDecksRelatedByFirstPropriumId->search($playerDeckRelatedByFirstPropriumId);
            $this->collPlayerDecksRelatedByFirstPropriumId->remove($pos);
            if (null === $this->playerDecksRelatedByFirstPropriumIdScheduledForDeletion) {
                $this->playerDecksRelatedByFirstPropriumIdScheduledForDeletion = clone $this->collPlayerDecksRelatedByFirstPropriumId;
                $this->playerDecksRelatedByFirstPropriumIdScheduledForDeletion->clear();
            }
            $this->playerDecksRelatedByFirstPropriumIdScheduledForDeletion[]= clone $playerDeckRelatedByFirstPropriumId;
            $playerDeckRelatedByFirstPropriumId->setPropriumRelatedByFirstPropriumId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Proprium is new, it will return
     * an empty collection; or if this Proprium has previously
     * been saved, it will retrieve related PlayerDecksRelatedByFirstPropriumId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Proprium.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     */
    public function getPlayerDecksRelatedByFirstPropriumIdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerDeckQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerDecksRelatedByFirstPropriumId($query, $con);
    }

    /**
     * Clears out the collPlayerDecksRelatedBySecondPropriumId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerDecksRelatedBySecondPropriumId()
     */
    public function clearPlayerDecksRelatedBySecondPropriumId()
    {
        $this->collPlayerDecksRelatedBySecondPropriumId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerDecksRelatedBySecondPropriumId collection loaded partially.
     */
    public function resetPartialPlayerDecksRelatedBySecondPropriumId($v = true)
    {
        $this->collPlayerDecksRelatedBySecondPropriumIdPartial = $v;
    }

    /**
     * Initializes the collPlayerDecksRelatedBySecondPropriumId collection.
     *
     * By default this just sets the collPlayerDecksRelatedBySecondPropriumId collection to an empty array (like clearcollPlayerDecksRelatedBySecondPropriumId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerDecksRelatedBySecondPropriumId($overrideExisting = true)
    {
        if (null !== $this->collPlayerDecksRelatedBySecondPropriumId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerDeckTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerDecksRelatedBySecondPropriumId = new $collectionClassName;
        $this->collPlayerDecksRelatedBySecondPropriumId->setModel('\app\model\PlayerDeck');
    }

    /**
     * Gets an array of ChildPlayerDeck objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildProprium is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     * @throws PropelException
     */
    public function getPlayerDecksRelatedBySecondPropriumId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedBySecondPropriumIdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedBySecondPropriumId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedBySecondPropriumId) {
                // return empty collection
                $this->initPlayerDecksRelatedBySecondPropriumId();
            } else {
                $collPlayerDecksRelatedBySecondPropriumId = ChildPlayerDeckQuery::create(null, $criteria)
                    ->filterByPropriumRelatedBySecondPropriumId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerDecksRelatedBySecondPropriumIdPartial && count($collPlayerDecksRelatedBySecondPropriumId)) {
                        $this->initPlayerDecksRelatedBySecondPropriumId(false);

                        foreach ($collPlayerDecksRelatedBySecondPropriumId as $obj) {
                            if (false == $this->collPlayerDecksRelatedBySecondPropriumId->contains($obj)) {
                                $this->collPlayerDecksRelatedBySecondPropriumId->append($obj);
                            }
                        }

                        $this->collPlayerDecksRelatedBySecondPropriumIdPartial = true;
                    }

                    return $collPlayerDecksRelatedBySecondPropriumId;
                }

                if ($partial && $this->collPlayerDecksRelatedBySecondPropriumId) {
                    foreach ($this->collPlayerDecksRelatedBySecondPropriumId as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerDecksRelatedBySecondPropriumId[] = $obj;
                        }
                    }
                }

                $this->collPlayerDecksRelatedBySecondPropriumId = $collPlayerDecksRelatedBySecondPropriumId;
                $this->collPlayerDecksRelatedBySecondPropriumIdPartial = false;
            }
        }

        return $this->collPlayerDecksRelatedBySecondPropriumId;
    }

    /**
     * Sets a collection of ChildPlayerDeck objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerDecksRelatedBySecondPropriumId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildProprium The current object (for fluent API support)
     */
    public function setPlayerDecksRelatedBySecondPropriumId(Collection $playerDecksRelatedBySecondPropriumId, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerDeck[] $playerDecksRelatedBySecondPropriumIdToDelete */
        $playerDecksRelatedBySecondPropriumIdToDelete = $this->getPlayerDecksRelatedBySecondPropriumId(new Criteria(), $con)->diff($playerDecksRelatedBySecondPropriumId);


        $this->playerDecksRelatedBySecondPropriumIdScheduledForDeletion = $playerDecksRelatedBySecondPropriumIdToDelete;

        foreach ($playerDecksRelatedBySecondPropriumIdToDelete as $playerDeckRelatedBySecondPropriumIdRemoved) {
            $playerDeckRelatedBySecondPropriumIdRemoved->setPropriumRelatedBySecondPropriumId(null);
        }

        $this->collPlayerDecksRelatedBySecondPropriumId = null;
        foreach ($playerDecksRelatedBySecondPropriumId as $playerDeckRelatedBySecondPropriumId) {
            $this->addPlayerDeckRelatedBySecondPropriumId($playerDeckRelatedBySecondPropriumId);
        }

        $this->collPlayerDecksRelatedBySecondPropriumId = $playerDecksRelatedBySecondPropriumId;
        $this->collPlayerDecksRelatedBySecondPropriumIdPartial = false;

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
    public function countPlayerDecksRelatedBySecondPropriumId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedBySecondPropriumIdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedBySecondPropriumId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedBySecondPropriumId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerDecksRelatedBySecondPropriumId());
            }

            $query = ChildPlayerDeckQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPropriumRelatedBySecondPropriumId($this)
                ->count($con);
        }

        return count($this->collPlayerDecksRelatedBySecondPropriumId);
    }

    /**
     * Method called to associate a ChildPlayerDeck object to this object
     * through the ChildPlayerDeck foreign key attribute.
     *
     * @param  ChildPlayerDeck $l ChildPlayerDeck
     * @return $this|\app\model\Proprium The current object (for fluent API support)
     */
    public function addPlayerDeckRelatedBySecondPropriumId(ChildPlayerDeck $l)
    {
        if ($this->collPlayerDecksRelatedBySecondPropriumId === null) {
            $this->initPlayerDecksRelatedBySecondPropriumId();
            $this->collPlayerDecksRelatedBySecondPropriumIdPartial = true;
        }

        if (!$this->collPlayerDecksRelatedBySecondPropriumId->contains($l)) {
            $this->doAddPlayerDeckRelatedBySecondPropriumId($l);

            if ($this->playerDecksRelatedBySecondPropriumIdScheduledForDeletion and $this->playerDecksRelatedBySecondPropriumIdScheduledForDeletion->contains($l)) {
                $this->playerDecksRelatedBySecondPropriumIdScheduledForDeletion->remove($this->playerDecksRelatedBySecondPropriumIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerDeck $playerDeckRelatedBySecondPropriumId The ChildPlayerDeck object to add.
     */
    protected function doAddPlayerDeckRelatedBySecondPropriumId(ChildPlayerDeck $playerDeckRelatedBySecondPropriumId)
    {
        $this->collPlayerDecksRelatedBySecondPropriumId[]= $playerDeckRelatedBySecondPropriumId;
        $playerDeckRelatedBySecondPropriumId->setPropriumRelatedBySecondPropriumId($this);
    }

    /**
     * @param  ChildPlayerDeck $playerDeckRelatedBySecondPropriumId The ChildPlayerDeck object to remove.
     * @return $this|ChildProprium The current object (for fluent API support)
     */
    public function removePlayerDeckRelatedBySecondPropriumId(ChildPlayerDeck $playerDeckRelatedBySecondPropriumId)
    {
        if ($this->getPlayerDecksRelatedBySecondPropriumId()->contains($playerDeckRelatedBySecondPropriumId)) {
            $pos = $this->collPlayerDecksRelatedBySecondPropriumId->search($playerDeckRelatedBySecondPropriumId);
            $this->collPlayerDecksRelatedBySecondPropriumId->remove($pos);
            if (null === $this->playerDecksRelatedBySecondPropriumIdScheduledForDeletion) {
                $this->playerDecksRelatedBySecondPropriumIdScheduledForDeletion = clone $this->collPlayerDecksRelatedBySecondPropriumId;
                $this->playerDecksRelatedBySecondPropriumIdScheduledForDeletion->clear();
            }
            $this->playerDecksRelatedBySecondPropriumIdScheduledForDeletion[]= clone $playerDeckRelatedBySecondPropriumId;
            $playerDeckRelatedBySecondPropriumId->setPropriumRelatedBySecondPropriumId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Proprium is new, it will return
     * an empty collection; or if this Proprium has previously
     * been saved, it will retrieve related PlayerDecksRelatedBySecondPropriumId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Proprium.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     */
    public function getPlayerDecksRelatedBySecondPropriumIdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerDeckQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerDecksRelatedBySecondPropriumId($query, $con);
    }

    /**
     * Clears out the collPlayerDecksRelatedByThirdPropriumId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerDecksRelatedByThirdPropriumId()
     */
    public function clearPlayerDecksRelatedByThirdPropriumId()
    {
        $this->collPlayerDecksRelatedByThirdPropriumId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerDecksRelatedByThirdPropriumId collection loaded partially.
     */
    public function resetPartialPlayerDecksRelatedByThirdPropriumId($v = true)
    {
        $this->collPlayerDecksRelatedByThirdPropriumIdPartial = $v;
    }

    /**
     * Initializes the collPlayerDecksRelatedByThirdPropriumId collection.
     *
     * By default this just sets the collPlayerDecksRelatedByThirdPropriumId collection to an empty array (like clearcollPlayerDecksRelatedByThirdPropriumId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerDecksRelatedByThirdPropriumId($overrideExisting = true)
    {
        if (null !== $this->collPlayerDecksRelatedByThirdPropriumId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerDeckTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerDecksRelatedByThirdPropriumId = new $collectionClassName;
        $this->collPlayerDecksRelatedByThirdPropriumId->setModel('\app\model\PlayerDeck');
    }

    /**
     * Gets an array of ChildPlayerDeck objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildProprium is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     * @throws PropelException
     */
    public function getPlayerDecksRelatedByThirdPropriumId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByThirdPropriumIdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByThirdPropriumId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByThirdPropriumId) {
                // return empty collection
                $this->initPlayerDecksRelatedByThirdPropriumId();
            } else {
                $collPlayerDecksRelatedByThirdPropriumId = ChildPlayerDeckQuery::create(null, $criteria)
                    ->filterByPropriumRelatedByThirdPropriumId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerDecksRelatedByThirdPropriumIdPartial && count($collPlayerDecksRelatedByThirdPropriumId)) {
                        $this->initPlayerDecksRelatedByThirdPropriumId(false);

                        foreach ($collPlayerDecksRelatedByThirdPropriumId as $obj) {
                            if (false == $this->collPlayerDecksRelatedByThirdPropriumId->contains($obj)) {
                                $this->collPlayerDecksRelatedByThirdPropriumId->append($obj);
                            }
                        }

                        $this->collPlayerDecksRelatedByThirdPropriumIdPartial = true;
                    }

                    return $collPlayerDecksRelatedByThirdPropriumId;
                }

                if ($partial && $this->collPlayerDecksRelatedByThirdPropriumId) {
                    foreach ($this->collPlayerDecksRelatedByThirdPropriumId as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerDecksRelatedByThirdPropriumId[] = $obj;
                        }
                    }
                }

                $this->collPlayerDecksRelatedByThirdPropriumId = $collPlayerDecksRelatedByThirdPropriumId;
                $this->collPlayerDecksRelatedByThirdPropriumIdPartial = false;
            }
        }

        return $this->collPlayerDecksRelatedByThirdPropriumId;
    }

    /**
     * Sets a collection of ChildPlayerDeck objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerDecksRelatedByThirdPropriumId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildProprium The current object (for fluent API support)
     */
    public function setPlayerDecksRelatedByThirdPropriumId(Collection $playerDecksRelatedByThirdPropriumId, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerDeck[] $playerDecksRelatedByThirdPropriumIdToDelete */
        $playerDecksRelatedByThirdPropriumIdToDelete = $this->getPlayerDecksRelatedByThirdPropriumId(new Criteria(), $con)->diff($playerDecksRelatedByThirdPropriumId);


        $this->playerDecksRelatedByThirdPropriumIdScheduledForDeletion = $playerDecksRelatedByThirdPropriumIdToDelete;

        foreach ($playerDecksRelatedByThirdPropriumIdToDelete as $playerDeckRelatedByThirdPropriumIdRemoved) {
            $playerDeckRelatedByThirdPropriumIdRemoved->setPropriumRelatedByThirdPropriumId(null);
        }

        $this->collPlayerDecksRelatedByThirdPropriumId = null;
        foreach ($playerDecksRelatedByThirdPropriumId as $playerDeckRelatedByThirdPropriumId) {
            $this->addPlayerDeckRelatedByThirdPropriumId($playerDeckRelatedByThirdPropriumId);
        }

        $this->collPlayerDecksRelatedByThirdPropriumId = $playerDecksRelatedByThirdPropriumId;
        $this->collPlayerDecksRelatedByThirdPropriumIdPartial = false;

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
    public function countPlayerDecksRelatedByThirdPropriumId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByThirdPropriumIdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByThirdPropriumId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByThirdPropriumId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerDecksRelatedByThirdPropriumId());
            }

            $query = ChildPlayerDeckQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPropriumRelatedByThirdPropriumId($this)
                ->count($con);
        }

        return count($this->collPlayerDecksRelatedByThirdPropriumId);
    }

    /**
     * Method called to associate a ChildPlayerDeck object to this object
     * through the ChildPlayerDeck foreign key attribute.
     *
     * @param  ChildPlayerDeck $l ChildPlayerDeck
     * @return $this|\app\model\Proprium The current object (for fluent API support)
     */
    public function addPlayerDeckRelatedByThirdPropriumId(ChildPlayerDeck $l)
    {
        if ($this->collPlayerDecksRelatedByThirdPropriumId === null) {
            $this->initPlayerDecksRelatedByThirdPropriumId();
            $this->collPlayerDecksRelatedByThirdPropriumIdPartial = true;
        }

        if (!$this->collPlayerDecksRelatedByThirdPropriumId->contains($l)) {
            $this->doAddPlayerDeckRelatedByThirdPropriumId($l);

            if ($this->playerDecksRelatedByThirdPropriumIdScheduledForDeletion and $this->playerDecksRelatedByThirdPropriumIdScheduledForDeletion->contains($l)) {
                $this->playerDecksRelatedByThirdPropriumIdScheduledForDeletion->remove($this->playerDecksRelatedByThirdPropriumIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerDeck $playerDeckRelatedByThirdPropriumId The ChildPlayerDeck object to add.
     */
    protected function doAddPlayerDeckRelatedByThirdPropriumId(ChildPlayerDeck $playerDeckRelatedByThirdPropriumId)
    {
        $this->collPlayerDecksRelatedByThirdPropriumId[]= $playerDeckRelatedByThirdPropriumId;
        $playerDeckRelatedByThirdPropriumId->setPropriumRelatedByThirdPropriumId($this);
    }

    /**
     * @param  ChildPlayerDeck $playerDeckRelatedByThirdPropriumId The ChildPlayerDeck object to remove.
     * @return $this|ChildProprium The current object (for fluent API support)
     */
    public function removePlayerDeckRelatedByThirdPropriumId(ChildPlayerDeck $playerDeckRelatedByThirdPropriumId)
    {
        if ($this->getPlayerDecksRelatedByThirdPropriumId()->contains($playerDeckRelatedByThirdPropriumId)) {
            $pos = $this->collPlayerDecksRelatedByThirdPropriumId->search($playerDeckRelatedByThirdPropriumId);
            $this->collPlayerDecksRelatedByThirdPropriumId->remove($pos);
            if (null === $this->playerDecksRelatedByThirdPropriumIdScheduledForDeletion) {
                $this->playerDecksRelatedByThirdPropriumIdScheduledForDeletion = clone $this->collPlayerDecksRelatedByThirdPropriumId;
                $this->playerDecksRelatedByThirdPropriumIdScheduledForDeletion->clear();
            }
            $this->playerDecksRelatedByThirdPropriumIdScheduledForDeletion[]= clone $playerDeckRelatedByThirdPropriumId;
            $playerDeckRelatedByThirdPropriumId->setPropriumRelatedByThirdPropriumId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Proprium is new, it will return
     * an empty collection; or if this Proprium has previously
     * been saved, it will retrieve related PlayerDecksRelatedByThirdPropriumId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Proprium.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     */
    public function getPlayerDecksRelatedByThirdPropriumIdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerDeckQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerDecksRelatedByThirdPropriumId($query, $con);
    }

    /**
     * Clears out the collPlayerDecksRelatedByFourthPropriumId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerDecksRelatedByFourthPropriumId()
     */
    public function clearPlayerDecksRelatedByFourthPropriumId()
    {
        $this->collPlayerDecksRelatedByFourthPropriumId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerDecksRelatedByFourthPropriumId collection loaded partially.
     */
    public function resetPartialPlayerDecksRelatedByFourthPropriumId($v = true)
    {
        $this->collPlayerDecksRelatedByFourthPropriumIdPartial = $v;
    }

    /**
     * Initializes the collPlayerDecksRelatedByFourthPropriumId collection.
     *
     * By default this just sets the collPlayerDecksRelatedByFourthPropriumId collection to an empty array (like clearcollPlayerDecksRelatedByFourthPropriumId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerDecksRelatedByFourthPropriumId($overrideExisting = true)
    {
        if (null !== $this->collPlayerDecksRelatedByFourthPropriumId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerDeckTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerDecksRelatedByFourthPropriumId = new $collectionClassName;
        $this->collPlayerDecksRelatedByFourthPropriumId->setModel('\app\model\PlayerDeck');
    }

    /**
     * Gets an array of ChildPlayerDeck objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildProprium is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     * @throws PropelException
     */
    public function getPlayerDecksRelatedByFourthPropriumId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByFourthPropriumIdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByFourthPropriumId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByFourthPropriumId) {
                // return empty collection
                $this->initPlayerDecksRelatedByFourthPropriumId();
            } else {
                $collPlayerDecksRelatedByFourthPropriumId = ChildPlayerDeckQuery::create(null, $criteria)
                    ->filterByPropriumRelatedByFourthPropriumId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerDecksRelatedByFourthPropriumIdPartial && count($collPlayerDecksRelatedByFourthPropriumId)) {
                        $this->initPlayerDecksRelatedByFourthPropriumId(false);

                        foreach ($collPlayerDecksRelatedByFourthPropriumId as $obj) {
                            if (false == $this->collPlayerDecksRelatedByFourthPropriumId->contains($obj)) {
                                $this->collPlayerDecksRelatedByFourthPropriumId->append($obj);
                            }
                        }

                        $this->collPlayerDecksRelatedByFourthPropriumIdPartial = true;
                    }

                    return $collPlayerDecksRelatedByFourthPropriumId;
                }

                if ($partial && $this->collPlayerDecksRelatedByFourthPropriumId) {
                    foreach ($this->collPlayerDecksRelatedByFourthPropriumId as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerDecksRelatedByFourthPropriumId[] = $obj;
                        }
                    }
                }

                $this->collPlayerDecksRelatedByFourthPropriumId = $collPlayerDecksRelatedByFourthPropriumId;
                $this->collPlayerDecksRelatedByFourthPropriumIdPartial = false;
            }
        }

        return $this->collPlayerDecksRelatedByFourthPropriumId;
    }

    /**
     * Sets a collection of ChildPlayerDeck objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerDecksRelatedByFourthPropriumId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildProprium The current object (for fluent API support)
     */
    public function setPlayerDecksRelatedByFourthPropriumId(Collection $playerDecksRelatedByFourthPropriumId, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerDeck[] $playerDecksRelatedByFourthPropriumIdToDelete */
        $playerDecksRelatedByFourthPropriumIdToDelete = $this->getPlayerDecksRelatedByFourthPropriumId(new Criteria(), $con)->diff($playerDecksRelatedByFourthPropriumId);


        $this->playerDecksRelatedByFourthPropriumIdScheduledForDeletion = $playerDecksRelatedByFourthPropriumIdToDelete;

        foreach ($playerDecksRelatedByFourthPropriumIdToDelete as $playerDeckRelatedByFourthPropriumIdRemoved) {
            $playerDeckRelatedByFourthPropriumIdRemoved->setPropriumRelatedByFourthPropriumId(null);
        }

        $this->collPlayerDecksRelatedByFourthPropriumId = null;
        foreach ($playerDecksRelatedByFourthPropriumId as $playerDeckRelatedByFourthPropriumId) {
            $this->addPlayerDeckRelatedByFourthPropriumId($playerDeckRelatedByFourthPropriumId);
        }

        $this->collPlayerDecksRelatedByFourthPropriumId = $playerDecksRelatedByFourthPropriumId;
        $this->collPlayerDecksRelatedByFourthPropriumIdPartial = false;

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
    public function countPlayerDecksRelatedByFourthPropriumId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByFourthPropriumIdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByFourthPropriumId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByFourthPropriumId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerDecksRelatedByFourthPropriumId());
            }

            $query = ChildPlayerDeckQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPropriumRelatedByFourthPropriumId($this)
                ->count($con);
        }

        return count($this->collPlayerDecksRelatedByFourthPropriumId);
    }

    /**
     * Method called to associate a ChildPlayerDeck object to this object
     * through the ChildPlayerDeck foreign key attribute.
     *
     * @param  ChildPlayerDeck $l ChildPlayerDeck
     * @return $this|\app\model\Proprium The current object (for fluent API support)
     */
    public function addPlayerDeckRelatedByFourthPropriumId(ChildPlayerDeck $l)
    {
        if ($this->collPlayerDecksRelatedByFourthPropriumId === null) {
            $this->initPlayerDecksRelatedByFourthPropriumId();
            $this->collPlayerDecksRelatedByFourthPropriumIdPartial = true;
        }

        if (!$this->collPlayerDecksRelatedByFourthPropriumId->contains($l)) {
            $this->doAddPlayerDeckRelatedByFourthPropriumId($l);

            if ($this->playerDecksRelatedByFourthPropriumIdScheduledForDeletion and $this->playerDecksRelatedByFourthPropriumIdScheduledForDeletion->contains($l)) {
                $this->playerDecksRelatedByFourthPropriumIdScheduledForDeletion->remove($this->playerDecksRelatedByFourthPropriumIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerDeck $playerDeckRelatedByFourthPropriumId The ChildPlayerDeck object to add.
     */
    protected function doAddPlayerDeckRelatedByFourthPropriumId(ChildPlayerDeck $playerDeckRelatedByFourthPropriumId)
    {
        $this->collPlayerDecksRelatedByFourthPropriumId[]= $playerDeckRelatedByFourthPropriumId;
        $playerDeckRelatedByFourthPropriumId->setPropriumRelatedByFourthPropriumId($this);
    }

    /**
     * @param  ChildPlayerDeck $playerDeckRelatedByFourthPropriumId The ChildPlayerDeck object to remove.
     * @return $this|ChildProprium The current object (for fluent API support)
     */
    public function removePlayerDeckRelatedByFourthPropriumId(ChildPlayerDeck $playerDeckRelatedByFourthPropriumId)
    {
        if ($this->getPlayerDecksRelatedByFourthPropriumId()->contains($playerDeckRelatedByFourthPropriumId)) {
            $pos = $this->collPlayerDecksRelatedByFourthPropriumId->search($playerDeckRelatedByFourthPropriumId);
            $this->collPlayerDecksRelatedByFourthPropriumId->remove($pos);
            if (null === $this->playerDecksRelatedByFourthPropriumIdScheduledForDeletion) {
                $this->playerDecksRelatedByFourthPropriumIdScheduledForDeletion = clone $this->collPlayerDecksRelatedByFourthPropriumId;
                $this->playerDecksRelatedByFourthPropriumIdScheduledForDeletion->clear();
            }
            $this->playerDecksRelatedByFourthPropriumIdScheduledForDeletion[]= clone $playerDeckRelatedByFourthPropriumId;
            $playerDeckRelatedByFourthPropriumId->setPropriumRelatedByFourthPropriumId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Proprium is new, it will return
     * an empty collection; or if this Proprium has previously
     * been saved, it will retrieve related PlayerDecksRelatedByFourthPropriumId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Proprium.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     */
    public function getPlayerDecksRelatedByFourthPropriumIdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerDeckQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerDecksRelatedByFourthPropriumId($query, $con);
    }

    /**
     * Clears out the collPlayerDecksRelatedByFifthPropriumId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerDecksRelatedByFifthPropriumId()
     */
    public function clearPlayerDecksRelatedByFifthPropriumId()
    {
        $this->collPlayerDecksRelatedByFifthPropriumId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerDecksRelatedByFifthPropriumId collection loaded partially.
     */
    public function resetPartialPlayerDecksRelatedByFifthPropriumId($v = true)
    {
        $this->collPlayerDecksRelatedByFifthPropriumIdPartial = $v;
    }

    /**
     * Initializes the collPlayerDecksRelatedByFifthPropriumId collection.
     *
     * By default this just sets the collPlayerDecksRelatedByFifthPropriumId collection to an empty array (like clearcollPlayerDecksRelatedByFifthPropriumId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerDecksRelatedByFifthPropriumId($overrideExisting = true)
    {
        if (null !== $this->collPlayerDecksRelatedByFifthPropriumId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlayerDeckTableMap::getTableMap()->getCollectionClassName();

        $this->collPlayerDecksRelatedByFifthPropriumId = new $collectionClassName;
        $this->collPlayerDecksRelatedByFifthPropriumId->setModel('\app\model\PlayerDeck');
    }

    /**
     * Gets an array of ChildPlayerDeck objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildProprium is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     * @throws PropelException
     */
    public function getPlayerDecksRelatedByFifthPropriumId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByFifthPropriumIdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByFifthPropriumId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByFifthPropriumId) {
                // return empty collection
                $this->initPlayerDecksRelatedByFifthPropriumId();
            } else {
                $collPlayerDecksRelatedByFifthPropriumId = ChildPlayerDeckQuery::create(null, $criteria)
                    ->filterByPropriumRelatedByFifthPropriumId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerDecksRelatedByFifthPropriumIdPartial && count($collPlayerDecksRelatedByFifthPropriumId)) {
                        $this->initPlayerDecksRelatedByFifthPropriumId(false);

                        foreach ($collPlayerDecksRelatedByFifthPropriumId as $obj) {
                            if (false == $this->collPlayerDecksRelatedByFifthPropriumId->contains($obj)) {
                                $this->collPlayerDecksRelatedByFifthPropriumId->append($obj);
                            }
                        }

                        $this->collPlayerDecksRelatedByFifthPropriumIdPartial = true;
                    }

                    return $collPlayerDecksRelatedByFifthPropriumId;
                }

                if ($partial && $this->collPlayerDecksRelatedByFifthPropriumId) {
                    foreach ($this->collPlayerDecksRelatedByFifthPropriumId as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerDecksRelatedByFifthPropriumId[] = $obj;
                        }
                    }
                }

                $this->collPlayerDecksRelatedByFifthPropriumId = $collPlayerDecksRelatedByFifthPropriumId;
                $this->collPlayerDecksRelatedByFifthPropriumIdPartial = false;
            }
        }

        return $this->collPlayerDecksRelatedByFifthPropriumId;
    }

    /**
     * Sets a collection of ChildPlayerDeck objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerDecksRelatedByFifthPropriumId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildProprium The current object (for fluent API support)
     */
    public function setPlayerDecksRelatedByFifthPropriumId(Collection $playerDecksRelatedByFifthPropriumId, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerDeck[] $playerDecksRelatedByFifthPropriumIdToDelete */
        $playerDecksRelatedByFifthPropriumIdToDelete = $this->getPlayerDecksRelatedByFifthPropriumId(new Criteria(), $con)->diff($playerDecksRelatedByFifthPropriumId);


        $this->playerDecksRelatedByFifthPropriumIdScheduledForDeletion = $playerDecksRelatedByFifthPropriumIdToDelete;

        foreach ($playerDecksRelatedByFifthPropriumIdToDelete as $playerDeckRelatedByFifthPropriumIdRemoved) {
            $playerDeckRelatedByFifthPropriumIdRemoved->setPropriumRelatedByFifthPropriumId(null);
        }

        $this->collPlayerDecksRelatedByFifthPropriumId = null;
        foreach ($playerDecksRelatedByFifthPropriumId as $playerDeckRelatedByFifthPropriumId) {
            $this->addPlayerDeckRelatedByFifthPropriumId($playerDeckRelatedByFifthPropriumId);
        }

        $this->collPlayerDecksRelatedByFifthPropriumId = $playerDecksRelatedByFifthPropriumId;
        $this->collPlayerDecksRelatedByFifthPropriumIdPartial = false;

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
    public function countPlayerDecksRelatedByFifthPropriumId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerDecksRelatedByFifthPropriumIdPartial && !$this->isNew();
        if (null === $this->collPlayerDecksRelatedByFifthPropriumId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerDecksRelatedByFifthPropriumId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerDecksRelatedByFifthPropriumId());
            }

            $query = ChildPlayerDeckQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPropriumRelatedByFifthPropriumId($this)
                ->count($con);
        }

        return count($this->collPlayerDecksRelatedByFifthPropriumId);
    }

    /**
     * Method called to associate a ChildPlayerDeck object to this object
     * through the ChildPlayerDeck foreign key attribute.
     *
     * @param  ChildPlayerDeck $l ChildPlayerDeck
     * @return $this|\app\model\Proprium The current object (for fluent API support)
     */
    public function addPlayerDeckRelatedByFifthPropriumId(ChildPlayerDeck $l)
    {
        if ($this->collPlayerDecksRelatedByFifthPropriumId === null) {
            $this->initPlayerDecksRelatedByFifthPropriumId();
            $this->collPlayerDecksRelatedByFifthPropriumIdPartial = true;
        }

        if (!$this->collPlayerDecksRelatedByFifthPropriumId->contains($l)) {
            $this->doAddPlayerDeckRelatedByFifthPropriumId($l);

            if ($this->playerDecksRelatedByFifthPropriumIdScheduledForDeletion and $this->playerDecksRelatedByFifthPropriumIdScheduledForDeletion->contains($l)) {
                $this->playerDecksRelatedByFifthPropriumIdScheduledForDeletion->remove($this->playerDecksRelatedByFifthPropriumIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlayerDeck $playerDeckRelatedByFifthPropriumId The ChildPlayerDeck object to add.
     */
    protected function doAddPlayerDeckRelatedByFifthPropriumId(ChildPlayerDeck $playerDeckRelatedByFifthPropriumId)
    {
        $this->collPlayerDecksRelatedByFifthPropriumId[]= $playerDeckRelatedByFifthPropriumId;
        $playerDeckRelatedByFifthPropriumId->setPropriumRelatedByFifthPropriumId($this);
    }

    /**
     * @param  ChildPlayerDeck $playerDeckRelatedByFifthPropriumId The ChildPlayerDeck object to remove.
     * @return $this|ChildProprium The current object (for fluent API support)
     */
    public function removePlayerDeckRelatedByFifthPropriumId(ChildPlayerDeck $playerDeckRelatedByFifthPropriumId)
    {
        if ($this->getPlayerDecksRelatedByFifthPropriumId()->contains($playerDeckRelatedByFifthPropriumId)) {
            $pos = $this->collPlayerDecksRelatedByFifthPropriumId->search($playerDeckRelatedByFifthPropriumId);
            $this->collPlayerDecksRelatedByFifthPropriumId->remove($pos);
            if (null === $this->playerDecksRelatedByFifthPropriumIdScheduledForDeletion) {
                $this->playerDecksRelatedByFifthPropriumIdScheduledForDeletion = clone $this->collPlayerDecksRelatedByFifthPropriumId;
                $this->playerDecksRelatedByFifthPropriumIdScheduledForDeletion->clear();
            }
            $this->playerDecksRelatedByFifthPropriumIdScheduledForDeletion[]= clone $playerDeckRelatedByFifthPropriumId;
            $playerDeckRelatedByFifthPropriumId->setPropriumRelatedByFifthPropriumId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Proprium is new, it will return
     * an empty collection; or if this Proprium has previously
     * been saved, it will retrieve related PlayerDecksRelatedByFifthPropriumId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Proprium.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerDeck[] List of ChildPlayerDeck objects
     */
    public function getPlayerDecksRelatedByFifthPropriumIdJoinPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerDeckQuery::create(null, $criteria);
        $query->joinWith('Player', $joinBehavior);

        return $this->getPlayerDecksRelatedByFifthPropriumId($query, $con);
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
            if ($this->collItems) {
                foreach ($this->collItems as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerDecksRelatedByFirstPropriumId) {
                foreach ($this->collPlayerDecksRelatedByFirstPropriumId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerDecksRelatedBySecondPropriumId) {
                foreach ($this->collPlayerDecksRelatedBySecondPropriumId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerDecksRelatedByThirdPropriumId) {
                foreach ($this->collPlayerDecksRelatedByThirdPropriumId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerDecksRelatedByFourthPropriumId) {
                foreach ($this->collPlayerDecksRelatedByFourthPropriumId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerDecksRelatedByFifthPropriumId) {
                foreach ($this->collPlayerDecksRelatedByFifthPropriumId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collItems = null;
        $this->collPlayerDecksRelatedByFirstPropriumId = null;
        $this->collPlayerDecksRelatedBySecondPropriumId = null;
        $this->collPlayerDecksRelatedByThirdPropriumId = null;
        $this->collPlayerDecksRelatedByFourthPropriumId = null;
        $this->collPlayerDecksRelatedByFifthPropriumId = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PropriumTableMap::DEFAULT_STRING_FORMAT);
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
