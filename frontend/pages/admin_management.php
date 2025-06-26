<?php
session_start();
require_once '../../config/database.php';

// Only allow super admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: dashboard.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();

// Handle create admin  
$create_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_admin_username'])) {
    $username = trim($_POST['new_admin_username']);
    $email = trim($_POST['new_admin_email']);
    $password = $_POST['new_admin_password'];
    if ($username && $email && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare('INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, "admin", NOW())');
        try {
            $stmt->execute([$username, $email, $hash]);
            header('Location: admin_management.php');
            exit();
        } catch (PDOException $e) {
            $create_error = 'Gebruikersnaam of e-mail bestaat al.';
        }
    } else {
        $create_error = 'Vul alle velden in aub.';
    }
}

// Handle delete admin
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $admin_id = (int)$_GET['delete'];
    // Prevent deleting super_admins or yourself
    $stmt = $db->prepare('SELECT role FROM users WHERE id = ?');
    $stmt->execute([$admin_id]);
    $role = $stmt->fetchColumn();
    if ($role === 'admin') {
        $stmt = $db->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$admin_id]);
        header('Location: admin_management.php');
        exit();
    }
}

// Fetch all admins
$stmt = $db->prepare('SELECT id, username, email, created_at FROM users WHERE role = "admin"');
$stmt->execute();
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management</title>
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
    <link rel="stylesheet" href="../../css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-full mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-brand-purple">Admin Management</h1>
                </div>
                <div class="flex items-center">
                    <span class="text-gray-700">Welkom, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </div>
            </div>
        </div>
    </nav>
    <div class="flex">
        <!-- Sidebar -->
        <div class="bg-brand-purple w-64 min-h-screen">
            <div class="p-4">
                <div class="mb-8">
                    <h2 class="text-white text-2xl font-bold mb-1">smart energy</h2>
                </div>
                <nav class="space-y-4">
                    <a href="dashboard.php" class="flex items-center text-white py-2 px-4 rounded hover:bg-brand-purple-light">dashboard</a>
                    <a href="instellingen.php" class="flex items-center text-white py-2 px-4 rounded hover:bg-brand-purple-light">Instellingen</a>
                    <a href="admin_management.php" class="flex items-center text-white py-2 px-4 rounded hover:bg-brand-purple-light bg-brand-purple-light">Admin Management</a>
                    <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['super_admin', 'admin'])): ?>
                    <a href="user_management.php" class="flex items-center text-white py-2 px-4 rounded hover:bg-brand-purple-light<?php if(basename($_SERVER['PHP_SELF']) == 'user_management.php') echo ' bg-brand-purple-light'; ?>">
                        <span>User Management</span>
                    </a>
                    <?php endif; ?>
                    <a href="../../includes/logout.php" class="flex items-center text-white py-2 px-4 rounded hover:bg-brand-purple-light mt-8">logout</a>
                </nav>
            </div>
        </div>
        <!-- Main Content -->
        <main class="flex-1 flex justify-center">
            <div class="w-full max-w-5xl px-4 py-8">
                <h2 class="text-2xl font-bold mb-4 text-brand-purple">Beheer Admin Accounts</h2>
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-2">Nieuwe admin aanmaken</h3>
                    <?php if ($create_error): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-2"><?php echo $create_error; ?></div>
                    <?php endif; ?>
                    <form method="POST" class="flex flex-col md:flex-row gap-2 items-center">
                        <input type="text" name="new_admin_username" placeholder="Gebruikersnaam" class="border rounded px-2 py-1" required>
                        <input type="email" name="new_admin_email" placeholder="E-mail" class="border rounded px-2 py-1" required>
                        <input type="password" name="new_admin_password" placeholder="Wachtwoord" class="border rounded px-2 py-1" required>
                        <button type="submit" class="bg-brand-purple hover:bg-brand-purple-light text-white px-4 py-2 rounded">Aanmaken</button>
                    </form>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-2">Bestaande admins</h3>
                    <table class="min-w-full bg-white rounded shadow">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">Gebruikersnaam</th>
                                <th class="py-2 px-4 border-b">E-mail</th>
                                <th class="py-2 px-4 border-b">Aangemaakt op</th>
                                <th class="py-2 px-4 border-b">Actie</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($admins as $admin): ?>
                            <tr>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($admin['username']); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($admin['email']); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($admin['created_at']); ?></td>
                                <td class="py-2 px-4 border-b">
                                    <a href="admin_management.php?delete=<?php echo $admin['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Weet je zeker dat je deze admin wilt verwijderen?');">Verwijderen</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html> 