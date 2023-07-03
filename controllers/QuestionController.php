<?php
require_once "../models/Question.php";

class QuestionController
{
    private $model;

    public function __construct()
    {
        $this->model = new Question();
    }

    // get all questions
    public function getAllQuestions()
    {
        $searchText = $_GET['searchText'];
        $questions = $this->model->getAllQuestions($searchText);
        echo json_encode($questions);
    }

    // create question
    public function addQuestion()
    {
        $question = $_POST['question'];
        $userID = $_POST['userID'];
        $tags = $_POST['tags'];
        $createdDate = $_POST['createdDate'];

        if (
            !isset($question) ||
            !isset($userID) ||
            !isset($tags) ||
            !isset($createdDate) ||
            empty($question) ||
            empty($userID) ||
            empty($tags) ||
            empty($createdDate)
        ) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Please complete all information']);
            exit;
        }

        $this->model->create(
            $question,
            $userID,
            $tags,
            $createdDate,
        );
        echo 'Question create successfully';
    }
}

$controller = new QuestionController();

if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'getAllQuestions':
            $controller->getAllQuestions();
            break;

        case 'addQuestion':
            $controller->addQuestion();
            break;

        default:
            http_response_code(500);
            echo 'Error: Action not matched';
            break;
    }
}
