<?php

namespace proven\store\controllers;

require_once 'lib/ViewLoader.php';
require_once 'lib/Validator.php';

require_once 'model/StoreModel.php';
require_once 'model/User.php';

use proven\store\model\StoreModel as Model;
use proven\lib\ViewLoader as View;

use proven\lib\views\Validator as Validator;

/**
 * Main controller
 * @author ProvenSoft
 */
class MainController {
    /**
     * @var ViewLoader
     */
    private $view;
    /**
     * @var Model
     */
    private $model;
    /**
     * @var string
     */
    private $action;
    /**
     * @var string
     */
    private $requestMethod;

    public function __construct() {
        //instantiate the view loader.
        $this->view = new View();
        //instantiate the model.
        $this->model = new Model();
    }

    /* ============== HTTP REQUEST FUNCTIONS ============== */

    /**
     * processes requests from client, regarding action command.
     */
    public function processRequest() {
        $this->action = "";
        //retrieve action command requested by client.
        if (\filter_has_var(\INPUT_POST, 'action')) {
            $this->action = \filter_input(\INPUT_POST, 'action');
        } else {
            if (\filter_has_var(\INPUT_GET, 'action')) {
                $this->action = \filter_input(\INPUT_GET, 'action');
            } else {
                $this->action = "home";
            }
        }
        //retrieve request method.
        if (\filter_has_var(\INPUT_SERVER, 'REQUEST_METHOD')) {
            $this->requestMethod = \strtolower(\filter_input(\INPUT_SERVER, 'REQUEST_METHOD'));
        }
        //process action according to request method.
        switch ($this->requestMethod) {
            case 'get':
                $this->doGet();
                break;
            case 'post':
                $this->doPost();
                break;
            default:
                $this->handleError();
                break;
        }
    }

    /**
     * processes get requests from client.
     */
    private function doGet() {
        //process action.
        switch ($this->action) {
            case 'home':
                $this->doHomePage();
                break;
            case 'user':
                $this->doUserMng();
                break;
            case 'user/edit':
                $this->doUserEditForm("edit");
                break;
            case 'category':
                $this->doCategoryMng();
                break;
            case 'category/edit':
                $this->doCategoryEditForm();
                break;
            case 'product':
                $this->doProductMng();
                break;
            case 'warehouse':
                $this->doWareHouseMng();
                break;
            case 'loginform':
                $this->doLoginForm();
                break;
            case 'logout':
                $this->doLogout();
                break;
            default:  //processing default action.
                $this->handleError();
                break;
        }
    }

    /**
     * processes post requests from client.
     */
    private function doPost() {
        //process action.
        switch ($this->action) {
            // USER
            case 'user/role':
                $this->doListUsersByRole();
                break;
            case 'user/form':
                $this->doUserEditForm("add");
                break;
            case 'user/add': 
                $this->doUserAdd();
                break;
            case 'user/modify': 
                $this->doUserModify();
                break;
            case 'user/remove': 
                $this->doUserRemove();
                break;
            case 'user/login': 
                $this->doUserLogin();
                break;

            // CATEGORY
            case 'category/removeConfirmation': 
                $this->doCategoryRemovalConfirmation();
                break;
            case 'category/cancelRemove': 
                $this->doCategoryMng();
                break;
            case 'category/remove': 
                $this->doCategoryRemove();
                break;
            case 'category/modify': 
                $this->doCategoryModify();
                break;

            // PRODUCT
            case 'product/searchByCategory': 
                $this->doListProductsByCategory();
                break;
            case 'product/addForm': 
                $this->doProductForm('add');
                break;
            case 'product/removeConfirmation': 
                $this->doProductRemovalConfirmation();
                break;
            case 'product/cancelRemove': 
                $this->doproductMng();
                break;
            case 'product/remove': 
                $this->doProductRemove();
                break;
            case 'product/editForm': 
                $this->doProductForm('edit');
                break;
            case 'product/modify': 
                $this->doProductModify();
                break;
            case 'product/add': 
                $this->doProductAdd();
                break;
            case 'product/stocks': 
                $this->doProductStockInfo();
                break;
            case 'product/searchByCode':
                $this->doListStockByProduct();
                break;

            default:  //processing default action.
                $this->doHomePage();
                break;
        }
    }

