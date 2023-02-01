<?php
/* StoreModel class representing a model of the MVC architecture
 * corresponding to all the database related operations.
 * @author Dániel Májer
 * */

namespace proven\store\model;

// USER IMPORTS
require_once "model/persist/UserDao.php";
require_once "model/User.php";

// CATEGORY IMPORTS
require_once "model/persist/CategoryDao.php";
require_once "model/Category.php";

// PRODUCT IMPORTS
require_once "model/persist/ProductDao.php";
require_once "model/Product.php";

// WAREHOUSE-PRODUCT IMPORTS
require_once "model/persist/WarehouseProductDao.php";
require_once "model/WarehouseProduct.php";

// WAREHOUSE IMPORTS
require_once "model/persist/WarehouseDao.php";
require_once "model/Warehouse.php";

use proven\store\model\persist\UserDao;
use proven\store\model\persist\CategoryDao;
use proven\store\model\persist\ProductDao;
use proven\store\model\persist\WarehouseProductDao;
use proven\store\model\persist\WarehouseDao;
//use proven\store\model\User;

/**
 * Service class to provide data.
 * @author ProvenSoft
 */
class StoreModel
{
    public function __construct()
    {
    }

    // USER METHODS
    // =========================================

    /* Finds all users in the database.
     * @return array<User|null>
     * */
    public function findAllUsers(): array
    {
        $dbHelper = new UserDao();
        return $dbHelper->selectAll();
    }

    /* Finds all users by the given role in the database.
     * @param role string
     * @return array<User|null>
     * */
    public function findUsersByRole(string $role): array
    {
        $dbHelper = new UserDao();
        return $dbHelper->selectWhere("role", $role);
    }

    /* Adds a new user to the database.
     * @param user User
     * @return int The number of rows affected by the SQL query.
     * */
    public function addUser(User $user): int
    {
        $dbHelper = new UserDao();
        return $dbHelper->insert($user);
    }

    /* Modifies a user in the database.
     * @param user User
     * @return int The number of rows affected by the SQL query.
     * */
    public function modifyUser(User $user): int
    {
        $dbHelper = new UserDao();
        return $dbHelper->update($user);
    }

    /* Removes a user in the database.
     * @param user User
     * @return int The number of rows affected by the SQL query.
     * */
    public function removeUser(User $user): int
    {
        $dbHelper = new UserDao();
        return $dbHelper->delete($user);
    }

    /* Finds a user by the given id in the database.
     * @param id int
     * @return User|null
     * */
    public function findUserById(int $id): ?User
    {
        $dbHelper = new UserDao();
        $u = new User($id);
        return $dbHelper->select($u);
    }

    /* Finds a user by the given username and password in the database.
     * @param username string
     * @param password string
     * @return User|null
     * */
    public function findUserByUsernameAndPassword(
        string $username,
        string $password
    ): ?User {
        $dbHelper = new UserDao();
        $u = new User(0, $username, $password);
        return $dbHelper->selectByUsernameAndPassword($u);
    }

    // CATEGORY METHODS
    // =========================================

    /* Finds a category by the given id in the database.
     * @param id int
     * @return Category|null
     * */
    public function findCategoryById(int $id): ?Category
    {
        $dbHelper = new CategoryDao();
        $tmpObj = new Category($id);
        return $dbHelper->select($tmpObj);
    }

    /* Finds a category by the given code in the database.
     * @param code string
     * @return Category|null
     * */
    public function findCategoryByCode(string $code): ?Category
    {
        $dbHelper = new CategoryDao();
        $tmpObj = new Category(0, $code);
        return $dbHelper->selectByCode($tmpObj);
    }

    /* Finds all categories in the database.
     * @return array<Category|null>
     * */
    public function findAllCategories(): array
    {
        $dbHelper = new CategoryDao();
        return $dbHelper->selectAll();
    }

    /* Modifies a category in the database.
     * @param category Category
     * @return int The number of rows affected by the SQL query.
     * */
    public function modifyCategory(Category $category): int
    {
        $dbHelper = new CategoryDao();
        return $dbHelper->update($category);
    }

    /* Removes a category in the database.
     * @param category Category
     * @return int The number of rows affected by the SQL query.
     * */
    public function removeCategory(Category $category): int
    {
        // Init category data access object.
        $categoryDao = new CategoryDao();
        $productDao = new ProductDao();

        $WarehouseProductDao = new WarehouseProductDao();
        // Find the products with the found category.
        $productsWithCategory = $this->findProductsByCategory($category);

        if (!empty($productsWithCategory)) {
            $stockEntities = [];
            foreach ($productsWithCategory as $product) {
                $result = $WarehouseProductDao->selectByProduct($product);

                if (!is_null($result)) {
                    foreach ($result as $stock) {
                        array_push($stockEntities, $stock);
                    }
                }
            }

            // ==============================================
            // First try to delete from the warehousesproducts table.
            // ==============================================
            if (!empty($stockEntities)) {
                $tmpRowCounter = 0;
                foreach ($stockEntities as $stock) {
                    $tmpRowCounter += (int) $WarehouseProductDao->delete(
                        $stock
                    );
                }

                // At this point if $stockEntities wasn't empty,
                // but after trying to delete them from the warehousesproducts table,
                // and 0 rows have been affected, then something has failed. => We will return 0 as value.
                if ($tmpRowCounter === 0) {
                    return 0;
                }
            }

            // ==============================================
            // Second delete from the products table.
            // ==============================================
            $tmpRowCounter = 0;
            foreach ($productsWithCategory as $product) {
                $tmpRowCounter += (int) $productDao->delete($product);
            }
            // At this point if $productsWithCategory wasn't empty,
            // but after trying to delete them from the products table,
            // and 0 rows have been affected, then something has failed. => We will return 0 as value.
            if ($tmpRowCounter === 0) {
                return 0;
            }
            // ==============================================
        }

        // And finally try to delete the $category.
        return $categoryDao->delete($category);
    }

