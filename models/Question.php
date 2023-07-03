<?php

require_once '../config.inc.php';

class Question
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

    public function getAllQuestions($searchText)
    {
        $sql = "SELECT * FROM QUESTIONS 
        WHERE question LIKE :searchText 
        OR userID = :searchText 
        OR tags LIKE :searchText
        ORDER BY createdDate DESC";
        if (!isset($searchText) || empty($searchText)) {
            $sql = "SELECT * FROM QUESTIONS ORDER BY createdDate DESC";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':searchText', '%' . $searchText . '%');
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    // create
    public function create(
        $question,
        $userID,
        $tags,
        $createdDate
    ) {
        $sql =  'INSERT INTO 
                QUESTIONS (question, userID, tags, createdDate, numberAnswerers) 
                values (:question, :userID, :tags, :createdDate, 0)';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':question', $question);
        $stmt->bindValue(':userID', $userID);
        $stmt->bindValue(':tags', $tags);
        $stmt->bindValue(':createdDate', $createdDate);
        $stmt->execute();
    }

    public function updateNumberOfAnswer(
        $questionID
    ) {
        $sql =  'UPDATE QUESTIONS 
        SET numberAnswerers = numberAnswerers + 1 
        WHERE questionID = :questionID';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':questionID', $questionID);
        $stmt->execute();
    }
}
