<?php
/* MainController
 * Managing all the in-coming and outgoing requests by
 * calling the corresponding controller to the operation.
 * @author Dániel Májer
 * */

namespace proven\store\controllers;

require_once "controllers/CategoryController.php";
require_once "controllers/ProductController.php";
require_once "controllers/UserController.php";
require_once "controllers/WarehouseController.php";

require_once "lib/ViewLoader.php";
require_once "lib/Validator.php";

require_once "model/StoreModel.php";
require_once "model/User.php";

use proven\store\model\StoreModel as Model;
use proven\lib\ViewLoader as View;

use proven\lib\views\Validator as Validator;

use proven\store\controllers\CategoryController;
use proven\store\controllers\ProductController;
use proven\store\controllers\UserController;
use proven\store\controllers\WarehouseController;

class MainController
{
    /**
     * @var ViewLoader
     */
    private $view;
    /**
     * @var Model
     */
    private $model;
    /**
     * @var CategoryController
     */
    private $categoryController;
    /**
     * @var ProductController
     */
    private $productController;
    /**
     * @var UserController
     */
    private $userController;
    /**
     * @var WarehouseController
     */
    private $warehouseController;
    /**
     * @var string
     */
    private $action;
    /**
     * @var string
     */
    private $requestMethod;

    public function __construct()
    {
        //instantiate the category controller.
        $this->categoryController = new CategoryController();
        //instantiate the product controller.
        $this->productController = new ProductController();
        //instantiate the user controller.
        $this->userController = new UserController();
        //instantiate the warehouse controller.
        $this->warehouseController = new WarehouseController();
        //instantiate the view loader.
        $this->view = new View();
        //instantiate the model.
        $this->model = new Model();
    }

    /* ============== HTTP REQUEST FUNCTIONS ============== */

    /**
     * processes requests from client, regarding action command.
     */
    public function processRequest()
    {
        $this->action = "";
        //retrieve action command requested by client.
        if (\filter_has_var(\INPUT_POST, "action")) {
            $this->action = \filter_input(\INPUT_POST, "action");
        } else {
            if (\filter_has_var(\INPUT_GET, "action")) {
                $this->action = \filter_input(\INPUT_GET, "action");
            } else {
                $this->action = "home";
            }
        }
        //retrieve request method.
        if (\filter_has_var(\INPUT_SERVER, "REQUEST_METHOD")) {
            $this->requestMethod = \strtolower(
                \filter_input(\INPUT_SERVER, "REQUEST_METHOD")
            );
        }
        //process action according to request method.
        switch ($this->requestMethod) {
            case "get":
                $this->doGet();
                break;
            case "post":
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
    private function doGet()
    {
        //process action.
        switch ($this->action) {
            case "home":
                $this->doHomePage();
                break;

            // USER
            case "user":
                $this->userController->doUserMng();
                break;
            case "user/edit":
                $this->userController->doUserEditForm("edit");
                break;

            // CATEGORY
            case "category":
                $this->categoryController->doCategoryMng();
                break;
            case "category/edit":
                $this->categoryController->doCategoryEditForm();
                break;

            // PRODUCT
            case "product":
                $this->productController->doProductMng();
                break;

            // WAREHOUSE
            case "warehouse":
                $this->warehouseController->doWareHouseMng();
                break;
            case "warehouse/edit":
                $this->warehouseController->doWarehouseEditForm("edit");
                break;

            // SESSION
            case "loginform":
                $this->doLoginForm();
                break;
            case "logout":
                $this->doLogout();
                break;

            default:
                //processing default action.
                $this->handleError();
                break;
        }
    }

    /**
     * processes post requests from client.
     */
    private function doPost()
    {
        //process action.
        switch ($this->action) {
            // USER
            case "user/role":
                $this->userController->doListUsersByRole();
                break;
            case "user/form":
                $this->userController->doUserEditForm("add");
                break;
            case "user/add":
                $this->userController->doUserAdd();
                break;
            case "user/modify":
                $this->userController->doUserModify();
                break;
            case "user/remove":
                $this->userController->doUserRemove();
                break;
            case "user/login":
                $this->userController->doUserLogin();
                break;

            // CATEGORY
            case "category/removeConfirmation":
                $this->categoryController->doCategoryRemovalConfirmation();
                break;
            case "category/cancelRemove":
                $this->categoryController->doCategoryMng();
                break;
            case "category/remove":
                $this->categoryController->doCategoryRemove();
                break;
            case "category/modify":
                $this->categoryController->doCategoryModify();
                break;

            // PRODUCT
            case "product/searchByCategory":
                $this->productController->doListProductsByCategory();
                break;
            case "product/addForm":
                $this->productController->doProductForm("add");
                break;
            case "product/removeConfirmation":
                $this->productController->doProductRemovalConfirmation();
                break;
            case "product/cancelRemove":
                $this->productController->doproductMng();
                break;
            case "product/remove":
                $this->productController->doProductRemove();
                break;
            case "product/editForm":
                $this->productController->doProductForm("edit");
                break;
            case "product/modify":
                $this->productController->doProductModify();
                break;
            case "product/add":
                $this->productController->doProductAdd();
                break;
            case "product/stocks":
                $this->productController->doProductStockInfo();
                break;
            case "product/searchByCode":
                $this->productController->doListStockByProduct();
                break;

            // WAREHOUSE
            case "warehouse/modify":
                $this->warehouseController->doWarehouseModify();
                break;
            case "warehouse/stocks":
                $this->warehouseController->doWarehouseStockInfo();
                break;

            default:
                //processing default action.
                $this->doHomePage();
                break;
        }
    }

    /* ============== NAVIGATION CONTROL METHODS ============== */
    /**
     * handles errors.
     */
    public function handleError()
    {
        $this->view->show("message.php", [
            "message" => "Something went wrong!",
        ]);
    }

    /**
     * displays home page content.
     */
    public function doHomePage()
    {
        $this->view->show("home.php", []);
    }

    /* ============== SESSION CONTROL METHODS ============== */
    /**
     * displays login form page.
     */
    public function doLoginForm()
    {
        $this->view->show("login/loginform.php", []); //initial prototype version;
    }

    /* Logs out the user, kills the cookies
     * and deletes the corresponding keys from the $_SESSION glob. var.
     * @return void
     * */
    public function doLogout()
    {
        unset($_SESSION["username"]);
        unset($_SESSION["userrole"]);
        setcookie(session_id(), "", time() - 3600);
        session_destroy();

        header("Location:index.php");
        exit();
    }

}
