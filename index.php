<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Exam Registration</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
    <div class="text-center mb-6">
     
      <h2 class="mt-4 text-2xl font-bold text-gray-900">Student Login</h2>
    </div>

    <?php if (isset($_GET['error'])): ?>
      <div class="mb-4 rounded-md bg-red-50 p-4">
        <div class="flex">
          <div class="flex-shrink-0">
            <i class="fas fa-exclamation-circle h-5 w-5 text-red-400"></i>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-red-800">Invalid USN or password</p>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['registered'])): ?>
      <div class="mb-4 rounded-md bg-green-50 p-4">
        <div class="flex">
          <div class="flex-shrink-0">
            <i class="fas fa-check-circle h-5 w-5 text-green-400"></i>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-green-800">Registration successful! Please login.</p>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <form action="authenticate.php" method="POST" class="space-y-4">
      <input type="hidden" name="action" value="login">
      <div>
        <label for="usn" class="block text-sm font-medium text-gray-700 mb-1">USN</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-id-card text-gray-400"></i>
          </div>
          <input id="usn" name="usn" type="text" required 
                 class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                 placeholder="University Serial Number">
        </div>
      </div>
      
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-lock text-gray-400"></i>
          </div>
          <input id="password" name="password" type="password" required 
                 class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                 placeholder="Password">
        </div>
      </div>

      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <input id="remember-me" name="remember-me" type="checkbox" 
                 class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
          <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
        </div>

        <div class="text-sm">
          <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Forgot password?</a>
        </div>
      </div>

      <div>
        <button type="submit" 
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          <i class="fas fa-sign-in-alt mr-2"></i> Sign in
        </button>
      </div>
    </form>

    <div class="mt-4 text-center text-sm">
      <p class="text-gray-600">Don't have an account? 
        <a href="register.php" class="font-medium text-indigo-600 hover:text-indigo-500">Register here</a>
      </p>
    </div>
  </div>
</body>
</html>