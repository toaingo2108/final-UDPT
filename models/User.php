<?php

require_once '../config.inc.php';

class User
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

    public function getAllUsers()
    {
        $sql = "SELECT * FROM USERS";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function login($username, $password)
    {
        $sql = "SELECT userID, username, role FROM USERS WHERE username = :username AND password = :password";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function updateRole($userID, $role)
    {
        $sql = 'UPDATE USERS 
                SET role = :role 
                WHERE userID = :userID';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':userID', $userID);
        $stmt->bindValue(':role', $role);
        $stmt->execute();
    }
}
