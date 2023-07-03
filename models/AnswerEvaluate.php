<?php

require_once '../config.inc.php';

class AnswerEvaluate
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

    public function getAllAnswerEvaluates()
    {
        $sql = "SELECT * FROM ANSWER_EVALUATES";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getLastAnswersEvaluates()
    {
        $sql = "SELECT e.*, a.Answer FROM ANSWER_EVALUATES e, ANSWERS a
        WHERE e.AnswerID = a.AnswerID
        ORDER BY e.createdDate DESC LIMIT 10";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function create(
        $answerID,
        $userID,
        $rateCategory,
        $createdDate
    ) {
        $sql =  'INSERT INTO 
                ANSWER_EVALUATES (answerID, userID, rateCategory, createdDate) 
                values (:answerID, :userID, :rateCategory, :createdDate)';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':answerID', $answerID);
        $stmt->bindValue(':userID', $userID);
        $stmt->bindValue(':rateCategory', $rateCategory);
        $stmt->bindValue(':createdDate', $createdDate);
        $stmt->execute();
    }

    public function update(
        $answerID,
        $userID,
        $rateCategory,
        $createdDate
    ) {
        $sql = 'UPDATE ANSWER_EVALUATES 
        SET rateCategory = :rateCategory, 
        createdDate = :createdDate 
        WHERE answerID = :answerID and userID = :userID';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':answerID', $answerID);
        $stmt->bindValue(':userID', $userID);
        $stmt->bindValue(':rateCategory', $rateCategory);
        $stmt->bindValue(':createdDate', $createdDate);
        $stmt->execute();
    }

    public function getByAnswerUser(
        $answerID,
        $userID
    ) {
        $sql = "SELECT * FROM ANSWER_EVALUATES WHERE answerID = :answerID and userID = :userID";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':answerID', $answerID);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
}
