<?php

namespace proven\store\controllers;

require_once 'lib/ViewLoader.php';
require_once 'lib/Validator.php';

require_once 'model/StoreModel.php';
require_once 'model/User.php';

use proven\store\model\StoreModel as Model;
use proven\lib\ViewLoader as View;

use proven\lib\views\Validator as Validator;

/* ============== USER MANAGEMENT CONTROL METHODS ============== */
class UserController {

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
     * displays user management page.
     */
    public function doUserMng() {
        //get all users.
        $result = $this->model->findAllUsers();
        //pass list to view and show.
        $this->view->show("user/usermanage.php", ['list' => $result]);
        //$this->view->show("user/user.php", [])  //initial prototype version;
    }

    public function doListUsersByRole() {
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
    }

    public function doUserEditForm(string $mode) {
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
}