    /* ============== NAVIGATION CONTROL METHODS ============== */

    /**
     * handles errors.
     */
    public function handleError() {
        $this->view->show("message.php", ['message' => 'Something went wrong!']);
    }

    /**
     * displays home page content.
     */
    public function doHomePage() {
        $this->view->show("home.php", []);
    }

    /* ============== SESSION CONTROL METHODS ============== */

    /**
     * displays login form page.
     */
    public function doLoginForm() {
        $this->view->show("login/loginform.php", []);  //initial prototype version;
    }

    /* ============== USER MANAGEMENT CONTROL METHODS ============== --> COPIED */

    /**
     * displays user management page.
     */
    public function doUserMng() {
        if (isset($_SESSION['userrole'])) {
            if ($_SESSION['userrole'] === 'admin') {
                //get all users.
                $result = $this->model->findAllUsers();

                //pass list to view and show.
                $this->view->show("user/usermanage.php", ['list' => $result]);

            } else {
                $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
            }
        } else {
            $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
        }
    }

    public function doListUsersByRole() {
        if (isset($_SESSION['userrole'])) {
            if ($_SESSION['userrole'] === 'admin') {
                //get role sent from client to search.
                $roletoSearch = \filter_input(INPUT_POST, "search");
                if ($roletoSearch !== false) {
                    //get users with that role.
                    $result = $this->model->findUsersByRole($roletoSearch);
                    //pass list to view and show.
                    $this->view->show("user/usermanage.php", ['list' => $result]);
                }  else {
                    //pass information message to view and show.
                    $this->view->show("user/usermanage.php", ['message' => "No data found"]);
                }
            } else {
                $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
            }
        } else {
            $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
        }
    }

    public function doUserEditForm(string $mode) {
        if (isset($_SESSION['userrole'])) {
            if ($_SESSION['userrole'] === 'admin') {
                $data = array();
                if ($mode != 'user/add') {
                    //fetch data for selected user
                    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                    if (($id !== false) && (!is_null($id))) {
                        $user = $this->model->findUserById($id);
                        if (!is_null($user)) {
                            $data['user'] = $user;
                        }
                     }
                     $data['mode'] = $mode;
                }
                $this->view->show("user/userdetail.php", $data);  //initial prototype version.
            } else {
                $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
            }
        } else {
            $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
        }

    }

    public function doUserAdd() {
        //get user data from form and validate
        $user = Validator::validateUser(INPUT_POST);
        //add user to database
        if (!is_null($user)) {
            $result = $this->model->addUser($user);
            $message = ($result > 0) ? "Successfully added":"Error adding";
            $this->view->show("user/userdetail.php", ['mode' => 'add', 'message' => $message]);
        } else {
            $message = "Invalid data";
            $this->view->show("user/userdetail.php", ['mode' => 'add', 'message' => $message]);
        }
    }
    
    public function doUserModify() {
        //get user data from form and validate
        $user = Validator::validateUser(INPUT_POST);
        //add user to database
        if (!is_null($user)) {
            $result = $this->model->modifyUser($user);
            $message = ($result > 0) ? "Successfully modified":"Error modifying";
            $this->view->show("user/userdetail.php", ['mode' => 'add', 'message' => $message]);
        } else {
            $message = "Invalid data";
            $this->view->show("user/userdetail.php", ['mode' => 'add', 'message' => $message]);
        }
    }    

    public function doUserRemove() {
        //get user data from form and validate
        $user = Validator::validateUser(INPUT_POST);
        //add user to database
        if (!is_null($user)) {
            $result = $this->model->removeUser($user);
            $message = ($result > 0) ? "Successfully removed":"Error removing";
            $this->view->show("user/userdetail.php", ['mode' => 'add', 'message' => $message]);
        } else {
            $message = "Invalid data";
            $this->view->show("user/userdetail.php", ['mode' => 'add', 'message' => $message]);
        }
    }
    
