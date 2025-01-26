<?php
session_start();

// Fungsi untuk mengecek apakah pengguna sudah login
function isLoggedIn()
{
    return isset($_SESSION['user']);
}

// Fungsi untuk mendapatkan role pengguna
function getUserRole()
{
    return $_SESSION['user']['role'] ?? null;
}

?>

<nav class="bg-gray-800 text-white">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <div>
                <a href="index.php" class="text-2xl font-bold">Ruang NU</a>
            </div>

            <!-- Menu -->
            <div class="hidden md:flex space-x-6">
                <a href="index.php" class="hover:text-blue-300">Beranda</a>
                <?php if (isLoggedIn()) : ?>
                    <a href="pinjam-ruang.php" class="hover:text-blue-300">Pinjam Ruang</a>
                    <a href="aktivitas.php" class="hover:text-blue-300">Status Aktivitas</a>
                    <a href="log_aktivitas.php" class="hover:text-blue-300">Log Aktivitas</a>
                <?php endif; ?>
            </div>

            <!-- Login/Logout -->
            <div>
                <?php if (isLoggedIn()) : ?>
                    <span class="hidden md:inline">Halo, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>
                    <a href="logout.php" class="ml-4 px-4 py-2 bg-red-500 rounded-lg hover:bg-red-600">Logout</a>
                <?php else : ?>
                    <a href="login.php" class="px-4 py-2 bg-blue-500 rounded-lg hover:bg-blue-600">Login</a>
                    <a href="register.php" class="ml-2 px-4 py-2 bg-green-500 rounded-lg hover:bg-green-600">Register</a>
                <?php endif; ?>
            </div>

            <!-- Hamburger Menu -->
            <div class="md:hidden">
                <button id="menu-btn" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden flex-col space-y-2 mt-4">
            <a href="index.php" class="hover:text-blue-300">Beranda</a>
            <?php if (isLoggedIn()) : ?>
                <a href="pinjam-ruang.php" class="hover:text-blue-300">Pinjam Ruang</a>
                <a href="aktivitas.php" class="hover:text-blue-300">Status Aktivitas</a>
                <a href="log_aktivitas.php" class="hover:text-blue-300">Log Aktivitas</a>
            <?php endif; ?>
            <?php if (isLoggedIn()) : ?>
                <a href="logout.php" class="hover:text-red-400">Logout</a>
            <?php else : ?>
                <a href="login.php" class="hover:text-blue-400">Login</a>
                <a href="register.php" class="hover:text-green-400">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
    const menuBtn = document.getElementById('menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    menuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>
