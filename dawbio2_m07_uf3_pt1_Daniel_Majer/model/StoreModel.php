<?php
namespace proven\store\model;

// USER IMPORTS
require_once 'model/persist/UserDao.php';
require_once 'model/User.php';

// CATEGORY IMPORTS
require_once 'model/persist/CategoryDao.php';
require_once 'model/Category.php';

use proven\store\model\persist\UserDao;
use proven\store\model\persist\CategoryDao;
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
        $u = new Category($id);
        return $dbHelper->select($u);
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
        $dbHelper = new CategoryDao();
        return $dbHelper->delete($category);
    }
}

