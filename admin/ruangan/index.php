<?php
session_start();

// Validasi akses pengguna
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'superadmin')) {
    header("Location: ../../login.php");
    exit();
}

require '../../config/db.php';

// Fetch rooms
$query = "SELECT * FROM rooms ORDER BY id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Ruangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="flex bg-green-50">
    <!-- Sidebar -->
    <?php include '../../components/sidebarAdmin.php'; ?>

    <!-- Main Content -->
    <main class="flex-grow p-6 ml-72">
        <div class="bg-white rounded-lg shadow-lg border border-green-100 p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-green-800">Manajemen Ruangan</h1>
                <a href="tambah.php" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 flex items-center">
                    <i data-feather="plus" class="mr-2"></i>
                    Tambah Ruangan
                </a>
            </div>

            <?php if ($result->num_rows === 0): ?>
                <div class="text-center py-8 text-gray-500">
                    Belum ada ruangan yang ditambahkan.
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-green-50 border-b border-green-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-600 uppercase tracking-wider">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-600 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-600 uppercase tracking-wider">Kapasitas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-600 uppercase tracking-wider">Fasilitas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-green-100">
                            <?php while ($room = $result->fetch_assoc()): ?>
                                <tr class="hover:bg-green-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-green-800"><?php echo htmlspecialchars($room['id']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-green-800"><?php echo htmlspecialchars($room['name']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-green-800"><?php echo htmlspecialchars($room['capacity']); ?> orang</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-green-800"><?php echo htmlspecialchars($room['location']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                                            <?php echo $room['is_available'] === '1' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                            <?php echo htmlspecialchars($room['is_available']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="edit.php?id=<?php echo $room['id']; ?>" class="text-green-600 hover:text-green-900 mr-3">
                                            <i data-feather="edit" class="w-4 h-4 inline-block"></i>
                                        </a>
                                        <button class="text-red-600 hover:text-red-900" onclick="confirmDelete(<?php echo $room['id']; ?>)">
                                            <i data-feather="trash-2" class="w-4 h-4 inline-block"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        feather.replace();

        // Konfirmasi hapus dengan SweetAlert
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Ruangan?',
                text: 'Apakah Anda yakin ingin menghapus ruangan ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `hapus.php?id=${id}`;
                }
            });
        }
    </script>
</body>
</html>