    public function doUserLogin() {

        $params = null;

        $username = \filter_input(INPUT_POST, "username");
        $password = \filter_input(INPUT_POST, "password");

        // Empty fields trivial case.
        if (empty($username) ||
                 empty($password)
            ) {
            $params['emptyFields'] = true;
        }
        // Not empty and passed filtering.
        else if ($username !== false &&
            $password !== false) {

            //Get users with that username and password.
            $result = $this->model->findUserByUsernameAndPassword($username, $password);

            // Not empty, so found the user.
            if (!is_null($result)) {
                // TODO: DO the login, cookies, etc...
                $_SESSION["username"] = $result->getUsername();
                $_SESSION["userrole"] = $result->getRole();
                $_SESSION["userFullName"] = $result->getFirstname() . ' ' . $result->getLastname();

                $params["message"] = "Successful login.";
                header("Location: index.php");
                exit();
            // Empty, so hasn't found the user.
            } else {
                $params['invalidUsername'] = $username;
                $params['invalidPassword'] = $password;
            }

            //pass params to view and show.
            $this->view->show("login/loginform.php", $params);
        }  else {
            //pass information message to view and show.
            $this->view->show("login/loginform.php", $params);
        }
    }

    /* ============== CATEGORY MANAGEMENT CONTROL METHODS ============== --> COPIED */

    /**
     * displays category management page.
     */
    public function doCategoryMng() {
        //TODO
        $result = $this->model->findAllCategories();
        $this->view->show("category/categorymanage.php", ['list' => $result]);
        /* $this->view->show("message.php", ['message' => 'Not implemented yet!']); */
    }

    /* ============== PRODUCT MANAGEMENT CONTROL METHODS ============== --> COPIED*/

    /**
     * displays product management page.
     */
    public function doProductMng() {
        //TODO
        /* $categoryCode = filter_input(INPUT_POST, 'categoryCode'); */
        /* $products = $this->model->findProductsByCategory($categoryCode); */
        $products = $this->model->findAllProducts();
        $this->view->show("product/productmanage.php", ['list' => $products]);
    }

    /**
     * displays product management page.
     */
    public function doWarehouseMng() {
        //TODO
        $this->view->show("message.php", ['message' => 'Not implemented yet!']);
    }


    // CATEGORY METHODS --> COPIED
    // ==================================================
    public function doCategoryRemovalConfirmation() {
        if (isset($_SESSION['userrole'])) {
            if ($_SESSION['userrole'] === 'admin' ||
                $_SESSION['userrole'] === 'staff') {

                $id = filter_input(INPUT_POST, 'categoryId', FILTER_VALIDATE_INT);
                $categoryToDelete = $this->model->findCategoryById($id);
                $this->view->show("category/categoryRemovalConfirmation.php", ['category' => $categoryToDelete]);

            } else {
                $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
            }
        } else {
            $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
        }
    }

    public function doCategoryRemove() {
        if (isset($_SESSION['userrole'])) {
            if ($_SESSION['userrole'] === 'admin' ||
                $_SESSION['userrole'] === 'staff') {

                $affectedRowNum = 0;
                $deletionResult = false;

                // Find the category
                $id = filter_input(INPUT_POST, 'categoryId', FILTER_VALIDATE_INT);
                $categoryToDelete = $this->model->findCategoryById($id);

                if (!is_null($categoryToDelete)) {
                    $affectedRowNum = $this->model->removeCategory($categoryToDelete);
                }

                if ($affectedRowNum > 0) {
                    $deletionResult = true;
                }

                $allCategories = $this->model->findAllCategories();

                $data = [
                    'list' => $allCategories,
                    'deletionResult' => $deletionResult,
                    'deletedId' => $id
                ];

                $this->view->show("category/categorymanage.php", $data);

            } else {
                $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
            }
        } else {
            $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
        }
    }

    public function doCategoryEditForm() {
        // TODO
        $data = array();
        //fetch data for selected user
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (($id !== false) && (!is_null($id))) {
            $category = $this->model->findCategoryById($id);
            if (!is_null($category)) {
                $data['category'] = $category;
            }
         }

        $this->view->show("category/categorydetail.php", $data);  //initial prototype version.
    }

