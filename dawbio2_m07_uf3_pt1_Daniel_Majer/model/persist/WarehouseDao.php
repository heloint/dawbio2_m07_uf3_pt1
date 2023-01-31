<?php
namespace proven\store\model\persist;

require_once "model/persist/StoreDb.php";
require_once "model/Warehouse.php";

use proven\store\model\persist\StoreDb as DbConnect;
use proven\store\model\Warehouse as Warehouse;

/**
 * Warehouse database persistence class.
 * @author Dániel Májer
 */
class WarehouseDao
{
    /**
     * Encapsulates connection data to database.
     */
    private DbConnect $dbConnect;
    /**
     * table name for entity.
     */
    private static string $TABLE_NAME = "warehouses";
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
     * Defines queries to database.
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
            "insert into %s (code, address) values (:code, :address)",
            self::$TABLE_NAME
        );
        $this->queries["UPDATE"] = \sprintf(
            "update %s set code= :code,address= :address where id = :id",
            self::$TABLE_NAME
        );
        $this->queries["DELETE"] = \sprintf(
            "delete from %s where id = :id",
            self::$TABLE_NAME
        );
    }

    /**
     * Fetches a row from PDOStatement and converts it into an entity object.
     * @param $statement the statement with query data.
     * @return entity object with retrieved data or false in case of error.
     */
    /* private function fetchToEntity($statement): mixed {
        $row = $statement->fetch();
        if ($row) {
            $id          = intval($row['id']);
            $code        = $row['code'];
            $address = $row['address'];
            return new Warehouse($id, $code, $description);
        } else {
            return false;
        }
    } */

    /**
     * selects an entity given its id.
     * @param entity the entity to search.
     * @return entity object being searched or null if not found or in case of error.
     */
    public function select(Warehouse $entity): ?Warehouse
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
                        Warehouse::class
                    );
                    $data = $stmt->fetch();
                } else {
                    $data = null;
                }
            } else {
                $data = null;
            }
        } catch (\PDOException $e) {
            // print "Error Code <br>".$e->getCode();
            // print "Error Message <br>".$e->getMessage();
            // print "Strack Trace <br>".nl2br($e->getTraceAsString());
            $data = null;
        }
        return $data;
    }

    /**
     * Selects an entity given its code.
     * @param entity the entity to search.
     * @return entity object being searched or null if not found or in case of error.
     */
    public function selectByCode(Warehouse $entity): ?Warehouse
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
                        Warehouse::class
                    );
                    $data = $stmt->fetch();
                } else {
                    $data = null;
                }
            } else {
                $data = null;
            }
        } catch (\PDOException $e) {
            // print "Error Code <br>".$e->getCode();
            // print "Error Message <br>".$e->getMessage();
            // print "Strack Trace <br>".nl2br($e->getTraceAsString());
            $data = null;
        }
        return $data;
    }

    /**
     * selects all entitites in database.
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
                        Warehouse::class
                    );
                    $data = $stmt->fetchAll();
                    //or in one single sentence:
                    // $data = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, User::class);
                } else {
                    $data = [];
                }
            } else {
                $data = [];
            }
        } catch (\PDOException $e) {
            //            print "Error Code <br>".$e->getCode();
            //            print "Error Message <br>".$e->getMessage();
            //            print "Stack Trace <br>".nl2br($e->getTraceAsString());
            $data = [];
        }
        return $data;
    }

    /**
     * Inserts a new entity in database.
     * @param entity the entity object to insert.
     * @return number of rows affected.
     */
    public function insert(Warehouse $entity): int
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
                $entity->getAddress(),
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
     * updates entity in database.
     * @param entity the entity object to update.
     * @return number of rows affected.
     */
    public function update(Warehouse $entity): int
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
                $entity->getAddress(),
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
     * deletes entity from database.
     * @param entity the entity object to delete.
     * @return number of rows affected.
     */
    public function delete(Warehouse $entity): int
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
