<?php
require_once "../models/AnswerEvaluate.php";

class AnswerEvaluateController
{
    private $model;

    public function __construct()
    {
        $this->model = new AnswerEvaluate();
    }

    // get all answerEvaluates
    public function getAllAnswerEvaluates()
    {
        $answerEvaluates = $this->model->getAllAnswerEvaluates();
        echo json_encode($answerEvaluates);
    }

    public function getLastAnswersEvaluates()
    {
        $answers = $this->model->getLastAnswersEvaluates();
        echo json_encode($answers);
    }

    public function addEvaluate()
    {
        $answerID = $_POST['answerID'];
        $userID = $_POST['userID'];
        $rateCategory = $_POST['rateCategory'];
        $createdDate = $_POST['createdDate'];

        if (
            !isset($answerID) ||
            !isset($rateCategory) ||
            !isset($userID) ||
            !isset($createdDate) ||
            empty($answerID) ||
            empty($rateCategory) ||
            empty($userID) ||
            empty($createdDate)
        ) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Please complete all information']);
            exit;
        }

        $evaluate = $this->model->getByAnswerUser(
            $answerID,
            $userID
        );

        if (empty($evaluate)) {
            $this->model->create(
                $answerID,
                $userID,
                $rateCategory,
                $createdDate,
            );
            echo 'Evaluate added successfully';
        } else {
            $this->model->update(
                $answerID,
                $userID,
                $rateCategory,
                $createdDate,
            );
            echo 'Evaluate updated successfully';
        }
    }
}

$controller = new AnswerEvaluateController();

if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'getAllAnswerEvaluates':
            $controller->getAllAnswerEvaluates();
            break;
        case 'getLastAnswerEvaluates':
            $controller->getLastAnswersEvaluates();
            break;
        case 'addEvaluate':
            $controller->addEvaluate();
            break;

        default:
            http_response_code(500);
            echo 'Error: Action not matched';
            break;
    }
}
