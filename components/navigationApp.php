<?php
// components/navigationApp.php
$isLoggedIn = isset($_SESSION['user']);
$userRole = $isLoggedIn ? $_SESSION['user']['role'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RuangNU - Sistem Peminjaman Ruang</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav id="mainNavigation" class="fixed top-6 left-0 right-0 z-50">
        <div class="max-w-5xl mx-auto px-4">
            <div class="glassy-nav rounded-2xl py-4 px-6 flex justify-between items-center">
                <div class="flex items-center">
                    <a href="index.php" class="flex items-center space-x-2">
                        <img src="assets/images/logo.jpg" alt="RuangNU Logo" class="h-10 mr-4 animate-pulse-hover">
                        <h1 class="text-2xl font-bold text-white">RuangNU</h1>
                    </a>
                </div>
                
                <div class="hidden md:flex space-x-6">
                    <a href="#home" class="text-white" title="Beranda">
                        <i class="ri-home-line text-2xl hover:text-green-500"></i>
                    </a>
                    <a href="#about" class="text-white" title="Tentang">
                        <i class="ri-information-line text-2xl hover:text-green-500"></i>
                    </a>
                    <a href="#features" class="text-white" title="Fitur">
                        <i class="ri-grid-line text-2xl hover:text-green-500"></i>
                    </a>
                    <a href="#contact" class="text-white" title="Kontak">
                        <i class="ri-contacts-line text-2xl hover:text-green-500"></i>
                    </a>
                </div>

                
                <div class="hidden md:flex items-center space-x-4">
                    <?php if ($isLoggedIn): ?>
                        <a href="booking-room.php" class="px-4 py-2 bg-white text-green-500 rounded-lg hover:bg-green-100">
                            <i class="ri-file-list-line mr-2"></i>Pinjam Ruang
                        </a>
                        <a href="profile.php" class="px-4 py-2 bg-white text-green-500 rounded-lg hover:bg-green-100">
                            <i class="ri-user-line mr-2"></i>My Profile
                        </a>
                        <a href="logout.php" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                            <i class="ri-logout-box-line mr-2"></i>Logout
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="px-4 py-2 bg-white text-green-500 rounded-lg hover:bg-green-100">
                            <i class="ri-login-box-line mr-2"></i>Login
                        </a>
                        <a href="register.php" class="px-4 py-2 bg-white text-green-500 rounded-lg hover:bg-green-100">
                            <i class="ri-user-add-line mr-2"></i>Daftar
                        </a>
                    <?php endif; ?>
                </div>
                
                <div class="md:hidden">
                    <button id="mobileMenuToggle" class="text-white focus:outline-none">
                        <i class="ri-menu-line text-2xl transform transition-transform" id="hamburgerIcon"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div id="mobileMenu" class="hidden md:hidden absolute top-full left-0 w-full z-50 mt-2">
            <div class="max-w-5xl mx-auto px-4">
                <div class="glassy-nav rounded-2xl py-4 px-6">
                    <div class="space-y-4">
                        <a href="#home" class="block mobile-menu-item text-white py-2 border-b border-green-600 hover:bg-green-600 rounded">
                            <i class="ri-home-line mr-2"></i>Beranda
                        </a>
                        <a href="#about" class="block mobile-menu-item text-white py-2 border-b border-green-600 hover:bg-green-600 rounded">
                            <i class="ri-information-line mr-2"></i>Tentang
                        </a>
                        <a href="#features" class="block mobile-menu-item text-white py-2 border-b border-green-600 hover:bg-green-600 rounded">
                            <i class="ri-grid-line mr-2"></i>Fitur
                        </a>
                        <a href="#contact" class="block mobile-menu-item text-white py-2 border-b border-green-600 hover:bg-green-600 rounded">
                            <i class="ri-contacts-line mr-2"></i>Kontak
                        </a>
                        <?php if ($isLoggedIn): ?>
                            <a href="booking-room.php" class="block mobile-menu-item text-white py-2 border-b border-green-600 hover:bg-green-600 rounded">
                                <i class="ri-file-list-line mr-2"></i>Pinjam Ruang
                            </a>
                            <a href="profile.php" class="block mobile-menu-item text-white py-2 border-b border-green-600 hover:bg-green-600 rounded">
                                <i class="ri-list-check-line mr-2"></i>Profile
                            </a>
                            <a href="logout.php" class="block mobile-menu-item text-white py-2 border-b border-red-600 hover:bg-red-600 rounded">
                                <i class="ri-logout-box-line mr-2"></i>Logout
                            </a>
                        <?php else: ?>
                            <a href="login.php" class="block mobile-menu-item text-white py-2 border-b border-green-600 hover:bg-green-600 rounded">
                                <i class="ri-login-box-line mr-2"></i>Login
                            </a>
                            <a href="register.php" class="block mobile-menu-item text-white py-2 border-b border-green-600 hover:bg-green-600 rounded">
                                <i class="ri-user-add-line mr-2"></i>Daftar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileMenu = document.getElementById('mobileMenu');
            const hamburgerIcon = document.getElementById('hamburgerIcon');

            mobileMenuToggle.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                hamburgerIcon.classList.toggle('rotate-90');
            });

            // Optional: Close mobile menu when clicking outside
            document.addEventListener('click', (event) => {
                const isClickInsideMobileMenu = mobileMenu.contains(event.target);
                const isClickOnToggle = mobileMenuToggle.contains(event.target);
                
                if (!isClickInsideMobileMenu && !isClickOnToggle && !mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                    hamburgerIcon.classList.remove('rotate-90');
                }
            });
        });
    </script>
</body>
</html>