    public function doCategoryModify() {

        $category = Validator::validateCategory(INPUT_POST);

        if (!is_null($category)) {
            $result = $this->model->modifyCategory($category);
            $message = ($result > 0) ? "Successfully modified" : "Error modifying";
            $this->view->show("category/categorydetail.php", ['message' => $message, 'category' => $category]);
        } else {
            $message = "Invalid data";
            $this->view->show("category/categorydetail.php", ['message' => $message, 'category' => $category]);
        }
    }


    // PRODUCT METHODS --> COPIED
    // ==================================================
    public function doListProductsByCategory() {
        //get role sent from client to search.
        $categoryToSearch = \filter_input(INPUT_POST, "search");
        if ($categoryToSearch !== false) {
            //get users with that role.
            $foundCategory = $this->model->findCategoryByCode($categoryToSearch);
            $result = $this->model->findProductsByCategory($foundCategory);
            //pass list to view and show.
            $this->view->show("product/productmanage.php", ['list' => $result, 'searchedCategory' => $categoryToSearch]);
        }  else {
            //pass information message to view and show.
            $this->view->show("product/productmanage.php", ['message' => "No data found"]);
        }
    }
    

    public function doProductForm($mode) {
        if (isset($_SESSION['userrole'])) {
            if ($_SESSION['userrole'] === 'admin' ||
                $_SESSION['userrole'] === 'staff') {
                $data = array(); 
                $data['mode'] = $mode;
                if ($mode != 'add') {
                    //fetch data for selected user
                    $id = filter_input(INPUT_POST, 'productId', FILTER_VALIDATE_INT);

                    if (($id !== false) && (!is_null($id))) {
                        $product = $this->model->findProductById($id);
                        if (!is_null($product)) {
                            $data['product'] = $product;
                        }
                     }
                }
                $this->view->show("product/productdetail.php", $data);
            } else {
                $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
            }
        } else {
            $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
        }
    }

    public function doProductAdd() {

        if (isset($_SESSION['userrole'])) {
            if ($_SESSION['userrole'] === 'admin' ||
                $_SESSION['userrole'] === 'staff') {

                //get product data from form and validate
                $product = Validator::validateProduct(INPUT_POST);

                //add product to database
                if (!is_null($product)) {
                    $result = $this->model->addProduct($product);
                    $message = ($result > 0) ? "Successfully added":"Error adding";
                    $this->view->show("product/productdetail.php", ['mode' => 'add', 'message' => $message, 'product' => $product]);
                } else {
                    $message = "Invalid data";
                    $this->view->show("product/productdetail.php", ['mode' => 'add', 'message' => $message, 'product' => $product]);
                }

            } else {
                $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
            }
        } else {
            $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
        }
    }

    public function doProductModify() {
        if (isset($_SESSION['userrole'])) {
            if ($_SESSION['userrole'] === 'admin' ||
                $_SESSION['userrole'] === 'staff') {

                //get user data from form and validate
                $product = Validator::validateProduct(INPUT_POST);
                if (!is_null($product)) {
                    $result = $this->model->modifyProduct($product);
                    $message = ($result > 0) ? "Successfully modified":"Error modifying";
                    $this->view->show("product/productdetail.php", ['mode' => 'edit', 'message' => $message, 'product' => $product]);
                } else {
                    $message = "Invalid data";
                    $this->view->show("product/productdetail.php", ['mode' => 'edit', 'message' => $message, 'product' => $product]);
                }

            } else {
                $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
            }
        } else {
            $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
        }

    }

    public function doProductRemovalConfirmation() {

        if (isset($_SESSION['userrole'])) {
            if ($_SESSION['userrole'] === 'admin' ||
                $_SESSION['userrole'] === 'staff') {

                $id = filter_input(INPUT_POST, 'productId', FILTER_VALIDATE_INT);
                $productToDelete = $this->model->findProductById($id);
                $this->view->show("product/productRemovalConfirmation.php", ['product' => $productToDelete]);

            } else {
                $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
            }
        } else {
            $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
        }
    }

