<?php
// components/about.php
?>
<section id="about" class="bg-gradient-to-br from-green-50 to-green-100 py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-green-800 mb-4 transform transition-all duration-700 hover:scale-105">
                Tentang RuangNU
            </h2>
            <p class="text-xl text-green-600 max-w-3xl mx-auto">
                Transformasi Digital Manajemen Ruangan di Universitas NU Yogyakarta
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl shadow-lg p-8 group hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-4 hover:scale-105">
                <div class="text-center">
                    <div class="w-24 h-24 mx-auto mb-6 bg-green-100 rounded-full flex items-center justify-center group-hover:bg-green-200 transition-all">
                        <i class="ri-eye-line text-5xl text-green-600 group-hover:text-green-700 transition-all"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-green-800 mb-4">Visi</h3>
                    <p class="text-green-600">
                        Menjadi platform peminjaman ruangan tercanggih yang mengintegrasikan teknologi dan kemudahan akses
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-8 group hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-4 hover:scale-105">
                <div class="text-center">
                    <div class="w-24 h-24 mx-auto mb-6 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-200 transition-all">
                        <i class="ri-rocket-line text-5xl text-blue-600 group-hover:text-blue-700 transition-all"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-green-800 mb-4">Misi</h3>
                    <p class="text-green-600">
                        Mengoptimalkan penggunaan ruangan melalui sistem digital pintar, efisien, dan transparan
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-8 group hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-4 hover:scale-105">
                <div class="text-center">
                    <div class="w-24 h-24 mx-auto mb-6 bg-purple-100 rounded-full flex items-center justify-center group-hover:bg-purple-200 transition-all">
                        <i class="ri-lightbulb-line text-5xl text-purple-600 group-hover:text-purple-700 transition-all"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-green-800 mb-4">Tujuan</h3>
                    <p class="text-green-600">
                        Menciptakan ekosistem manajemen ruangan yang cerdas, mudah digunakan, dan berkelanjutan
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-16 text-center">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg p-10 transform transition-all duration-700 hover:scale-105">
                <h3 class="text-3xl font-bold text-green-800 mb-6">Komitmen Kami</h3>
                <p class="text-xl text-green-600 leading-relaxed">
                    RuangNU berkomitmen untuk menghadirkan solusi teknologi yang memudahkan sivitas akademika dalam mengelola dan meminjam ruangan dengan cepat, aman, dan transparan.
                </p>
            </div>
        </div>
    </div>

    <style>
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 10px rgba(16, 185, 129, 0.3); }
            50% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.6); }
        }

        .group:hover {
            animation: pulse-glow 1.5s infinite;
        }
    </style>
</section>