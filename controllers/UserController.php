<?php
require_once "../models/User.php";

class UserController
{
    private $model;

    public function __construct()
    {
        $this->model = new User();
    }

    // get all users
    public function getAllUsers()
    {
        $users = $this->model->getAllUsers();
        echo json_encode($users);
    }

    public function updateRole()
    {
        $userID = $_POST['userID'];
        $role = $_POST['role'];

        if (
            !isset($userID) ||
            !isset($role) ||
            empty($userID) ||
            empty($role)
        ) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Please complete all information']);
            exit;
        }

        $this->model->updateRole(
            $userID,
            $role,
        );
        echo 'Role updated successfully';
    }
}

$controller = new UserController();

if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'getAllUsers':
            $controller->getAllUsers();
            break;
        case 'updateRole':
            $controller->updateRole();
            break;

        default:
            http_response_code(500);
            echo 'Error: Action not matched';
            break;
    }
}
