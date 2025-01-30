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
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <?php include '../../components/sidebarAdmin.php'; ?>

    <!-- Main Content -->
    <main class="flex-grow p-6 ml-72">
        <div class="max-w-3xl mx-auto">
            <!-- Profile Header -->
            <div class="bg-white rounded-lg shadow-sm mb-6">
    <div class="relative h-48 bg-gradient-to-r from-green-400 to-blue-500 rounded-t-lg">
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center text-white">
                <h2 class="text-3xl font-bold mb-2">Welcome back,</h2>
                <p class="text-xl"><?php echo htmlspecialchars($profile['name']); ?>!</p>
            </div>
        </div>
        <div class="absolute -bottom-16 left-8">
            <div class="w-32 h-32 bg-white rounded-full border-4 border-white shadow-md flex items-center justify-center overflow-hidden">
                <img class="w-full h-full object-cover" src="../../uploads/profiles/<?php echo htmlspecialchars($profile['img_prfl']); ?>" alt="profile user">
            </div>
        </div>
    </div>
    <div class="pt-20 pb-6 px-8">
        <h1 class="text-2xl font-bold text-gray-800">
            <?php echo htmlspecialchars($profile['name']); ?>
        </h1>
        <p class="text-gray-800 font-bold"><?php echo htmlspecialchars($profile['role']); ?></p>
        <p class="text-gray-500">@<?php echo htmlspecialchars($profile['nim']); ?></p>
    </div>
</div>

            <!-- Alert Messages -->
            <?php if(isset($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <div class="flex items-center">
                        <i data-feather="alert-circle" class="w-5 h-5 mr-2"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(isset($success)): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                    <div class="flex items-center">
                        <i data-feather="check-circle" class="w-5 h-5 mr-2"></i>
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Edit Profile Form -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center mb-6">
                    <i data-feather="edit-3" class="w-5 h-5 mr-2 text-gray-500"></i>
                    <h2 class="text-xl font-semibold text-gray-800">Edit Profile</h2>
                </div>

                <form method="POST" class="space-y-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <div class="relative">
                                <i data-feather="user" class="w-5 h-5 text-gray-400 absolute left-3 top-3"></i>
                                <input 
                                    type="text" 
                                    name="name" 
                                    value="<?php echo htmlspecialchars($profile['name']); ?>"
                                    required 
                                    class="pl-10 w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <div class="relative">
                                <i data-feather="mail" class="w-5 h-5 text-gray-400 absolute left-3 top-3"></i>
                                <input 
                                    type="email" 
                                    name="email" 
                                    value="<?php echo htmlspecialchars($profile['email']); ?>"
                                    required 
                                    class="pl-10 w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Username (NIM)</label>
                            <div class="relative">
                                <i data-feather="at-sign" class="w-5 h-5 text-gray-400 absolute left-3 top-3"></i>
                                <input 
                                    type="text" 
                                    name="nim" 
                                    value="<?php echo htmlspecialchars($profile['nim']); ?>"
                                    required 
                                    class="pl-10 w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                >
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                            <div class="relative">
                                <i data-feather="lock" class="w-5 h-5 text-gray-400 absolute left-3 top-3"></i>
                                <input 
                                    type="password" 
                                    name="current_password" 
                                    class="pl-10 w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Enter current password to change"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <div class="relative">
                                <i data-feather="key" class="w-5 h-5 text-gray-400 absolute left-3 top-3"></i>
                                <input 
                                    type="password" 
                                    name="new_password" 
                                    class="pl-10 w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Leave blank to keep current password"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="pt-6">
                        <button 
                            type="submit" 
                            class="w-full bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-all shadow-sm flex items-center justify-center"
                        >
                            <i data-feather="save" class="w-5 h-5 mr-2"></i>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        feather.replace();
    </script>
</body>
</html>