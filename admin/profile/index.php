<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit();
}

$user = $_SESSION['user'];
require '../../config/db.php';

// Fetch complete user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $nim = $_POST['nim'];
    
    // Check if password change is requested
    if (!empty($_POST['new_password'])) {
        // Verify current password first
        if (password_verify($_POST['current_password'], $profile['password'])) {
            $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, nim = ?, password = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $name, $email, $nim, $new_password, $user['id']);
        } else {
            $error = "Password lama tidak sesuai!";
        }
    } else {
        // Update without changing password
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, nim = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $nim, $user['id']);
    }

    if ($stmt->execute()) {
        $success = "Profil berhasil diperbarui!";
        // Refresh user data in session
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['nim'] = $nim;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="flex bg-green-50">
    <!-- Sidebar -->
    <?php include '../../components/sidebarAdmin.php'; ?>

    <!-- Main Content -->
    <main class="flex-grow p-6 ml-72">
        <div class="bg-white rounded-lg shadow-lg border border-green-100 p-8">
            <h1 class="text-3xl font-bold mb-6 text-green-800">Kelola Profil</h1>

            <?php if(isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if(isset($success)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-green-700 font-semibold mb-2">Nama</label>
                    <input 
                        type="text" 
                        name="name" 
                        value="<?php echo htmlspecialchars($profile['name']); ?>"
                        required 
                        class="w-full px-4 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all"
                    >
                </div>

                <div>
                    <label class="block text-green-700 font-semibold mb-2">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        value="<?php echo htmlspecialchars($profile['email']); ?>"
                        required 
                        class="w-full px-4 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all"
                    >
                </div>

                <div>
                    <label class="block text-green-700 font-semibold mb-2">NIM</label>
                    <input 
                        type="text" 
                        name="nim" 
                        value="<?php echo htmlspecialchars($profile['nim']); ?>"
                        required 
                        class="w-full px-4 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all"
                    >
                </div>

                <div>
                    <label class="block text-green-700 font-semibold mb-2">Password Lama (Opsional)</label>
                    <input 
                        type="password" 
                        name="current_password" 
                        class="w-full px-4 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all"
                        placeholder="Masukkan password lama jika ingin mengganti password"
                    >
                </div>

                <div>
                    <label class="block text-green-700 font-semibold mb-2">Password Baru (Opsional)</label>
                    <input 
                        type="password" 
                        name="new_password" 
                        class="w-full px-4 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all"
                        placeholder="Kosongkan jika tidak ingin mengganti password"
                    >
                </div>

                <div>
                    <button 
                        type="submit" 
                        class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-all shadow-md"
                    >
                        Perbarui Profil
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        feather.replace();
    </script>
</body>
</html>