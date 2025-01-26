<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit();
}

$user = $_SESSION['user'];
require '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $nim = $_POST['nim'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, nim, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $nim, $password, $role);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Gagal menambah pengguna!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-green-50">
    <!-- Sidebar -->
    <?php include '../../components/sidebarAdmin.php'; ?>

    <!-- Main Content -->
    <main class="flex-grow p-6 ml-72">
        <div class="bg-white rounded-lg shadow-lg border border-green-100 p-8">
            <h1 class="text-3xl font-bold mb-6 text-green-800">Tambah Pengguna</h1>

            <?php if(isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-green-700 font-semibold mb-2">Nama</label>
                    <input 
                        type="text" 
                        name="name" 
                        required 
                        class="w-full px-4 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all"
                    >
                </div>

                <div>
                    <label class="block text-green-700 font-semibold mb-2">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        required 
                        class="w-full px-4 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all"
                    >
                </div>

                <div>
                    <label class="block text-green-700 font-semibold mb-2">NIM</label>
                    <input 
                        type="text" 
                        name="nim" 
                        required 
                        class="w-full px-4 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all"
                    >
                </div>

                <div>
                    <label class="block text-green-700 font-semibold mb-2">Password</label>
                    <input 
                        type="password" 
                        name="password" 
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
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="admin">Admin</option>
                        <option value="superadmin">Superadmin</option>
                    </select>
                </div>

                <div>
                    <button 
                        type="submit" 
                        class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-all shadow-md"
                    >
                        Tambah Pengguna
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>