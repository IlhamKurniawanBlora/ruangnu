<?php
session_start();

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'superadmin')) {
    header("Location: ../../login.php");
    exit();
}

require '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $capacity = $_POST['capacity'];
    $is_available = isset($_POST['is_available']) ? 1 : 0;

    // Handle image upload
    if (isset($_FILES['img_room']) && $_FILES['img_room']['error'] === UPLOAD_ERR_OK) {
        $img_name = basename($_FILES['img_room']['name']);
        $img_path = '../../uploads/' . $img_name;

        if (move_uploaded_file($_FILES['img_room']['tmp_name'], $img_path)) {
            $stmt = $conn->prepare("INSERT INTO rooms (name, location, capacity, img_room, is_available) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssisi", $name, $location, $capacity, $img_name, $is_available);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                $error = "Gagal menambah ruangan!";
            }
        } else {
            $error = "Gagal mengunggah gambar!";
        }
    } else {
        $error = "Harap unggah gambar!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Ruangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="flex bg-green-50">
    <!-- Sidebar -->
    <?php include '../../components/sidebarAdmin.php'; ?>

    <!-- Main Content -->
    <main class="flex-grow p-6 ml-72">
        <div class="bg-white rounded-lg shadow-lg border border-green-100 p-8">
            <h1 class="text-3xl font-bold text-green-800 mb-6">Tambah Ruangan</h1>

            <?php if(isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="block text-green-700 font-bold mb-2">Nama Ruangan</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-green-700 font-bold mb-2">Lokasi Ruangan</label>
                    <input type="text" name="location" required class="w-full px-3 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-green-700 font-bold mb-2">Kapasitas</label>
                    <input type="number" name="capacity" required class="w-full px-3 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-green-700 font-bold mb-2">Unggah Gambar</label>
                    <input type="file" name="img_room" id="img_room" accept="image/*" required class="w-full px-3 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <div id="img_preview" class="mt-4">
                        <img id="preview_image" src="#" alt="Preview Gambar" class="hidden w-32 h-32 object-cover border border-green-300 rounded-lg">
                    </div>
                </div>
                <div>
                    <label class="flex items-center text-green-700 font-bold">
                        <input type="checkbox" name="is_available" class="mr-2 text-green-500 focus:ring-green-500" value="1">
                        Ruangan Tersedia
                    </label>
                </div>
                <div>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 flex items-center">
                        <i data-feather="plus" class="mr-2"></i>
                        Tambah Ruangan
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        feather.replace();

        const imgInput = document.getElementById('img_room');
        const previewImage = document.getElementById('preview_image');
        const imgPreview = document.getElementById('img_preview');

        imgInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
