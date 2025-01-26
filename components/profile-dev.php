<?php
// components/profile-dev.php
?>
<section id="developer-profiles" class="py-16 bg-gradient-to-br from-green-50 to-green-100">
    <div class="container mx-auto px-4">
        <h2 class="text-center text-4xl font-bold text-green-800 mb-12">Tim Pengembang RuangNU</h2>

        <!-- Carousel Container -->
        <div id="profileCarousel" class="relative overflow-hidden">
            <div class="flex transition-transform duration-700 ease-in-out" style="width: 400%; transform: translateX(0);">
                <!-- Developer Profile 1 -->
                <div class="w-1/4 px-4">
                    <div class="bg-white p-6 rounded-lg shadow-lg border border-green-200 text-center hover:scale-105 hover:shadow-2xl transition-transform duration-300">
                        <img src="assets/images/profile-blck.png" alt="Developer 1" class="w-32 h-32 mx-auto rounded-full mb-6 border border-green-300 shadow-md">
                        <h3 class="text-xl font-bold text-green-800 mb-2">Developer 1</h3>
                        <p class="text-green-700 mb-4">Frontend Developer</p>
                        <p class="text-sm text-gray-600">Inovator Digital dengan Passion untuk Teknologi</p>
                    </div>
                </div>

                <!-- Developer Profile 2 -->
                <div class="w-1/4 px-4">
                    <div class="bg-white p-6 rounded-lg shadow-lg border border-green-200 text-center hover:scale-105 hover:shadow-2xl transition-transform duration-300">
                        <img src="assets/images/profile-blck.png" alt="Developer 2" class="w-32 h-32 mx-auto rounded-full mb-6 border border-green-300 shadow-md">
                        <h3 class="text-xl font-bold text-green-800 mb-2">Developer 2</h3>
                        <p class="text-green-700 mb-4">Backend Developer</p>
                        <p class="text-sm text-gray-600">Inovator Digital dengan Passion untuk Teknologi</p>
                    </div>
                </div>

                <!-- Developer Profile 3 -->
                <div class="w-1/4 px-4">
                    <div class="bg-white p-6 rounded-lg shadow-lg border border-green-200 text-center hover:scale-105 hover:shadow-2xl transition-transform duration-300">
                        <img src="assets/images/profile-blck.png" alt="Developer 3" class="w-32 h-32 mx-auto rounded-full mb-6 border border-green-300 shadow-md">
                        <h3 class="text-xl font-bold text-green-800 mb-2">Developer 3</h3>
                        <p class="text-green-700 mb-4">UI/UX Designer</p>
                        <p class="text-sm text-gray-600">Inovator Digital dengan Passion untuk Teknologi</p>
                    </div>
                </div>

                <!-- Developer Profile 4 -->
                <div class="w-1/4 px-4">
                    <div class="bg-white p-6 rounded-lg shadow-lg border border-green-200 text-center hover:scale-105 hover:shadow-2xl transition-transform duration-300">
                        <img src="assets/images/profile-blck.png" alt="Developer 4" class="w-32 h-32 mx-auto rounded-full mb-6 border border-green-300 shadow-md">
                        <h3 class="text-xl font-bold text-green-800 mb-2">Developer 4</h3>
                        <p class="text-green-700 mb-4">DevOps Engineer</p>
                        <p class="text-sm text-gray-600">Inovator Digital dengan Passion untuk Teknologi</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <button id="prevButton" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-green-600 text-white p-3 rounded-full shadow-lg hover:bg-green-700 focus:outline-none">
                <i class="ri-arrow-left-s-line text-2xl"></i>
            </button>
            <button id="nextButton" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-green-600 text-white p-3 rounded-full shadow-lg hover:bg-green-700 focus:outline-none">
                <i class="ri-arrow-right-s-line text-2xl"></i>
            </button>
        </div>
    </div>

    <script>
        // JavaScript for automatic sliding and button controls
        const carousel = document.querySelector('#profileCarousel > div');
        const prevButton = document.getElementById('prevButton');
        const nextButton = document.getElementById('nextButton');
        let index = 0;

        const slide = () => {
            index = (index + 1) % 4; // Adjust for the number of profiles
            carousel.style.transform = `translateX(-${index * 100 / 4}%)`;
        };

        const prevSlide = () => {
            index = (index - 1 + 4) % 4; // Wrap around to last slide
            carousel.style.transform = `translateX(-${index * 100 / 4}%)`;
        };

        const nextSlide = () => {
            index = (index + 1) % 4;
            carousel.style.transform = `translateX(-${index * 100 / 4}%)`;
        };

        let autoSlide = setInterval(slide, 3000); // Auto-slide every 3 seconds

        prevButton.addEventListener('click', () => {
            clearInterval(autoSlide);
            prevSlide();
            autoSlide = setInterval(slide, 3000); // Restart auto-slide
        });

        nextButton.addEventListener('click', () => {
            clearInterval(autoSlide);
            nextSlide();
            autoSlide = setInterval(slide, 3000); // Restart auto-slide
        });
    </script>

    <!-- Add Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
</section>
