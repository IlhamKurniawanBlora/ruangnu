<?php
$user = $_SESSION['user'];

// Cek apakah user sudah login
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'superadmin')) {
    header("Location: ../../login.php");
    exit();
}

?>

<div class="fixed top-0 left-0 h-screen w-72 bg-gradient-to-b from-green-800 to-green-900 text-white flex flex-col shadow-2xl transition-all duration-300 ease-in-out">
    <!-- Logo Section with Elegant Design -->
    <div class="p-6 border-b border-green-700 text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-green-700 opacity-20 transform -skew-x-12"></div>
        <h1 class="text-2xl font-bold text-white relative z-10 tracking-wider">Ruang NU</h1>
        <p class="text-sm text-green-200 mt-1 relative z-10">
            <?php echo htmlspecialchars($user['name']); ?>
        </p>
        <p class="text-xs text-green-300 uppercase tracking-wider relative z-10">
            <?php echo htmlspecialchars($user['role']); ?>
        </p>
    </div>

    <!-- Navigation Menu with Hover Effects -->
    <nav class="flex-grow overflow-y-auto scrollbar-thin scrollbar-thumb-green-600 scrollbar-track-green-800">
        <ul class="mt-4">
            <!-- Common Link -->
            <li class="group">
                <a href="../dashboard" class="block px-6 py-3 hover:bg-green-700 transition-all duration-300 ease-in-out transform hover:translate-x-2 hover:border-l-4 border-green-400">
                    <i data-feather="home" class="mr-3 inline-block group-hover:text-green-300 transition-colors"></i>
                    <span class="group-hover:text-white">Home</span>
                </a>
            </li>

            <?php if ($user['role'] === 'admin' || $user['role'] === 'superadmin') : ?>
                <!-- Links for Admin/Superadmin -->
                <li class="group">
                    <a href="../booking/index.php" class="block px-6 py-3 hover:bg-green-700 transition-all duration-300 ease-in-out transform hover:translate-x-2 hover:border-l-4 border-green-400">
                        <i data-feather="list" class="mr-3 inline-block group-hover:text-green-300 transition-colors"></i>
                        <span class="group-hover:text-white">List Peminjaman</span>
                    </a>
                </li>
                <li class="group">
                    <a href="../ruangan/tambah.php" class="block px-6 py-3 hover:bg-green-700 transition-all duration-300 ease-in-out transform hover:translate-x-2 hover:border-l-4 border-green-400">
                        <i data-feather="plus-square" class="mr-3 inline-block group-hover:text-green-300 transition-colors"></i>
                        <span class="group-hover:text-white">Tambah Ruangan</span>
                    </a>
                </li>
                <li class="group">
                    <a href="../ruangan/index.php" class="block px-6 py-3 hover:bg-green-700 transition-all duration-300 ease-in-out transform hover:translate-x-2 hover:border-l-4 border-green-400">
                        <i data-feather="settings" class="mr-3 inline-block group-hover:text-green-300 transition-colors"></i>
                        <span class="group-hover:text-white">Kelola Ruangan</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($user['role'] === 'superadmin') : ?>
                <!-- Links for Superadmin -->
                <li class="group">
                    <a href="../pengguna/tambah.php" class="block px-6 py-3 hover:bg-green-700 transition-all duration-300 ease-in-out transform hover:translate-x-2 hover:border-l-4 border-green-400">
                        <i data-feather="user-plus" class="mr-3 inline-block group-hover:text-green-300 transition-colors"></i>
                        <span class="group-hover:text-white">Tambah Pengguna</span>
                    </a>
                </li>
                <li class="group">
                    <a href="../pengguna/index.php" class="block px-6 py-3 hover:bg-green-700 transition-all duration-300 ease-in-out transform hover:translate-x-2 hover:border-l-4 border-green-400">
                        <i data-feather="users" class="mr-3 inline-block group-hover:text-green-300 transition-colors"></i>
                        <span class="group-hover:text-white">Kelola Pengguna</span>
                    </a>
                </li>
            <?php endif; ?>

            <li class="group">
                <a href="../profile" class="block px-6 py-3 hover:bg-green-700 transition-all duration-300 ease-in-out transform hover:translate-x-2 hover:border-l-4 border-green-400">
                    <i data-feather="user" class="mr-3 inline-block group-hover:text-green-300 transition-colors"></i>
                    <span class="group-hover:text-white">Kelola Profile</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Logout Button with Hover Effects -->
    <div class="p-4 border-t border-green-700">
        <a href="../../logout.php" class="block px-6 py-3 bg-red-600 text-center rounded-lg hover:bg-red-700 transition-all duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg">
            Logout
        </a>
    </div>
</div>

<script src="https://unpkg.com/feather-icons"></script>
<script>
    feather.replace();
</script>

<!-- Add some custom CSS for scrollbar and smooth interactions -->
<style>
    /* Custom Scrollbar for WebKit browsers */
    .scrollbar-thin::-webkit-scrollbar {
        width: 6px;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb {
        background-color: #38a169; /* Green color */
        border-radius: 3px;
    }
    .scrollbar-thin::-webkit-scrollbar-track {
        background-color: #2f855a; /* Darker green */
    }
</style>