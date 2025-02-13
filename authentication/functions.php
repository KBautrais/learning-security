<?php

function connectDb()
{
    try {
        $conn = new PDO("mysql:host=127.0.0.1;dbname=authentication", 'root', '');
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        return null;
    }
}

function logUser($email, $password)
{
    $connexion = connectDb();
    $sql = 'SELECT * FROM users WHERE email = "' . $email . '"';
    $stmt = $connexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getUser($id) {
    $connexion = connectDb();
    $sql = 'SELECT * FROM users WHERE id = ' . $id;
    $stmt = $connexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getUserByEmail($email) {
    $connection = connectDb();
    $sql = 'SELECT * FROM users WHERE email = ?';
    $values = [$email];
    $stmt= $connection->prepare($sql);
    $stmt->execute($values);

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function saveUser($email, $username, $password) {
    $hashpassword = password_hash($password, PASSWORD_BCRYPT);
    $connexion = connectDb();
    $sql = 'INSERT INTO users(username,email,password) VALUES("'.$email.'","'.$username.'","'.$hashpassword.'")';
    $stmt = $connexion->prepare($sql);

    return $stmt->execute();
}