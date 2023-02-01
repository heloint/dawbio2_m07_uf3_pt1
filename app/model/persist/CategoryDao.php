<?php
/* Class that represents a data access object in the MVC architecture.
 * @author Dániel Májer
 * */

namespace proven\store\model\persist;

require_once "model/persist/StoreDb.php";
require_once "model/Category.php";

use proven\store\model\persist\StoreDb as DbConnect;
use proven\store\model\Category as Category;

/**
 * Category database persistence class.
 * @author ProvenSoft
 */
class CategoryDao
{
    /**
     * Encapsulates connection data to database.
     */
    private DbConnect $dbConnect;
    /**
     * table name for entity.
     */
    private static string $TABLE_NAME = "categories";
    /**
     * queries to database.
     */
    private array $queries;

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->dbConnect = new DbConnect();
        $this->queries = [];
        $this->initQueries();
    }

    /**
     * defines queries to database.
     */
    private function initQueries()
    {
        //query definition.
        $this->queries["SELECT_ALL"] = \sprintf(
            "select * from %s",
            self::$TABLE_NAME
        );
        $this->queries["SELECT_WHERE_ID"] = \sprintf(
            "select * from %s where id = :id",
            self::$TABLE_NAME
        );
        $this->queries["SELECT_WHERE_CODE"] = \sprintf(
            "select * from %s where code = :code",
            self::$TABLE_NAME
        );
        $this->queries["INSERT"] = \sprintf(
            "insert into %s (code, description) values (:code, :description)",
            self::$TABLE_NAME
        );
        $this->queries["UPDATE"] = \sprintf(
            "update %s set code= :code, description= :description where id = :id",
            self::$TABLE_NAME
        );
        $this->queries["DELETE"] = \sprintf(
            "delete from %s where id = :id",
            self::$TABLE_NAME
        );
    }

    /**
     * Selects an entity given its id.
     * @param entity the entity to search.
     * @return entity object being searched or null if not found or in case of error.
     */
    public function select(Category $entity): ?Category
    {
        $data = null;
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection();
            //query preparation.
            $stmt = $connection->prepare($this->queries["SELECT_WHERE_ID"]);
            $stmt->bindValue(":id", $entity->getId(), \PDO::PARAM_INT);
            //query execution.
            $success = $stmt->execute(); //bool
            //Statement data recovery.
            if ($success) {
                if ($stmt->rowCount() > 0) {
                    $stmt->setFetchMode(
                        \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE,
                        Category::class
                    );
                    $data = $stmt->fetch();
                } else {
                    $data = null;
                }
            } else {
                $data = null;
            }
        } catch (\PDOException $e) {
            $data = null;
        }
        return $data;
    }

    /**
     * Selects an entity given its code.
     * @param entity the entity to search.
     * @return entity object being searched or null if not found or in case of error.
     */
    public function selectByCode(Category $entity): ?Category
    {
        $data = null;
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection();
            //query preparation.
            $stmt = $connection->prepare($this->queries["SELECT_WHERE_CODE"]);
            $stmt->bindValue(":code", $entity->getCode(), \PDO::PARAM_INT);
            //query execution.
            $success = $stmt->execute(); //bool
            //Statement data recovery.
            if ($success) {
                if ($stmt->rowCount() > 0) {
                    $stmt->setFetchMode(
                        \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE,
                        Category::class
                    );
                    $data = $stmt->fetch();
                } else {
                    $data = null;
                }
            } else {
                $data = null;
            }
        } catch (\PDOException $e) {
            $data = null;
        }
        return $data;
    }

    /**
     * Selects all entitites in database.
     * return array of entity objects.
     */
    public function selectAll(): array
    {
        $data = [];
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection();
            //query preparation.
            $stmt = $connection->prepare($this->queries["SELECT_ALL"]);
            //query execution.
            $success = $stmt->execute(); //bool
            //Statement data recovery.
            if ($success) {
                if ($stmt->rowCount() > 0) {
                    //fetch in class mode and get array with all data.
                    $stmt->setFetchMode(
                        \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE,
                        Category::class
                    );
                    $data = $stmt->fetchAll();
                } else {
                    $data = [];
                }
            } else {
                $data = [];
            }
        } catch (\PDOException $e) {
            $data = [];
        }
        return $data;
    }

    /**
     * Inserts a new entity in database.
     * @param entity the entity object to insert.
     * @return number of rows affected.
     */
    public function insert(Category $entity): int
    {
        $numAffected = 0;
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection();
            //query preparation.
            $stmt = $connection->prepare($this->queries["INSERT"]);
            $stmt->bindValue(":code", $entity->getCode(), \PDO::PARAM_STR);
            $stmt->bindValue(
                ":description",
                $entity->getDescription(),
                \PDO::PARAM_STR
            );
            //query execution.
            $success = $stmt->execute(); //bool
            $numAffected = $success ? $stmt->rowCount() : 0;
        } catch (\PDOException $e) {
            // print "Error Code <br>".$e->getCode();
            // print "Error Message <br>".$e->getMessage();
            // print "Strack Trace <br>".nl2br($e->getTraceAsString());
            $numAffected = 0;
        }
        return $numAffected;
    }

    /**
     * Updates entity in database.
     * @param entity the entity object to update.
     * @return number of rows affected.
     */
    public function update(Category $entity): int
    {
        $numAffected = 0;
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection();
            //query preparation.
            $stmt = $connection->prepare($this->queries["UPDATE"]);
            $stmt->bindValue(":id", $entity->getId(), \PDO::PARAM_INT);
            $stmt->bindValue(":code", $entity->getCode(), \PDO::PARAM_STR);
            $stmt->bindValue(
                ":description",
                $entity->getDescription(),
                \PDO::PARAM_STR
            );
            $success = $stmt->execute();
            $numAffected = $success ? $stmt->rowCount() : 0;
        } catch (\PDOException $e) {
            $numAffected = 0;
        }
        return $numAffected;
    }

    /**
     * Deletes entity from database.
     * @param entity the entity object to delete.
     * @return number of rows affected.
     */
    public function delete(Category $entity): int
    {
        $numAffected = 0;
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection();
            //query preparation.
            $stmt = $connection->prepare($this->queries["DELETE"]);
            $stmt->bindValue(":id", $entity->getId(), \PDO::PARAM_INT);
            $success = $stmt->execute(); //bool

            $numAffected = $success ? $stmt->rowCount() : 0;
        } catch (\PDOException $e) {
            // print "Error Code <br>".$e->getCode();
            // print "Error Message <br>".$e->getMessage();
            // print "Strack Trace <br>".nl2br($e->getTraceAsString());
            $numAffected = 0;
        }
        return $numAffected;
    }
}
