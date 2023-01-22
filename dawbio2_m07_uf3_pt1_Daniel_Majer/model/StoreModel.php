<?php
namespace proven\store\model;

// USER IMPORTS
require_once 'model/persist/UserDao.php';
require_once 'model/User.php';

// CATEGORY IMPORTS
require_once 'model/persist/CategoryDao.php';
require_once 'model/Category.php';

// PRODUCT IMPORTS
require_once 'model/persist/ProductDao.php';
require_once 'model/Product.php';

// PRODUCT IMPORTS
require_once 'model/persist/WarehousesProductsDao.php';
require_once 'model/StockEntity.php';

use proven\store\model\persist\UserDao;
use proven\store\model\persist\CategoryDao;
use proven\store\model\persist\ProductDao;
use proven\store\model\persist\WarehousesProductsDao;
//use proven\store\model\User;

/**
 * Service class to provide data.
 * @author ProvenSoft
 */
class StoreModel {


    public function __construct() {
    }
   
    // USER METHODS
    // =========================================
    public function findAllUsers(): array {
        $dbHelper = new UserDao();
        return $dbHelper->selectAll();
    }
    
    public function findUsersByRole(string $role): array {
        $dbHelper = new UserDao();
        return $dbHelper->selectWhere("role", $role);
    }

    public function addUser(User $user): int {
        $dbHelper = new UserDao();
        return $dbHelper->insert($user);
    }

    public function modifyUser(User $user): int {
        $dbHelper = new UserDao();
        return $dbHelper->update($user);
    }

    public function removeUser(User $user): int {
        $dbHelper = new UserDao();
        return $dbHelper->delete($user);
    }
    
    public function findUserById(int $id): ?User {
        $dbHelper = new UserDao();
        $u = new User($id);
        return $dbHelper->select($u);
    }

    // CATEGORY METHODS
    // =========================================
    public function findCategoryById(int $id): ?Category {
        $dbHelper = new CategoryDao();
        $tmpObj = new Category($id);
        return $dbHelper->select($tmpObj);
    }

    public function findCategoryByCode(string $code): ?Category {
        $dbHelper = new CategoryDao();
        $tmpObj = new Category(0, $code);
        return $dbHelper->selectByCode($tmpObj);
    }

    public function findAllCategories(): array {
        $dbHelper = new CategoryDao();
        return $dbHelper->selectAll();
    }

    public function modifyCategory(Category $category): int {
        $dbHelper = new CategoryDao();
        return $dbHelper->update($category);
    }

    public function removeCategory(Category $category): int {
        // Init category data access object.
        $categoryDao = new CategoryDao();
        $productDao = new ProductDao();

        $WarehousesProductsDao = new WarehousesProductsDao();
        // Find the products with the found category.
        $productsWithCategory = $this->findProductsByCategory($category);

        if (!empty($productsWithCategory)) {

            $stockEntities = [];
            foreach($productsWithCategory as $product) {
                $result = $WarehousesProductsDao->selectByProduct($product);

                if (!is_null($result)) {
                    array_push($stockEntities, $result);
                }
            }

            // ==============================================
            // First try to delete from the warehousesproducts table.
            // ==============================================
            if (!empty($stockEntities)) {
                $tmpRowCounter = 0;
                foreach($stockEntities as $stock) {
                    $tmpRowCounter += (int) $WarehousesProductsDao->delete($stock);
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
                foreach($productsWithCategory as $product) {
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
    public function findProductById(int $id): ?Product {
        $dbHelper = new ProductDao();
        $u = new Product($id);
        return $dbHelper->select($u);
    }

    public function findAllProducts(): array {
        $dbHelper = new ProductDao();
        return $dbHelper->selectAll();
    }

    public function findProductsByCategory(Category $category): array {
        $dbHelper = new ProductDao();
        return $dbHelper->selectAllByCategory($category);
    }

    public function modifyProduct(Product $category): int {
        $dbHelper = new ProductDao();
        return $dbHelper->update($category);
    }

    public function removeProduct(Product $category): int {
        $dbHelper = new ProductDao();
        return $dbHelper->delete($category);
    }
}

