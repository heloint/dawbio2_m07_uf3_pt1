<?php
namespace proven\store\controllers;

require_once 'lib/ViewLoader.php';
require_once 'lib/Validator.php';

require_once 'model/StoreModel.php';
require_once 'model/User.php';

use proven\store\model\StoreModel as Model;
use proven\lib\ViewLoader as View;

use proven\lib\views\Validator as Validator;

/* ============== CATEGORY MANAGEMENT CONTROL METHODS ============== --> COPIED */
class CategoryController {

    /**
     * @var ViewLoader
     */
    private $view;

    /**
     * @var Model
     */
    private $model;


    public function __construct() {
        //instantiate the view loader.
        $this->view = new View();
        //instantiate the model.
        $this->model = new Model();
    }


    /**
     * displays category management page.
     */
    public function doCategoryMng() {
        //TODO
        $result = $this->model->findAllCategories();
        $this->view->show("category/categorymanage.php", ['list' => $result]);
        /* $this->view->show("message.php", ['message' => 'Not implemented yet!']); */
    }

    // CATEGORY METHODS
    // ==================================================
    public function doCategoryRemovalConfirmation() {
        $id = filter_input(INPUT_POST, 'categoryId', FILTER_VALIDATE_INT);
        $categoryToDelete = $this->model->findCategoryById($id);
        $this->view->show("category/categoryRemovalConfirmation.php", ['category' => $categoryToDelete]);
    }

    public function doCategoryRemove() {
        // TODO: ASK PROFESSOR IF DELETE THE PRODUCTS TOO OR NOT. BECAUSE OF THE CONSTRAINT.
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
}
