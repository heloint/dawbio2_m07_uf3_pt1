<?php
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
    public function findAllUsers(): array
    {
        $dbHelper = new UserDao();
        return $dbHelper->selectAll();
    }

    public function findUsersByRole(string $role): array
    {
        $dbHelper = new UserDao();
        return $dbHelper->selectWhere("role", $role);
    }

    public function addUser(User $user): int
    {
        $dbHelper = new UserDao();
        return $dbHelper->insert($user);
    }

    public function modifyUser(User $user): int
    {
        $dbHelper = new UserDao();
        return $dbHelper->update($user);
    }

    public function removeUser(User $user): int
    {
        $dbHelper = new UserDao();
        return $dbHelper->delete($user);
    }

    public function findUserById(int $id): ?User
    {
        $dbHelper = new UserDao();
        $u = new User($id);
        return $dbHelper->select($u);
    }

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
    public function findCategoryById(int $id): ?Category
    {
        $dbHelper = new CategoryDao();
        $tmpObj = new Category($id);
        return $dbHelper->select($tmpObj);
    }

    public function findCategoryByCode(string $code): ?Category
    {
        $dbHelper = new CategoryDao();
        $tmpObj = new Category(0, $code);
        return $dbHelper->selectByCode($tmpObj);
    }

    public function findAllCategories(): array
    {
        $dbHelper = new CategoryDao();
        return $dbHelper->selectAll();
    }

    public function modifyCategory(Category $category): int
    {
        $dbHelper = new CategoryDao();
        return $dbHelper->update($category);
    }

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
    public function findProductById(int $id): ?Product
    {
        $dbHelper = new ProductDao();
        $u = new Product($id);
        return $dbHelper->select($u);
    }

    public function findProductByCode(string $code)
    {
        $dbHelper = new ProductDao();
        $u = new Product(0, $code);
        return $dbHelper->selectByCode($u);
    }

    public function findAllProducts(): array
    {
        $dbHelper = new ProductDao();
        return $dbHelper->selectAll();
    }

    public function findProductsByCategory(Category $category): array
    {
        $dbHelper = new ProductDao();
        return $dbHelper->selectAllByCategory($category);
    }

    public function addProduct(Product $product): int
    {
        $dbHelper = new ProductDao();
        return $dbHelper->insert($product);
    }

    public function modifyProduct(Product $category): int
    {
        $dbHelper = new ProductDao();
        return $dbHelper->update($category);
    }

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
    public function findStocksByProduct(Product $product): ?array
    {
        $WarehouseProductDao = new WarehouseProductDao();
        return $WarehouseProductDao->selectByProduct($product);
    }

    public function findStocksByWarehouse(Warehouse $warehouse): ?array
    {
        $WarehouseProductDao = new WarehouseProductDao();
        return $WarehouseProductDao->selectByWarehouse($warehouse);
    }
    // WAREHOUSE METHODS
    // =======================================
    public function findAllWarehouses(): array
    {
        $dbHelper = new WarehouseDao();
        return $dbHelper->selectAll();
    }

    public function findWarehouseById(int $id): ?Warehouse {
        $dbHelper = new WarehouseDao();
        $u = new Warehouse($id);
        return $dbHelper->select($u);
    }

    public function modifyWarehouse($warehouse): int {
        $dbHelper = new WarehouseDao();
        return $dbHelper->update($warehouse);
    }
}
