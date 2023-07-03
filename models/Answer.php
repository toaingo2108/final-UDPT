<?php

require_once '../config.inc.php';

class Answer
{
    private $db;

    public function __construct()
    {
        global $config;

        // connect to db 
        try {
            $dsn = 'mysql:host=' . $config['host'] . ';port=' . $config['port'] . ';dbname=' . $config['database'];
            $this->db = new PDO($dsn, $config['username'], $config['password']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo 'Database connection successfull!';
        } catch (PDOException $e) {
            // echo 'Database connection failed: ' . $e->getMessage();
        }
    }

    public function getAllAnswers()
    {
        $sql = "SELECT * FROM ANSWERS";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getLastAnswers()
    {
        $sql = "SELECT a.*, q.Question FROM ANSWERS a, QUESTIONS q
        WHERE a.QuestionID = q.QuestionID
        ORDER BY a.createdDate DESC LIMIT 10";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getAllAnswersByQuestionID($questionID)
    {
        $sql = "SELECT * FROM ANSWERS WHERE QuestionID = $questionID";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    // create
    public function create(
        $questionID,
        $answer,
        $reference,
        $userID,
        $createdDate
    ) {
        $sql =  'INSERT INTO 
                ANSWERS (questionID, answer, reference, userID, createdDate) 
                values (:questionID, :answer, :reference, :userID, :createdDate)';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':questionID', $questionID);
        $stmt->bindValue(':answer', $answer);
        $stmt->bindValue(':reference', $reference);
        $stmt->bindValue(':userID', $userID);
        $stmt->bindValue(':createdDate', $createdDate);
        $stmt->execute();
    }
}
