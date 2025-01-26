<?php
// components/footer.php
?>
<footer class="bg-gradient-to-br from-green-700 via-green-600 to-green-800 text-white py-12">
    <div class="container mx-auto px-4 grid md:grid-cols-4 gap-8">
        <!-- Logo dan Deskripsi -->
        <div>
            <h3 class="text-2xl font-bold mb-4">RuangNU</h3>
            <p class="text-green-200 mb-4">
                Sistem Peminjaman Ruangan Universitas NU Yogyakarta yang mempermudah pengelolaan ruang secara digital.
            </p>
            <div class="flex space-x-4">
                <a href="#" class="text-green-300 hover:text-white transition duration-200 shadow-lg">
                    <i class="ri-facebook-circle-fill text-2xl"></i>
                </a>
                <a href="#" class="text-green-300 hover:text-white transition duration-200 shadow-lg">
                    <i class="ri-twitter-fill text-2xl"></i>
                </a>
                <a href="#" class="text-green-300 hover:text-white transition duration-200 shadow-lg">
                    <i class="ri-instagram-fill text-2xl"></i>
                </a>
                <a href="#" class="text-green-300 hover:text-white transition duration-200 shadow-lg">
                    <i class="ri-linkedin-box-fill text-2xl"></i>
                </a>
            </div>
        </div>

        <!-- Tautan Cepat -->
        <div>
            <h4 class="text-xl font-semibold mb-4">Tautan Cepat</h4>
            <ul class="space-y-2">
                <li><a href="#home" class="text-green-200 hover:text-white transition duration-200 shadow-sm">Beranda</a></li>
                <li><a href="#about" class="text-green-200 hover:text-white transition duration-200 shadow-sm">Tentang</a></li>
                <li><a href="#features" class="text-green-200 hover:text-white transition duration-200 shadow-sm">Fitur</a></li>
                <li><a href="#contact" class="text-green-200 hover:text-white transition duration-200 shadow-sm">Kontak</a></li>
            </ul>
        </div>

        <!-- Kontak -->
        <div>
            <h4 class="text-xl font-semibold mb-4">Kontak</h4>
            <p class="text-green-200 mb-2">
                <i class="ri-map-pin-line mr-2"></i>Universitas NU Yogyakarta
            </p>
            <p class="text-green-200 mb-2">
                <i class="ri-phone-line mr-2"></i>(0274) XXX XXX
            </p>
            <p class="text-green-200">
                <i class="ri-mail-line mr-2"></i>ruangnu@unuyogya.ac.id
            </p>
        </div>

        <!-- Berlangganan Newsletter -->
        <div>
            <h4 class="text-xl font-semibold mb-4">Berlangganan</h4>
            <p class="text-green-200 mb-4">Dapatkan informasi terbaru langsung di email Anda.</p>
            <form action="#" method="POST" class="flex flex-col space-y-2">
                <input type="email" name="email" placeholder="Masukkan email"
                    class="px-4 py-2 rounded-lg bg-green-900 text-green-200 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-md">
                <button type="submit"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-200 shadow-lg">
                    Berlangganan
                </button>
            </form>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="mt-8 text-center border-t border-green-600 pt-6">
        <p class="text-green-200">&copy; <?php echo date('Y'); ?> RuangNU. All Rights Reserved.</p>
        <p class="text-green-300 text-sm mt-2">
            Dibuat dengan <span class="text-red-500">&hearts;</span> oleh Tim Pengembang
        </p>
    </div>
</footer>

<!-- Icon Library -->
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