    public function doProductRemove() {
        if (isset($_SESSION['userrole'])) {
            if ($_SESSION['userrole'] === 'admin' ||
                $_SESSION['userrole'] === 'staff') {

                $affectedRowNum = 0;
                $deletionResult = false;

                $id = filter_input(INPUT_POST, 'productId', FILTER_VALIDATE_INT);

                $productToDelete = $this->model->findProductById($id);

                if (!is_null($productToDelete)) {
                    $affectedRowNum = $this->model->removeProduct($productToDelete);
                }

                if ($affectedRowNum > 0) {
                    $deletionResult = true;
                }

                $allProducts = $this->model->findAllProducts();

                $data = [
                    'list' => $allProducts,
                    'deletionResult' => $deletionResult,
                    'deletedId' => $id
                ];

                $this->view->show("product/productmanage.php", $data);

            } else {
                $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
            }
        } else {
            $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
        }

    }

    private function formatTableData(array $warehouses, array $productStockRegisters): array
    {
        $tableData = array();

        // First get those warehouses, which have the product in stock.
        foreach($warehouses as $warehouse) {
            foreach($productStockRegisters as $stock) {
                if ((int) $stock->getWarehouseId() === (int) $warehouse->getId()) {
                    \array_push($tableData, [
                        'id' => $warehouse->getId(),
                        'code' => $warehouse->getCode(),
                        'address' => $warehouse->getAddress(),
                        'stock'   => $stock->getStock()
                    ]);
                }
            }
        }

        // Sort the found warehouses by their stock value.
        array_multisort(array_column($tableData, 'stock'), SORT_DESC, $tableData);

        // After that filter out the warehouses,
        // which already included in $tableData.
        $missingProductWarehouses = \array_filter($warehouses, function($warehouse) use ($tableData) {
                                        if (\array_key_exists($warehouse->getId(), $tableData)) {
                                            return false;
                                        }
                                        return true;
                                    });

        // Finally fetch the filtered warehouses 
        // with stock value as 0.
        foreach($missingProductWarehouses as $warehouse) {
            \array_push($tableData, [
                'id' => $warehouse->getId(),
                'code' => $warehouse->getCode(),
                'address' => $warehouse->getAddress(),
                'stock'   => 0
            ]);
        }

        return $tableData;
    }

    public function doProductStockInfo() {
        $data = array(); 

        //fetch data for selected product
        $id = filter_input(INPUT_POST, 'productId', FILTER_VALIDATE_INT);

        if (($id !== false) && (!is_null($id))) {
            // Get product
            $product = $this->model->findProductById($id);
            if (!is_null($product)) {
                $data['product'] = $product;
            }

            // Get product-warehouse infos.
            $productStockRegisters = $this->model->findStocksByProduct($product);
            if (!is_null($productStockRegisters )) {
                $data['productStockRegisters'] = $productStockRegisters;
            }

            // Get warehouse infos.
            $warehouses = $this->model->findAllWarehouses();
            if (!is_null($warehouses)) {
                $data['warehouses'] = $warehouses;
            }

            $data['tableData'] = $this->formatTableData($warehouses, $productStockRegisters);
         }

        $this->view->show("product/productStock.php", $data);
    }

    public function doListStockByProduct() {
        $data = array();
        //get role sent from client to search.
        $productToSearch = \filter_input(INPUT_POST, "search");
        if ($productToSearch !== false) {
            //get users with that role.
            $foundProduct = $this->model->findProductByCode($productToSearch);

            if (!is_null($foundProduct)) {
                $data['product'] = $foundProduct;
                // Get product-warehouse infos.
                $productStockRegisters = $this->model->findStocksByProduct($foundProduct);
                if (!is_null($productStockRegisters )) {
                    $data['productStockRegisters'] = $productStockRegisters;
                }

                // Get warehouse infos.
                $warehouses = $this->model->findAllWarehouses();
                if (!is_null($warehouses)) {
                    $data['warehouses'] = $warehouses;
                }

                $data['tableData'] = $this->formatTableData($warehouses, $productStockRegisters);
             }
            //pass list to view and show.
            $this->view->show("product/productStock.php", $data);
        }  else {
            //pass information message to view and show.
            $this->view->show("product/productmanage.php", ['message' => "No data found"]);
        }
    }

    public function doLogout() {
        unset($_SESSION["username"]);
        unset($_SESSION["userrole"]);
        setcookie(session_id(), "", time() - 3600);
        session_destroy();

        header("Location:index.php");
        exit();
    }
}
