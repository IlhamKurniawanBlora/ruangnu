<?php
// components/feature.php
?>
<section id="features" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-green-800 mb-4">Fitur Unggulan RuangNU</h2>
            <p class="text-xl text-green-600 max-w-3xl mx-auto">
                Teknologi Cerdas untuk Manajemen Ruangan yang Efisien dan Transparan
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-green-400 rounded-2xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
                <div class="relative bg-white p-8 rounded-2xl shadow-lg text-center">
                    <div class="w-20 h-20 mx-auto mb-6 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="ri-calendar-check-line text-4xl text-green-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-green-800 mb-4">Pemesanan Online</h3>
                    <p class="text-green-600">
                        Ajukan peminjaman ruangan kapan pun dan di mana pun dengan antarmuka yang intuitif
                    </p>
                </div>
            </div>

            <div class="relative group">
                <div class="absolute -inset-0.5 bg-blue-400 rounded-2xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
                <div class="relative bg-white p-8 rounded-2xl shadow-lg text-center">
                    <div class="w-20 h-20 mx-auto mb-6 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="ri-dashboard-line text-4xl text-blue-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-green-800 mb-4">Manajemen Ruangan</h3>
                    <p class="text-green-600">
                        Pantau ketersediaan dan status ruangan secara real-time dengan mudah
                    </p>
                </div>
            </div>

            <div class="relative group">
                <div class="absolute -inset-0.5 bg-purple-400 rounded-2xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
                <div class="relative bg-white p-8 rounded-2xl shadow-lg text-center">
                    <div class="w-20 h-20 mx-auto mb-6 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="ri-time-line text-4xl text-purple-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-green-800 mb-4">Notifikasi Cepat</h3>
                    <p class="text-green-600">
                        Terima konfirmasi dan pemberitahuan instan melalui sistem terintegrasi
                    </p>
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-8 mt-8">
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-yellow-400 rounded-2xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
                <div class="relative bg-white p-8 rounded-2xl shadow-lg text-center">
                    <div class="w-20 h-20 mx-auto mb-6 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="ri-shield-check-line text-4xl text-yellow-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-green-800 mb-4">Keamanan Terjamin</h3>
                    <p class="text-green-600">
                        Sistem terenkripsi dan hanya dapat diakses oleh civitas akademika
                    </p>
                </div>
            </div>

            <div class="relative group">
                <div class="absolute -inset-0.5 bg-red-400 rounded-2xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
                <div class="relative bg-white p-8 rounded-2xl shadow-lg text-center">
                    <div class="w-20 h-20 mx-auto mb-6 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="ri-file-text-line text-4xl text-red-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-green-800 mb-4">Laporan Terperinci</h3>
                    <p class="text-green-600">
                        Akses riwayat dan laporan peminjaman dengan detail komprehensif
                    </p>
                </div>
            </div>

            <div class="relative group">
                <div class="absolute -inset-0.5 bg-indigo-400 rounded-2xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
                <div class="relative bg-white p-8 rounded-2xl shadow-lg text-center">
                    <div class="w-20 h-20 mx-auto mb-6 bg-indigo-100 rounded-full flex items-center justify-center">
                        <i class="ri-smartphone-line text-4xl text-indigo-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-green-800 mb-4">Mobile Friendly</h3>
                    <p class="text-green-600">
                        Gunakan RuangNU di smartphone atau komputer dengan pengalaman optimal
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .group:hover .absolute {
            transition: opacity 0.3s ease;
        }
    </style>
</section>