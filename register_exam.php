<?php
include 'config.php';
requireAuth();

// Get student details
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$_SESSION['student_id']]);
$student = $stmt->fetch();

if (!$student) {
    $_SESSION['error'] = "Student not found!";
    header("Location: login.php");
    exit();
}

// Handle multi-subject registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subject_ids'])) {
    $selected_ids = $_POST['subject_ids'];

    foreach ($selected_ids as $subject_id) {
        // Validate subject for student's department/semester
        $stmt = $pdo->prepare("SELECT * FROM subjects WHERE id = ? AND department = ? AND semester = ?");
        $stmt->execute([$subject_id, $student['department'], $student['semester']]);
        $subject = $stmt->fetch();

        if ($subject) {
            // Check if already registered
            $stmt = $pdo->prepare("SELECT * FROM registered_subjects WHERE student_id = ? AND subject_id = ?");
            $stmt->execute([$_SESSION['student_id'], $subject_id]);

            if ($stmt->rowCount() == 0) {
                // Register
                $stmt = $pdo->prepare("INSERT INTO registered_subjects (student_id, subject_id) VALUES (?, ?)");
                $stmt->execute([$_SESSION['student_id'], $subject_id]);
            }
        }
    }

    $_SESSION['success'] = "Selected subjects registered successfully!";
    header("Location: dashboard.php");
    exit();
}

// Fetch subjects not already registered
$stmt = $pdo->prepare("SELECT * FROM subjects WHERE department = ? AND semester = ? AND id NOT IN (SELECT subject_id FROM registered_subjects WHERE student_id = ?)");
$stmt->execute([$student['department'], $student['semester'], $_SESSION['student_id']]);
$available_subjects = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <title>Register for Exam | Exam System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="h-full">
    <div class="min-h-full">
        <nav class="bg-indigo-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center space-x-6">
                        <a href="dashboard.php" class="text-indigo-200 hover:text-white">Dashboard</a>
                        <a href="register_exam.php" class="text-white font-bold">Register for Exam</a>
                        <a href="profile.php" class="text-indigo-200 hover:text-white">Profile</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <?php if ($student['image_path']): ?>
                            <img src="<?= htmlspecialchars($student['image_path']) ?>" class="h-8 w-8 rounded-full" alt="Profile">
                        <?php else: ?>
                            <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center text-white">U</div>
                        <?php endif; ?>
                        <span class="text-white text-sm"><?= htmlspecialchars($_SESSION['student_name']) ?></span>
                        <a href="generate_hallticket.php" class="ml-4 bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-sm font-medium">
                            Download Hall Ticket
                        </a>
                        <a href="logout.php" class="text-indigo-200 hover:text-white">Logout</a>
                    </div>
                </div>
            </div>
        </nav>

        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-gray-900">Register for Exams</h1>
            </div>
        </header>

        <main>
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Available Subjects (<?= htmlspecialchars($student['department']) ?> - Semester <?= htmlspecialchars($student['semester']) ?>)</h2>

                    <?php if (empty($available_subjects)): ?>
                        <p class="text-gray-500">No subjects available for registration.</p>
                    <?php else: ?>
                        <form method="POST" action="register_exam.php">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <?php foreach ($available_subjects as $subject): ?>
                                    <label class="border rounded-lg p-4 flex items-start gap-4 cursor-pointer hover:shadow">
                                        <input type="checkbox" name="subject_ids[]" value="<?= $subject['id'] ?>" class="mt-1 h-4 w-4 text-indigo-600">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($subject['name']) ?></h3>
                                            <p class="text-sm text-gray-500"><?= htmlspecialchars($subject['subject_code']) ?></p>
                                            <p class="text-sm text-gray-500">Exam Date: <?= date('M d, Y', strtotime($subject['exam_date'])) ?></p>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-6">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-medium">
                                    Register Selected Subjects
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>