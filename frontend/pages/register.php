<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // Check if username or email already exists
        $query = "SELECT id FROM users WHERE username = :username OR email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $error = 'Username or email already exists.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = "INSERT INTO users (username, email, password, role, created_at) VALUES (:username, :email, :password, 'client', NOW())";
            $stmt = $db->prepare($insert);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            if ($stmt->execute()) {
                header('Location: login.php?registered=1');
                exit();
            } else {
                $errorInfo = $stmt->errorInfo();
                $error = 'Registration failed: ' . $errorInfo[2];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Smart Energy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-purple': '#4B2E83',
                        'brand-purple-light': '#5d3a9f'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-bold mb-6 text-center text-brand-purple">Register</h2>
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" name="username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" name="password" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">Confirm Password</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="confirm_password" type="password" name="confirm_password" required>
                </div>
                <div class="flex items-center justify-between">
                    <button class="bg-brand-purple hover:bg-brand-purple-light text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full" type="submit">Register</button>
                </div>
            </form>
            <div class="text-center mt-4">
                <a href="login.php" class="text-brand-purple hover:underline">Already have an account? Login</a>
            </div>
        </div>
    </div>
</body>
</html> 