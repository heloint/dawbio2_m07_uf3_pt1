<?php
/* Class that represents a data access object in the MVC architecture.
 * @author Dániel Májer
 * */

namespace proven\store\model\persist;

require_once "model/persist/StoreDb.php";
require_once "model/Product.php";
require_once "model/Warehouse.php";
require_once "model/WarehouseProduct.php";

use proven\store\model\persist\StoreDb as DbConnect;
use proven\store\model\Product as Product;
use proven\store\model\Warehouse as Warehouse;
use proven\store\model\WarehouseProduct as WarehouseProduct;

/**
 * Product database persistence class.
 * @author ProvenSoft
 */
class WarehouseProductDao
{
    /**
     * Encapsulates connection data to database.
     */
    private DbConnect $dbConnect;
    /**
     * table name for entity.
     */
    private static string $TABLE_NAME = "warehousesproducts";
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
        $this->queries["SELECT_WHERE_PRODUCT_ID"] = \sprintf(
            "select * from %s where product_id = :product_id",
            self::$TABLE_NAME
        );
        $this->queries["SELECT_WHERE_WAREHOUSE_ID"] = \sprintf(
            "select * from %s where warehouse_id = :warehouse_id",
            self::$TABLE_NAME
        );
        $this->queries["INSERT"] = \sprintf(
            "insert into %s (warehouse_id, product_id, stock) values (:warehouse_id, :product_id, :stock)",
            self::$TABLE_NAME
        );
        $this->queries["UPDATE"] = \sprintf(
            "update %s set code= :code, description = :description, price = :price, category_id = :category_id where id = :id",
            self::$TABLE_NAME
        );
        $this->queries["DELETE"] = \sprintf(
            "delete from %s where warehouse_id = :warehouse_id and product_id = :product_id",
            self::$TABLE_NAME
        );
    }

    /**
     * Selects a stock entity by a product entity.
     * @param entity the entity to search.
     * @return entity object being searched or null if not found or in case of error.
     */
    public function selectByProduct(Product $entity): ?array
    {
        $data = null;
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection();
            //query preparation.
            $stmt = $connection->prepare(
                $this->queries["SELECT_WHERE_PRODUCT_ID"]
            );
            $stmt->bindValue(":product_id", $entity->getId(), \PDO::PARAM_INT);
            //query execution.
            $success = $stmt->execute(); //bool
            //Statement data recovery.
            if ($success) {
                if ($stmt->rowCount() > 0) {
                    $stmt->setFetchMode(
                        \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE,
                        WarehouseProduct::class
                    );
                    $data = $stmt->fetchAll();
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
     * Selects a stock entity by a warehouse entity.
     * @param entity the entity to search.
     * @return entity object being searched or null if not found or in case of error.
     */
    public function selectByWarehouse(Warehouse $entity): ?array
    {
        $data = null;
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection();
            //query preparation.
            $stmt = $connection->prepare(
                $this->queries["SELECT_WHERE_WAREHOUSE_ID"]
            );
            $stmt->bindValue(
                ":warehouse_id",
                $entity->getId(),
                \PDO::PARAM_INT
            );
            //query execution.
            $success = $stmt->execute(); //bool
            //Statement data recovery.
            if ($success) {
                if ($stmt->rowCount() > 0) {
                    $stmt->setFetchMode(
                        \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE,
                        WarehouseProduct::class
                    );
                    $data = $stmt->fetchAll();
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
                        WarehouseProduct::class
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
    public function insert(WarehouseProduct $stock): int
    {
        $numAffected = 0;
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection();
            //query preparation.
            $stmt = $connection->prepare($this->queries["INSERT"]);
            $stmt->bindValue(
                ":warehouse_id",
                $stock->getWarehouseId(),
                \PDO::PARAM_INT
            );
            $stmt->bindValue(
                ":product_id",
                $stock->getProductId(),
                \PDO::PARAM_INT
            );
            $stmt->bindValue(
                ":stock",
                $stock->getStock(),
                \PDO::PARAM_INT | \PDO::PARAM_NULL
            );

            //query execution.
            $success = $stmt->execute(); //bool
            $numAffected = $success ? $stmt->rowCount() : 0;
        } catch (\PDOException $e) {
            $numAffected = 0;
        }
        return $numAffected;
    }

    /**
     * Updates entity in database.
     * @param entity the entity object to update.
     * @return number of rows affected.
     */
    public function update(WarehouseProduct $entity): int
    {
        $numAffected = 0;
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection();
            //query preparation.
            $stmt = $connection->prepare($this->queries["UPDATE"]);
            $stmt->bindValue(
                ":warehouse_id",
                $entity->getWarehouseId(),
                \PDO::PARAM_INT
            );
            $stmt->bindValue(
                ":product_id",
                $entity->getProductId(),
                \PDO::PARAM_INT
            );
            $stmt->bindValue(
                ":stock",
                $entity->getStock(),
                \PDO::PARAM_STR | \PDO::PARAM_NULL
            );
            //query execution.
            $success = $stmt->execute(); //bool
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
    public function delete(WarehouseProduct $entity): int
    {
        $numAffected = 0;
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection();
            //query preparation.
            $stmt = $connection->prepare($this->queries["DELETE"]);
            $stmt->bindValue(
                ":warehouse_id",
                $entity->getWarehouseId(),
                \PDO::PARAM_INT
            );
            $stmt->bindValue(
                ":product_id",
                $entity->getProductId(),
                \PDO::PARAM_INT
            );
            $success = $stmt->execute(); //bool
            $numAffected = $success ? $stmt->rowCount() : 0;
        } catch (\PDOException $e) {
            $numAffected = 0;
        }
        return $numAffected;
    }

    /**
     * Selects entitites in database where field value.
     * return array of entity objects.
     */
    public function selectWhere(string $fieldname, string $fieldvalue): array
    {
        $data = [];
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection();
            //query preparation.
            $query = sprintf(
                "select * from %s where %s = '%s'",
                self::$TABLE_NAME,
                $fieldname,
                $fieldvalue
            );
            $stmt = $connection->prepare($query);
            //query execution.
            $success = $stmt->execute(); //bool
            //Statement data recovery.
            if ($success) {
                if ($stmt->rowCount() > 0) {
                    $stmt->setFetchMode(
                        \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE,
                        WarehouseProduct::class
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
}
