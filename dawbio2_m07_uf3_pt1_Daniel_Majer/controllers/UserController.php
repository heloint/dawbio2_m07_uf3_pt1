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
     * Displays user management page.
     * @return void
     */
    public function doUserMng() {
        if (isset($_SESSION['userrole'])) {
            if ($_SESSION['userrole'] === 'admin') {
                try {
                    //get all users.
                    $result = $this->model->findAllUsers();
                } catch (\ErrorException $e) {
                    $this->view->show("message.php", ['message' => "An error has occured in our server. Please try again later."]);
                }

                //pass list to view and show.
                $this->view->show("user/usermanage.php", ['list' => $result]);

            } else {
                $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
            }
        } else {
            $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
        }
    }

    /* Searches for users by their role
     * and passes an array<User> to the view.
     * @return void
     * */
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

    /* Searches for a user by it's id
     * and passes an User object to the view.
     * @return void
     * */
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
                $this->view->show("user/userdetail.php", $data);
            } else {
                $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
            }
        } else {
            $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
        }
    }

    /* Adds a new user to the database.
     * @return void
     * */
    public function doUserAdd() {
        if (isset($_SESSION['userrole'])) {
            if ($_SESSION['userrole'] === 'admin') {
                //get user data from form and validate
                $user = Validator::validateUser(INPUT_POST);
                //add user to database
                if (!is_null($user)) {
                    $result = $this->model->addUser($user);
                    $message = ($result > 0) ? "Successfully added":"Error adding the new user to the database.";

                    $savedUser = $user;
                    // Get informations about the already saved user.
                    if ($result > 0) {
                        $savedUser = $this->model->findUserByUsernameAndPassword(
                                                            $user->getUsername(),
                                                            $user->getPassword()
                        );
                    }
                    $this->view->show("user/userdetail.php", ['user' => $savedUser, 'mode' => 'add', 'message' => $message]);
                } else {
                    $message = "Invalid data";
                    $this->view->show("user/userdetail.php", ['user' => $user, 'mode' => 'add', 'message' => $message]);
                }
            } else {
                $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
            }
        } else {
            $this->view->show("message.php", ['message' => "Don't have permission to visit this page!"]);
        }

    }

    /* Modifies the informations of a user in the database.
     * @return void
     * */
    public function doUserModify() {
        //get user data from form and validate
        $user = Validator::validateUser(INPUT_POST);
        //add user to database
        if (!is_null($user)) {
            $result = $this->model->modifyUser($user);
            $message = ($result > 0) ? "Successfully modified":"Error modifying. No modification has been made.";
            $this->view->show("user/userdetail.php", ['user' => $user, 'mode' => 'edit', 'message' => $message]);
        } else {
            $message = "Invalid data";
            $this->view->show("user/userdetail.php", ['user' => $user, 'mode' => 'edit', 'message' => $message]);
        }
    }

    /* Removes a user from the database.
     * @return void
     * */
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

    /* Tries to validate the given credentials
     * in the form. If all good, then logs in the user
     * and creates the cookies.
     * @return void
     * */
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

}

