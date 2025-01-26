<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit();
}

require '../../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $nim = $_POST['nim'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, nim = ?, role = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name, $email, $nim, $role, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Gagal memperbarui pengguna!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-green-50">
    <!-- Sidebar -->
    <?php include '../../components/sidebarAdmin.php'; ?>

    <!-- Main Content -->
    <main class="flex-grow p-6 ml-72">
        <div class="bg-white rounded-lg shadow-lg border border-green-100 p-8">
            <h1 class="text-3xl font-bold mb-6 text-green-800">Edit Pengguna</h1>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-green-700 font-semibold mb-2">Nama</label>
                    <input 
                        type="text" 
                        name="name" 
                        value="<?php echo htmlspecialchars($users['name']); ?>" 
                        required 
                        class="w-full px-4 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all"
                    >
                </div>

                <div>
                    <label class="block text-green-700 font-semibold mb-2">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        value="<?php echo htmlspecialchars($users['email']); ?>" 
                        required 
                        class="w-full px-4 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all"
                    >
                </div>

                <div>
                    <label class="block text-green-700 font-semibold mb-2">NIM</label>
                    <input 
                        type="number" 
                        name="nim" 
                        value="<?php echo htmlspecialchars($users['nim']); ?>" 
                        required 
                        class="w-full px-4 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all"
                    >
                </div>

                <div>
                    <label class="block text-green-700 font-semibold mb-2">Role</label>
                    <select 
                        name="role" 
                        required 
                        class="w-full px-4 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all"
                    >
                        <option value="mahasiswa" <?php echo $users['role'] === 'mahasiswa' ? 'selected' : ''; ?>>Mahasiswa</option>
                        <option value="admin" <?php echo $users['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="superadmin" <?php echo $users['role'] === 'superadmin' ? 'selected' : ''; ?>>Superadmin</option>
                    </select>
                </div>

                <div>
                    <button 
                        type="submit" 
                        class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-all shadow-md"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>