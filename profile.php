<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';
requireAuth();

// Get student details
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$_SESSION['student_id']]);
$student = $stmt->fetch();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];
    $success = '';

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone = trim($_POST['phone']);
    $semester = trim($_POST['semester']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if (empty($first_name)) $errors[] = "First name is required";
    if (empty($last_name)) $errors[] = "Last name is required";
    if (empty($phone)) $errors[] = "Phone number is required";
    if (empty($semester)) $errors[] = "Semester is required";

    // Password change logic
    $password_changed = false;
    if (!empty($current_password)) {
        if (!password_verify($current_password, $student['password'])) {
            $errors[] = "Current password is incorrect";
        } elseif (strlen($new_password) < 6) {
            $errors[] = "New password must be at least 6 characters";
        } elseif ($new_password !== $confirm_password) {
            $errors[] = "New passwords do not match";
        } else {
            $password_changed = true;
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        }
    }

    // Process image upload
    $image_path = $student['image_path'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        
        // Ensure upload directory exists and is writable
        if (!file_exists($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                $errors[] = "Failed to create upload directory";
            }
        }
        
        // Validate file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $_FILES['image']['tmp_name']);
        finfo_close($file_info);
        
        if (!in_array($mime_type, $allowed_types)) {
            $errors[] = "Only JPG, PNG, and GIF files are allowed";
        } else {
            // Delete old image if exists
            if (!empty($image_path) && file_exists($image_path)) {
                if (!unlink($image_path)) {
                    $errors[] = "Failed to remove old image";
                }
            }
            
            // Generate unique filename
            $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $file_name = 'profile_' . $_SESSION['student_id'] . '_' . time() . '.' . $file_ext;
            $target_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $image_path = $target_path;
            } else {
                $errors[] = "Failed to upload image";
            }
        }
    }

    if (empty($errors)) {
        try {
            // Update profile
            if ($password_changed) {
                $stmt = $pdo->prepare("UPDATE students SET first_name = ?, last_name = ?, phone = ?, semester = ?, image_path = ?, password = ? WHERE id = ?");
                $stmt->execute([$first_name, $last_name, $phone, $semester, $image_path, $hashed_password, $_SESSION['student_id']]);
            } else {
                $stmt = $pdo->prepare("UPDATE students SET first_name = ?, last_name = ?, phone = ?, semester = ?, image_path = ? WHERE id = ?");
                $stmt->execute([$first_name, $last_name, $phone, $semester, $image_path, $_SESSION['student_id']]);
            }

            // Update session
            $_SESSION['student_name'] = $first_name . ' ' . $last_name;
            if ($image_path) $_SESSION['student_image'] = $image_path;

            $success = "Profile updated successfully!";
            
            // Refresh student data
            $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
            $stmt->execute([$_SESSION['student_id']]);
            $student = $stmt->fetch();
            
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $errors[] = "Database error occurred while updating profile";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Exam System</title>
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
                            
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <a href="dashboard.php" class="text-indigo-200 hover:bg-indigo-500 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                                <a href="register_exam.php" class="text-indigo-200 hover:bg-indigo-500 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Register for Exam</a>
                                <a href="profile.php" class="bg-indigo-700 text-white px-3 py-2 rounded-md text-sm font-medium">Profile</a>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">
                            <div class="ml-3 relative">
                                <div class="flex items-center">
                                    <?php if (!empty($student['image_path'])): ?>
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
                <h1 class="text-3xl font-bold text-gray-900">Profile</h1>
            </div>
        </header>
        <main>
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div class="px-4 py-6 sm:px-0">
                    <div class="border-4 border-dashed border-gray-200 rounded-lg p-6">
                        <?php if (!empty($errors)): ?>
                            <div class="rounded-md bg-red-50 p-4 mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle h-5 w-5 text-red-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <ul class="list-disc pl-5 space-y-1">
                                                <?php foreach ($errors as $error): ?>
                                                    <li><?php echo htmlspecialchars($error); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($success)): ?>
                            <div class="rounded-md bg-green-50 p-4 mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle h-5 w-5 text-green-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-green-800"><?php echo htmlspecialchars($success); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form class="space-y-6" method="POST" action="profile.php" enctype="multipart/form-data">
                            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                                <div class="md:grid md:grid-cols-3 md:gap-6">
                                    <div class="md:col-span-1">
                                        <h3 class="text-lg font-medium leading-6 text-gray-900">Personal Information</h3>
                                        <p class="mt-1 text-sm text-gray-500">Update your personal details.</p>
                                    </div>
                                    <div class="mt-5 md:mt-0 md:col-span-2">
                                        <div class="grid grid-cols-6 gap-6">
                                            <div class="col-span-6 sm:col-span-3">
                                                <label for="first_name" class="block text-sm font-medium text-gray-700">First name</label>
                                                <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($student['first_name']); ?>" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            </div>

                                            <div class="col-span-6 sm:col-span-3">
                                                <label for="last_name" class="block text-sm font-medium text-gray-700">Last name</label>
                                                <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            </div>

                                            <div class="col-span-6 sm:col-span-4">
                                                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                                                <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($student['email']); ?>" disabled class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-100">
                                            </div>

                                            <div class="col-span-6 sm:col-span-4">
                                                <label for="usn" class="block text-sm font-medium text-gray-700">USN</label>
                                                <input type="text" name="usn" id="usn" value="<?php echo htmlspecialchars($student['usn']); ?>" disabled class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-100">
                                            </div>

                                            <div class="col-span-6 sm:col-span-4">
                                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                                <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($student['phone']); ?>" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            </div>

                                            <div class="col-span-6 sm:col-span-4">
                                                <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                                                <input type="text" name="department" id="department" value="<?php echo htmlspecialchars($student['department']); ?>" disabled class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-100">
                                            </div>

                                            <div class="col-span-6 sm:col-span-4">
                                                <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
                                                <input type="text" name="semester" id="semester" value="<?php echo htmlspecialchars($student['semester']); ?>" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            </div>

                                            <div class="col-span-6">
                                                <label class="block text-sm font-medium text-gray-700">Profile Image</label>
                                                <div class="mt-1 flex items-center">
                                                    <?php if (!empty($student['image_path'])): ?>
                                                        <img class="h-12 w-12 rounded-full" src="<?php echo htmlspecialchars($student['image_path']); ?>" alt="Profile">
                                                        <span class="ml-2 text-sm text-gray-500">Current: <?php echo htmlspecialchars(basename($student['image_path'])); ?></span>
                                                    <?php else: ?>
                                                        <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                                            <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                            </svg>
                                                        </span>
                                                        <span class="ml-2 text-sm text-gray-500">No image uploaded</span>
                                                    <?php endif; ?>
                                                    <input type="file" name="image" class="ml-5">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                                <div class="md:grid md:grid-cols-3 md:gap-6">
                                    <div class="md:col-span-1">
                                        <h3 class="text-lg font-medium leading-6 text-gray-900">Change Password</h3>
                                        <p class="mt-1 text-sm text-gray-500">Leave blank to keep current password.</p>
                                    </div>
                                    <div class="mt-5 md:mt-0 md:col-span-2">
                                        <div class="grid grid-cols-6 gap-6">
                                            <div class="col-span-6 sm:col-span-3">
                                                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                                                <input type="password" name="current_password" id="current_password" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            </div>

                                            <div class="col-span-6 sm:col-span-3">
                                                <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                                                <input type="password" name="new_password" id="new_password" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            </div>

                                            <div class="col-span-6 sm:col-span-3">
                                                <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                                                <input type="password" name="confirm_password" id="confirm_password" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>