    // PRODUCT METHODS
    // =========================================

    /* Finds a product by the given id in the database.
     * @param id int
     * @return Product | null
     * */
    public function findProductById(int $id): ?Product
    {
        $dbHelper = new ProductDao();
        $u = new Product($id);
        return $dbHelper->select($u);
    }

    /* Finds a product by the given code in the database.
     * @param code string
     * @return Product | null
     * */
    public function findProductByCode(string $code)
    {
        $dbHelper = new ProductDao();
        $u = new Product(0, $code);
        return $dbHelper->selectByCode($u);
    }

    /* Finds all products in the database.
     * @return array<Product|null>
     * */
    public function findAllProducts(): array
    {
        $dbHelper = new ProductDao();
        return $dbHelper->selectAll();
    }

    /* Finds all product by the given category in the database.
     * @param category Category
     * @return array<Product | null>
     * */
    public function findProductsByCategory(Category $category): array
    {
        $dbHelper = new ProductDao();
        return $dbHelper->selectAllByCategory($category);
    }

    /* Adds a new product to the database.
     * @param product Product
     * @return int The number of rows affected by the SQL query.
     * */
    public function addProduct(Product $product): int
    {
        $dbHelper = new ProductDao();
        return $dbHelper->insert($product);
    }

    /* Modifies a product to the database.
     * @param product Product
     * @return int The number of rows affected by the SQL query.
     * */
    public function modifyProduct(Product $product ): int
    {
        $dbHelper = new ProductDao();
        return $dbHelper->update($product);
    }

    /* Removes a product to the database.
     * @param product Product
     * @return int The number of rows affected by the SQL query.
     * */
    public function removeProduct(Product $product): int
    {
        // Init category data access object.
        $productDao = new ProductDao();
        $WarehouseProductDao = new WarehouseProductDao();

        $result = $WarehouseProductDao->selectByProduct($product);

        // Collect the corresponding stock entities for this $product.
        $stockEntities = [];
        if (!is_null($result)) {
            foreach ($result as $stock) {
                array_push($stockEntities, $stock);
            }
        }

        // ==============================================
        // First try to delete from the warehousesproducts table.
        // ==============================================
        if (!empty($stockEntities)) {
            $tmpRowCounter = 0;
            foreach ($stockEntities as $stock) {
                $tmpRowCounter += (int) $WarehouseProductDao->delete($stock);
            }

            // At this point if $stockEntities wasn't empty,
            // but after trying to delete them from the warehousesproducts table,
            // and 0 rows have been affected, then something has failed. => We will return 0 as value.
            if ($tmpRowCounter === 0) {
                return 0;
            }
        }
        // ==============================================

        return $productDao->delete($product);
    }

    /* Finds all stock registers corresponding with the given product.
     * @param product Product
     * @return array<Product> | null
     * */
    public function findStocksByProduct(Product $product): ?array
    {
        $WarehouseProductDao = new WarehouseProductDao();
        return $WarehouseProductDao->selectByProduct($product);
    }

    /* Finds all stock registers corresponding with the given warehouse.
     * @param warehouse Warehouse
     * @return array<Warehouse> | null
     * */
    public function findStocksByWarehouse(Warehouse $warehouse): ?array
    {
        $WarehouseProductDao = new WarehouseProductDao();
        return $WarehouseProductDao->selectByWarehouse($warehouse);
    }

    // WAREHOUSE METHODS
    // =======================================

    /* Finds all warehouses in the database.
     * @return array<Warehouse|null>
     * */
    public function findAllWarehouses(): array
    {
        $dbHelper = new WarehouseDao();
        return $dbHelper->selectAll();
    }

    /* Finds a warehouse by the given id in the database.
     * @param id int
     * @return Warehouse | null
     * */
    public function findWarehouseById(int $id): ?Warehouse {
        $dbHelper = new WarehouseDao();
        $u = new Warehouse($id);
        return $dbHelper->select($u);
    }

    /* Modifies a warehouse in the database.
     * @param warehouse Warehouse
     * @return int The number of rows affected by the SQL query.
     * */
    public function modifyWarehouse(Warehouse $warehouse): int {
        $dbHelper = new WarehouseDao();
        return $dbHelper->update($warehouse);
    }
}
