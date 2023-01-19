<?php
namespace proven\store\model\persist;

require_once 'model/persist/StoreDb.php';
require_once 'model/Category.php';

use proven\store\model\persist\StoreDb as DbConnect;
use proven\store\model\Category as Category;

/**
 * Category database persistence class.
 * @author ProvenSoft
 */
class CategoryDao{

    /**
     * Encapsulates connection data to database.
     */
    private DbConnect $dbConnect;
    /**
     * table name for entity.
     */
    private static string $TABLE_NAME = 'categories';
    /**
     * queries to database.
     */
    private array $queries;

    /**
     * constructor.
     */
    public function __construct() {
        $this->dbConnect = new DbConnect();
        $this->queries = array();
        $this->initQueries();
    }

    /**
     * defines queries to database.
     */
    private function initQueries() {
        //query definition.
        $this->queries['SELECT_ALL'] = \sprintf(
                "select * from %s",
                self::$TABLE_NAME
        );
        $this->queries['UPDATE'] = \sprintf(
                "update %s set code= :code, description= :description where id = :id",
                self::$TABLE_NAME
        );
        $this->queries['DELETE'] = \sprintf(
                "delete from %s where id = :id",
                self::$TABLE_NAME
        );
    }

    /**
     * fetches a row from PDOStatement and converts it into an entity object.
     * @param $statement the statement with query data.
     * @return entity object with retrieved data or false in case of error.
     */
    /* private function fetchToEntity($statement): mixed {
        $row = $statement->fetch();
        if ($row) {
            $id          = intval($row['id']);
            $code        = $row['code'];
            $description = $row['description'];
            return new Category($id, $code, $description);
        } else {
            return false;
        }
    } */

    /**
     * selects all entitites in database.
     * return array of entity objects.
     */
    public function selectAll(): array {
        $data = array();
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection();
            //query preparation.
            $stmt = $connection->prepare($this->queries['SELECT_ALL']);
            //query execution.
            $success = $stmt->execute(); //bool
            //Statement data recovery.
            if ($success) {
                if ($stmt->rowCount()>0) {
                   //fetch in class mode and get array with all data.
                    $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Category::class);
                    $data = $stmt->fetchAll();
                    //or in one single sentence:
                    // $data = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, User::class);
                } else {
                    $data = array();
                }
            } else {
                $data = array();
            }
        } catch (\PDOException $e) {
//            print "Error Code <br>".$e->getCode();
//            print "Error Message <br>".$e->getMessage();
//            print "Stack Trace <br>".nl2br($e->getTraceAsString());
            $data = array();
        }
        return $data;
    }

    /**
     * updates entity in database.
     * @param entity the entity object to update.
     * @return number of rows affected.
     */
    public function update(Category $entity): int {
        $numAffected = 0;
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection();
            //query preparation.
            $stmt = $connection->prepare($this->queries['UPDATE']);
            $stmt->bindValue(':id', $entity->getId(), \PDO::PARAM_INT);
            $stmt->bindValue(':code', $entity->getCode(), \PDO::PARAM_STR);
            $stmt->bindValue(':description', $entity->getDescription(), \PDO::PARAM_STR);
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
    public function delete(Category $entity): int {
        $numAffected = 0;
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection();
            //query preparation.
            $stmt = $connection->prepare($this->queries['DELETE']);
            $stmt->bindValue(':id', $entity->getId(), \PDO::PARAM_INT);
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
