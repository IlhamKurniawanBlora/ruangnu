<?php
// components/hero.php
?>
<section id="hero" class="relative min-h-screen flex items-center overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-green-400 to-green-600 opacity-90 parallax-bg"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="grid md:grid-cols-2 items-center">
            <div class="text-white text-center md:text-left parallax-content" data-speed="0.5">
                    <?php if ($isLoggedIn): ?>
                        <p class="text-xl mb-8 opacity-80 transform transition-all duration-700 hover:opacity-100">
                            Selamat Datang <?php echo htmlspecialchars($user['name']); ?>
                        </p>
                    <?php else: ?>
                        <p class="text-xl mb-8 opacity-80 transform transition-all duration-700 hover:opacity-100">
                            Selamat Datang di RuangNU
                        </p>
                    <?php endif; ?>
                <h1 class="text-4xl md:text-5xl font-bold mb-6 transform transition-all duration-700 hover:scale-105">
                    Peminjaman Ruang <br>Lebih Mudah dengan RuangNU
                </h1>
                <p class="text-xl mb-8 opacity-80 transform transition-all duration-700 hover:opacity-100">
                    Solusi cerdas untuk manajemen ruangan di Universitas NU Yogyakarta
                </p>
                <div class="space-x-4">
                    <?php if ($isLoggedIn): ?>
                    <a href="pinjam-ruang.php" class="px-6 py-3 bg-white text-green-600 rounded-lg shadow-lg hover:shadow-xl transform transition-all duration-300 hover:scale-110 inline-block">
                        <i class="ri-login-box-line mr-2"></i>Pinjam Ruangan
                    </a>
                    <?php else: ?>
                        <a href="login.php" class="px-6 py-3 bg-white text-green-600 rounded-lg shadow-lg hover:shadow-xl transform transition-all duration-300 hover:scale-110 inline-block">
                        <i class="ri-login-box-line mr-2"></i>Mulai Sekarang
                    </a>
                    <?php endif; ?>
                    
                    <a href="#about" class="px-6 py-3 border-2 border-white text-white rounded-lg hover:bg-white hover:text-green-600 transform transition-all duration-300 hover:scale-110 inline-block">
                        <i class="ri-information-line mr-2"></i>Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
            
            <div class="hidden md:block parallax-image" data-speed="0.3">
                <img src="assets/images/file.png" alt="RuangNU Hero" class="w-full transform transition-all duration-700 hover:scale-110 hover:rotate-3">
            </div>
        </div>
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-white to-transparent"></div>
</section>

<style>
    @keyframes floating {
        0% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
        100% { transform: translateY(0); }
    }

    .parallax-content {
        transition: transform 0.5s ease;
    }

    .parallax-image img {
        animation: floating 4s ease-in-out infinite;
    }

    .parallax-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        transform: translateZ(-1px) scale(2);
        z-index: -1;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const parallaxElements = document.querySelectorAll('.parallax-content, .parallax-image');

        window.addEventListener('mousemove', (e) => {
            parallaxElements.forEach(element => {
                const speed = element.dataset.speed || 0.5;
                const x = (window.innerWidth - e.pageX * speed) / 100;
                const y = (window.innerHeight - e.pageY * speed) / 100;
                
                element.style.transform = `translateX(${x}px) translateY(${y}px)`;
            });
        });
    });
</script>