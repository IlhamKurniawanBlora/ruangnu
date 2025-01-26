<?php
session_start();
require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Mencari pengguna berdasarkan email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verifikasi password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];

        // Redirect berdasarkan role
        if ($user['role'] === 'mahasiswa') {
            header("Location: index.php");
        } elseif ($user['role'] === 'admin' || $user['role'] === 'superadmin') {
            header("Location: admin/dashboard");
        }
        exit();
    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #4ade80, #22c55e);
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
                <i data-feather="log-in" class="text-white w-10 h-10"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Selamat Datang</h1>
            <p class="text-sm text-gray-500">Masuk untuk melanjutkan</p>
        </div>

        <?php if (isset($error)) : ?>
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded-lg">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" required 
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50 transition-transform transform focus:scale-105">
                <p id="emailError" class="text-red-500 text-sm mt-1 hidden">Masukkan email yang valid</p>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required 
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50 transition-transform transform focus:scale-105">
                <p id="passwordError" class="text-red-500 text-sm mt-1 hidden">Password tidak boleh kosong</p>
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-gradient-to-br from-green-400 to-green-600 text-white rounded-lg hover:bg-green-700 transition-transform transform hover:scale-105 shadow-md">
                Login
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">Belum punya akun?</p>
            <a href="register.php" class="mt-2 inline-block px-4 py-2 text-green-600 font-semibold bg-green-100 rounded-lg hover:bg-green-200 transition-transform transform hover:scale-105">
                Daftar Sekarang
            </a>
        </div>
    </div>

    <script>
        feather.replace();

        document.querySelector("form").addEventListener("submit", (e) => {
            const email = document.getElementById("email");
            const password = document.getElementById("password");
            const emailError = document.getElementById("emailError");
            const passwordError = document.getElementById("passwordError");

            let isValid = true;

            if (!email.value.includes("@") || email.value.trim() === "") {
                emailError.classList.remove("hidden");
                isValid = false;
            } else {
                emailError.classList.add("hidden");
            }

            if (password.value.trim() === "") {
                passwordError.classList.remove("hidden");
                isValid = false;
            } else {
                passwordError.classList.add("hidden");
            }

            if (!isValid) e.preventDefault();
        });
    </script>
</body>
</html>

