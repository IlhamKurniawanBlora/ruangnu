<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit();
}

$user = $_SESSION['user'];
require '../../config/db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'superadmin')) {
    header("Location: ../login.php");
    exit();
}

// Fetch data pengguna dari database
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="bg-green-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <?php include '../../components/sidebarAdmin.php'; ?>

        <!-- Main Content Area -->
        <div class="flex-grow overflow-auto ml-72 p-6">
            <!-- Top Navigation -->
            <nav class="bg-white shadow-md p-4 flex justify-between items-center rounded-lg mb-6">
                <h1 class="text-2xl font-bold text-green-800">Manajemen Pengguna</h1>
                <div class="flex items-center space-x-4">
                    <a href="tambah.php" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 flex items-center transition-all">
                        <i data-feather="plus" class="mr-2"></i>
                        Tambah Pengguna
                    </a>
                </div>
            </nav>

            <!-- Content Wrapper -->
            <div>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-green-100">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-green-50 border-b border-green-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-green-600 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-green-600 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-green-600 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-green-600 uppercase tracking-wider">NIM</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-green-600 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-green-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-green-100">
                                <?php while ($user_row = $result->fetch_assoc()) : ?>
                                    <tr class="hover:bg-green-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-green-800"><?php echo $user_row['id']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-green-800"><?php echo htmlspecialchars($user_row['name']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-green-800"><?php echo htmlspecialchars($user_row['email']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-green-800"><?php echo htmlspecialchars($user_row['nim']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                                <?php 
                                                    echo $user_row['role'] === 'admin' ? 'bg-green-100 text-green-800' : 
                                                    ($user_row['role'] === 'superadmin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800');
                                                ?>">
                                                <?php echo htmlspecialchars($user_row['role']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="edit.php?id=<?php echo $user_row['id']; ?>" class="text-green-600 hover:text-green-900 mr-3 transition-colors">
                                                <i data-feather="edit" class="w-4 h-4 inline-block"></i>
                                            </a>
                                            <a href="hapus.php?id=<?php echo $user_row['id']; ?>" class="text-red-600 hover:text-red-900 transition-colors" 
                                               onclick="return confirm('Hapus pengguna ini?')">
                                                <i data-feather="trash-2" class="w-4 h-4 inline-block"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Feather Icons
        feather.replace();
    </script>
</body>
</html>