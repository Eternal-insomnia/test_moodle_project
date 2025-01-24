<?php
// CONNECT TO DB
$host = 'localhost';
$dbname = 'task_tracker';
$username = 'taskuser';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connection to DB: " . $e->getMessage());
}