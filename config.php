<?php
// config.php
$host = 'localhost';
$dbname = 'exam_registration_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed.");
}

session_start();

function requireAuth() {
    if (!isset($_SESSION['student_id'])) {
        header("Location: index.php");
        exit();
    }
}
?>