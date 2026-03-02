<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Exam Registration</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-2xl">
    <div class="text-center mb-6">
     
      <h2 class="mt-4 text-2xl font-bold text-gray-900">Student Registration</h2>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Form validation and processing
        $errors = [];

        // Collect data
        $usn = trim($_POST['usn']);
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $birth_date = $_POST['birth_date'];
        $gender = $_POST['gender'];
        $email = trim($_POST['email']);
        $department = $_POST['department'];
        $semester = $_POST['semester'];
        $phone = trim($_POST['phone']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validation
        if (empty($usn)) $errors[] = "USN is required";
        if (empty($first_name)) $errors[] = "First name is required";
        if (empty($last_name)) $errors[] = "Last name is required";
        if (empty($birth_date)) $errors[] = "Birth date is required";
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
        if (empty($phone)) $errors[] = "Phone number is required";
        if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters";
        if ($password !== $confirm_password) $errors[] = "Passwords do not match";

        // Check if USN or email already exists
        $stmt = $pdo->prepare("SELECT id FROM students WHERE usn = ? OR email = ?");
        $stmt->execute([$usn, $email]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "USN or Email already registered";
        }

        // Process image upload
        $image_path = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . '.' . $file_ext;
            $target_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $image_path = $target_path;
            } else {
                $errors[] = "Failed to upload image";
            }
        }

        if (empty($errors)) {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert student
            $stmt = $pdo->prepare("INSERT INTO students (usn, first_name, last_name, birth_date, gender, email, department, semester, phone, image_path, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$usn, $first_name, $last_name, $birth_date, $gender, $email, $department, $semester, $phone, $image_path, $hashed_password]);

            // Redirect to login with success message
            header("Location: index.php?registered=1");
            exit();
        }
    }
    ?>

    <?php if (!empty($errors)): ?>
        <div class="mb-4 rounded-md bg-red-50 p-4">
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

    <form class="space-y-4" method="POST" action="register.php" enctype="multipart/form-data">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
          <input id="first_name" name="first_name" type="text" required 
                 class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div>
          <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
          <input id="last_name" name="last_name" type="text" required 
                 class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
        </div>
      </div>

      <div>
        <label for="usn" class="block text-sm font-medium text-gray-700 mb-1">USN</label>
        <input id="usn" name="usn" type="text" required 
               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
      </div>

      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input id="email" name="email" type="email" required 
               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Birth Date</label>
          <input id="birth_date" name="birth_date" type="date" required 
                 class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
          <div class="flex space-x-4">
            <div class="flex items-center">
              <input id="male" name="gender" type="radio" value="Male" checked 
                     class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
              <label for="male" class="ml-2 block text-sm text-gray-700">Male</label>
            </div>
            <div class="flex items-center">
              <input id="female" name="gender" type="radio" value="Female" 
                     class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
              <label for="female" class="ml-2 block text-sm text-gray-700">Female</label>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
          <select id="department" name="department" required 
                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">Select Department</option>
            <option value="Computer Science">Computer Science</option>
            <option value="Electrical Engineering">Electrical Engineering</option>
            <option value="Mechanical Engineering">Mechanical Engineering</option>
            <option value="Civil Engineering">Civil Engineering</option>
            <option value="Electronics and Communication">Electronics and Communication</option>
          </select>
        </div>
        <div>
          <label for="semester" class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
          <select id="semester" name="semester" required 
                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">Select Semester</option>
            <?php for ($i = 1; $i <= 8; $i++): ?>
              <option value="<?php echo $i; ?>">Semester <?php echo $i; ?></option>
            <?php endfor; ?>
          </select>
        </div>
      </div>

      <div>
        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
        <input id="phone" name="phone" type="text" required 
               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
      </div>

      <div>
        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Profile Image</label>
        <input id="image" name="image" type="file" 
               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input id="password" name="password" type="password" required 
                 class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div>
          <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
          <input id="confirm_password" name="confirm_password" type="password" required 
                 class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
        </div>
      </div>

      <div class="flex items-center">
        <input id="terms" name="terms" type="checkbox" required 
               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
        <label for="terms" class="ml-2 block text-sm text-gray-700">
          I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-500">terms and conditions</a>
        </label>
      </div>

      <div>
        <button type="submit" 
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          <i class="fas fa-user-plus mr-2"></i> Register
        </button>
      </div>
    </form>

    <div class="mt-4 text-center text-sm">
      <p class="text-gray-600">Already have an account? 
        <a href="index.php" class="font-medium text-indigo-600 hover:text-indigo-500">Sign in</a>
      </p>
    </div>
  </div>
</body>
</html>