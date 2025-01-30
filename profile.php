<?php
// profile.php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    if (!empty($_FILES['profile_image']['name'])) {
        $image_name = time() . '_' . $_FILES['profile_image']['name'];
        $target_dir = 'uploads/profiles/';
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            $user['profile_image'] = $image_name;
        }
    }

    $update_query = "UPDATE users SET name = ?, email = ?, profile_image = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('sssi', $name, $email, $user['profile_image'], $user_id);
    $update_stmt->execute();

    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['email'] = $email;

    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <?php include 'components/navigationApp.php'; ?>
    <div class="max-w-3xl w-full bg-white shadow-lg rounded-lg px-6 py-6 md:py-16 lg:py-32 space-y-8">
        <!-- Profile Section -->
        <div class="text-center">
            <div class="relative inline-block">
                <img src="uploads/profiles/<?php echo htmlspecialchars($users['img_prfl'] ?: 'default.png'); ?>" 
                     alt="Profile Image" 
                     class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-gray-300">
                <label for="profile_image" 
                       class="absolute bottom-0 right-0 bg-green-500 text-white rounded-full p-2 cursor-pointer">
                    📷
                </label>
                <input type="file" id="profile_image" name="profile_image" class="hidden" onchange="previewImage(event)">
            </div>

            <h2 class="text-xl font-bold mt-4 text-gray-700">@<?php echo htmlspecialchars($users['name']); ?></h2>
            <p class="text-gray-500">Email: <?php echo htmlspecialchars($users['email']); ?></p>
        </div>

        <!-- Form Section -->
        <form method="POST" enctype="multipart/form-data" class="mt-8 space-y-4">
            <div class="flex flex-col">
                <label for="name" class="text-sm font-medium text-gray-600">Nama</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($users['name']); ?>" 
                       class="border border-gray-300 rounded-lg p-2 mt-1 focus:ring-green-500 focus:border-green-500">
            </div>
            <div class="flex flex-col">
                <label for="email" class="text-sm font-medium text-gray-600">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($users['email']); ?>" 
                       class="border border-gray-300 rounded-lg p-2 mt-1 focus:ring-green-500 focus:border-green-500">
            </div>
            <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition">Update Profil</button>
        </form>

        <!-- Preview Section -->
        <div id="previewContainer" class="hidden mt-8 text-center">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Pratinjau Gambar</h3>
            <img id="previewImage" src="#" alt="Preview" class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-gray-300">
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const previewContainer = document.getElementById('previewContainer');
                const previewImage = document.getElementById('previewImage');

                previewImage.src = reader.result;
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
