<?php
require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $nim = $_POST['nim'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, nim, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $nim, $password);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Registrasi gagal. Coba lagi!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #86efac, #22c55e);
        }
        input:focus {
            outline: none;
            border-color: #22c55e;
            box-shadow: 0 0 8px rgba(34, 197, 94, 0.5);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-xl p-8 w-96">
        <div class="flex flex-col items-center mb-6">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center mb-4">
                <i data-feather="user-plus" class="text-white w-10 h-10"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Buat Akun</h1>
            <p class="text-sm text-gray-500">Isi data di bawah untuk mendaftar</p>
        </div>

        <?php if (isset($error)) : ?>
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded-lg">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" id="name" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50 transition-transform transform focus:scale-105">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50 transition-transform transform focus:scale-105">
            </div>
            <div class="mb-4">
                <label for="nim" class="block text-sm font-medium text-gray-700">NIM</label>
                <input type="text" name="nim" id="nim" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50 transition-transform transform focus:scale-105">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50 transition-transform transform focus:scale-105">
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-gradient-to-br from-green-400 to-green-600 text-white rounded-lg hover:bg-green-700 transition-transform transform hover:scale-105 shadow-md">
                Register
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">Sudah punya akun?</p>
            <a href="login.php" class="mt-2 inline-block px-4 py-2 text-green-600 font-semibold bg-green-100 rounded-lg hover:bg-green-200 transition-transform transform hover:scale-105">
                Login Sekarang
            </a>
        </div>
    </div>

    <script>
        feather.replace();

        document.querySelector("form").addEventListener("submit", (e) => {
            const name = document.getElementById("name");
            const email = document.getElementById("email");
            const nim = document.getElementById("nim");
            const password = document.getElementById("password");

            if (name.value.trim() === "" || email.value.trim() === "" || !email.value.includes("@") || nim.value.trim() === "" || password.value.trim() === "") {
                alert("Harap isi semua data dengan benar.");
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
