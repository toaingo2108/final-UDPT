<?php
require_once "../models/User.php";

class AuthController
{
    private $model;

    public function __construct()
    {
        $this->model = new User();
    }

    // get all users
    public function login()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (
            !isset($username) ||
            !isset($password) ||
            empty($username) ||
            empty($password)
        ) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Please complete all information']);
            exit;
        }
        $user = $this->model->login($username, $password);
        echo json_encode($user);
    }
}

$controller = new AuthController();

if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'login':
            $controller->login();
            break;

        default:
            http_response_code(500);
            echo 'Error: Action not matched';
            break;
    }
}
