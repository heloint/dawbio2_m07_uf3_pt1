<?php
namespace proven\store\controllers;

require_once "lib/ViewLoader.php";
require_once "lib/Validator.php";

require_once "model/StoreModel.php";
require_once "model/User.php";

use proven\store\model\StoreModel as Model;
use proven\lib\ViewLoader as View;

use proven\lib\views\Validator as Validator;

/* ============== CATEGORY MANAGEMENT CONTROL METHODS ============== --> COPIED */
class CategoryController
{
    /**
     * @var ViewLoader
     */
    private $view;

    /**
     * @var Model
     */
    private $model;

    public function __construct()
    {
        //instantiate the view loader.
        $this->view = new View();
        //instantiate the model.
        $this->model = new Model();
    }

    /**
     * Displays category management page.
     * @return void
     */
    public function doCategoryMng()
    {
        try {
            $result = $this->model->findAllCategories();
            $this->view->show("category/categorymanage.php", [
                "list" => $result,
            ]);
        } catch (\ErrorException $e) {
            $this->view->show("message.php", [
                "message" =>
                    "An error has occured in our server. Please try again later.",
            ]);
        }
    }

    /* Redirects the user to the view of the confirmation of deleting a category.
     * @return void
     * */
    public function doCategoryRemovalConfirmation()
    {
        if (isset($_SESSION["userrole"])) {
            if (
                $_SESSION["userrole"] === "admin" ||
                $_SESSION["userrole"] === "staff"
            ) {
                $id = filter_input(
                    INPUT_POST,
                    "categoryId",
                    FILTER_VALIDATE_INT
                );
                $categoryToDelete = $this->model->findCategoryById($id);
                $this->view->show("category/categoryRemovalConfirmation.php", [
                    "category" => $categoryToDelete,
                ]);
            } else {
                $this->view->show("message.php", [
                    "message" => "Don't have permission to visit this page!",
                ]);
            }
        } else {
            $this->view->show("message.php", [
                "message" => "Don't have permission to visit this page!",
            ]);
        }
    }

    /* Removes the category.
     * @return void
     * */
    public function doCategoryRemove()
    {
        if (isset($_SESSION["userrole"])) {
            if (
                $_SESSION["userrole"] === "admin" ||
                $_SESSION["userrole"] === "staff"
            ) {
                $affectedRowNum = 0;
                $deletionResult = false;

                // Find the category
                $id = filter_input(
                    INPUT_POST,
                    "categoryId",
                    FILTER_VALIDATE_INT
                );
                $categoryToDelete = $this->model->findCategoryById($id);

                if (!is_null($categoryToDelete)) {
                    $affectedRowNum = $this->model->removeCategory(
                        $categoryToDelete
                    );
                }

                if ($affectedRowNum > 0) {
                    $deletionResult = true;
                }

                $allCategories = $this->model->findAllCategories();

                $data = [
                    "list" => $allCategories,
                    "deletionResult" => $deletionResult,
                    "deletedId" => $id,
                ];

                $this->view->show("category/categorymanage.php", $data);
            } else {
                $this->view->show("message.php", [
                    "message" => "Don't have permission to visit this page!",
                ]);
            }
        } else {
            $this->view->show("message.php", [
                "message" => "Don't have permission to visit this page!",
            ]);
        }
    }

    /* Redirects the user to the view of edition of a category.
     * @return void
     * */
    public function doCategoryEditForm()
    {
        $data = [];
        //fetch data for selected user
        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

        if ($id !== false && !is_null($id)) {
            $category = $this->model->findCategoryById($id);
            if (!is_null($category)) {
                $data["category"] = $category;
            }
        }

        $this->view->show("category/categorydetail.php", $data); //initial prototype version.
    }

    /* Modifies the category in the database.
     * @return void
     * */
    public function doCategoryModify()
    {
        $category = Validator::validateCategory(INPUT_POST);

        $result = -1;
        if (!is_null($category)) {
            $result = $this->model->modifyCategory($category);
            $message =
                $result > 0 ? "Successfully modified" : "Error modifying";
            $this->view->show("category/categorydetail.php", [
                "result" => $result,
                "message" => $message,
                "category" => $category,
            ]);
        } else {
            $message = "Invalid data";
            $this->view->show("category/categorydetail.php", [
                "result" => $result,
                "message" => $message,
                "category" => $category,
            ]);
        }
    }
}
