<?php
require_once "../models/Answer.php";
require_once "../models/Question.php";

class AnswerController
{
    private $model;

    public function __construct()
    {
        $this->model = new Answer();
    }

    // get all answers
    public function getAllAnswers()
    {
        $answers = $this->model->getAllAnswers();
        echo json_encode($answers);
    }

    public function getLastAnswers()
    {
        $answers = $this->model->getLastAnswers();
        echo json_encode($answers);
    }

    // get all answer by question ID
    public function getAllAnswersByQuestion()
    {
        $questionID = $_GET['questionID'];
        $answers = $this->model->getAllAnswersByQuestionID($questionID);
        echo json_encode($answers);
    }

    // create answer
    public function addAnswer()
    {
        $questionID = $_POST['questionID'];
        $answer = $_POST['answer'];
        $reference = $_POST['reference'];
        $userID = $_POST['userID'];
        $createdDate = $_POST['createdDate'];

        if (
            !isset($questionID) ||
            !isset($answer) ||
            !isset($reference) ||
            !isset($userID) ||
            !isset($createdDate) ||
            empty($questionID) ||
            empty($answer) ||
            empty($reference) ||
            empty($userID) ||
            empty($createdDate)
        ) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Please complete all information']);
            exit;
        }

        $this->model->create(
            $questionID,
            $answer,
            $reference,
            $userID,
            $createdDate
        );

        $questionModal = new Question();
        $questionModal->updateNumberOfAnswer($questionID);

        echo 'Answer create successfully';
    }
}

$controller = new AnswerController();

if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'getAllAnswers':
            $controller->getAllAnswers();
            break;
        case 'getLastAnswers':
            $controller->getLastAnswers();
            break;
        case 'getAllAnswersByQuestion':
            $controller->getAllAnswersByQuestion();
            break;
        case 'addAnswer':
            $controller->addAnswer();
            break;
        default:
            http_response_code(500);
            echo 'Error: Action not matched';
            break;
    }
}
