<?php
session_start();
require_once '../../config/database.php';

// Only allow super admins and admins
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['super_admin', 'admin'])) {
    header('Location: dashboard.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();

// Handle delete user
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $user_id = (int)$_GET['delete'];
    // Prevent deleting admins or super_admins
    $stmt = $db->prepare('SELECT role FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $role = $stmt->fetchColumn();
    if ($role === 'client') {
        $stmt = $db->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$user_id]);
        header('Location: user_management.php');
        exit();
    }
}

// Fetch all users except admins and super_admins
$stmt = $db->prepare('SELECT id, username, email, created_at FROM users WHERE role = "client"');
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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
                    <h1 class="text-xl font-bold text-brand-purple">User Management</h1>
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
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin'): ?>
                    <a href="admin_management.php" class="flex items-center text-white py-2 px-4 rounded hover:bg-brand-purple-light">Admin Management</a>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['super_admin', 'admin'])): ?>
                    <a href="user_management.php" class="flex items-center text-white py-2 px-4 rounded hover:bg-brand-purple-light bg-brand-purple-light">User Management</a>
                    <?php endif; ?>
                    <a href="../../includes/logout.php" class="flex items-center text-white py-2 px-4 rounded hover:bg-brand-purple-light mt-8">logout</a>
                </nav>
            </div>
        </div>
        <!-- Main Content -->
        <main class="flex-1 flex justify-center">
            <div class="w-full max-w-5xl px-4 py-8">
                <h2 class="text-2xl font-bold mb-4 text-brand-purple">Beheer Gebruikers</h2>
                <div>
                    <h3 class="text-lg font-semibold mb-2">Bestaande gebruikers</h3>
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
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($user['username']); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($user['created_at']); ?></td>
                                <td class="py-2 px-4 border-b">
                                    <a href="user_management.php?delete=<?php echo $user['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');">Verwijderen</a>
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