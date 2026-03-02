<?php
include 'config.php';
requireAuth();

// Get student details
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$_SESSION['student_id']]);
$student = $stmt->fetch();

// Check if student exists
if (!$student) {
    $_SESSION['error'] = "Student not found!";
    header("Location: login.php");
    exit();
}

// Get registered subjects
$stmt = $pdo->prepare("SELECT s.* FROM subjects s 
                        JOIN registered_subjects rs ON s.id = rs.subject_id 
                        WHERE rs.student_id = ?");
$stmt->execute([$_SESSION['student_id']]);
$registered_subjects = $stmt->fetchAll();

// Get available subjects (not registered yet)
$stmt = $pdo->prepare("SELECT * FROM subjects WHERE department = ? AND semester = ? 
                        AND id NOT IN (SELECT subject_id FROM registered_subjects WHERE student_id = ?)");
$stmt->execute([$student['department'], $student['semester'], $_SESSION['student_id']]);
$available_subjects = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Exam System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="h-full">
    <div class="min-h-full">
        <nav class="bg-indigo-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <!-- Logo or Name here -->
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <a href="dashboard.php" class="bg-indigo-700 text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                                <a href="register_exam.php" class="text-indigo-200 hover:bg-indigo-500 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Register for Exam</a>
                                <a href="profile.php" class="text-indigo-200 hover:bg-indigo-500 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Profile</a>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">
                            <div class="ml-3 relative">
                                <div class="flex items-center">
                                    <?php if ($student['image_path']): ?>
                                        <img class="h-8 w-8 rounded-full" src="<?php echo htmlspecialchars($student['image_path']); ?>" alt="Profile">
                                    <?php else: ?>
                                        <span class="inline-block h-8 w-8 rounded-full overflow-hidden bg-gray-100">
                                            <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </span>
                                    <?php endif; ?>
                                    <span class="ml-2 text-white text-sm font-medium"><?php echo htmlspecialchars($_SESSION['student_name']); ?></span>
                                </div>
                            </div>
                            <a href="generate_hallticket.php" class="ml-4 bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-sm font-medium">
                                Download Hall Ticket
                            </a>
                            <a href="logout.php" class="ml-4 text-indigo-200 hover:text-white text-sm font-medium">Sign out</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            </div>
        </header>

        <main>
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div class="px-4 py-6 sm:px-0">
                    <div class="border-4 border-dashed border-gray-200 rounded-lg p-6">
                        <!-- Registered Subjects Section -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Subjects Registered</h2>
                            <?php if (empty($registered_subjects)): ?>
                                <p class="text-gray-500">You haven't registered for any subjects yet.</p>
                            <?php else: ?>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exam Date</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject Code</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject Name</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Credits</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <?php foreach ($registered_subjects as $subject): ?>
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo date('M d, Y', strtotime($subject['exam_date'])); ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($subject['subject_code']); ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($subject['name']); ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($subject['credits']); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>