<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    if ($action == 'login') {
        $usn = trim($_POST['usn']);
        $password = $_POST['password'];
        
        $stmt = $pdo->prepare("SELECT * FROM students WHERE usn = ?");
        $stmt->execute([$usn]);
        $student = $stmt->fetch();
        
        if ($student && password_verify($password, $student['password'])) {
            $_SESSION['student_id'] = $student['id'];
            $_SESSION['student_name'] = $student['first_name'] . ' ' . $student['last_name'];
            $_SESSION['student_usn'] = $student['usn'];
            $_SESSION['student_image'] = $student['image_path'];
            
            header("Location: dashboard.php");
            exit();
        } else {
            header("Location: index.php?error=1");
            exit();
        }
    }
}

header("Location: index.php");
exit();
?>