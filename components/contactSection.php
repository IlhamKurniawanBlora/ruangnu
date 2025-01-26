<?php
// components/contact.php
?>
<section id="contact" class="py-16 bg-gradient-to-br from-green-50 to-green-100">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-green-800 mb-4">Hubungi Kami</h2>
            <p class="text-lg text-green-700 max-w-2xl mx-auto">
                Ada pertanyaan atau butuh bantuan? Jangan ragu untuk menghubungi tim RuangNU.
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Contact Form -->
            <div class="bg-white p-8 rounded-lg shadow-lg border border-green-200">
                <h3 class="text-2xl font-semibold text-green-800 mb-6">Kirim Pesan</h3>
                <form id="contactForm" class="space-y-5">
                    <div class="relative">
                        <input type="text" id="name" name="name" required 
                               class="peer w-full px-4 py-3 border-b-2 border-green-300 focus:border-green-600 transition-colors duration-300 outline-none"
                               placeholder=" ">
                        <label for="name" class="absolute left-0 -top-4 text-green-700 text-sm 
                               peer-placeholder-shown:top-3 peer-placeholder-shown:text-base 
                               peer-focus:-top-4 peer-focus:text-sm transition-all">
                            Nama Lengkap
                        </label>
                    </div>
                    
                    <div class="relative">
                        <input type="email" id="email" name="email" required 
                               class="peer w-full px-4 py-3 border-b-2 border-green-300 focus:border-green-600 transition-colors duration-300 outline-none"
                               placeholder=" ">
                        <label for="email" class="absolute left-0 -top-4 text-green-700 text-sm 
                               peer-placeholder-shown:top-3 peer-placeholder-shown:text-base 
                               peer-focus:-top-4 peer-focus:text-sm transition-all">
                            Alamat Email
                        </label>
                    </div>
                    
                    <div class="relative">
                        <textarea id="message" name="message" rows="4" required 
                                  class="peer w-full px-4 py-3 border-b-2 border-green-300 focus:border-green-600 transition-colors duration-300 outline-none resize-none"
                                  placeholder=" "></textarea>
                        <label for="message" class="absolute left-0 -top-4 text-green-700 text-sm 
                               peer-placeholder-shown:top-3 peer-placeholder-shown:text-base 
                               peer-focus:-top-4 peer-focus:text-sm transition-all">
                            Pesan Anda
                        </label>
                    </div>
                    
                    <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg 
                            hover:bg-green-700 transition-colors duration-300 transform hover:scale-105 active:scale-95">
                        Kirim Pesan
                    </button>
                </form>
            </div>
            
            <!-- Contact Info -->
            <div class="flex flex-col justify-center space-y-6">
                <div class="flex items-center space-x-4">
                    <i class="ri-map-pin-line text-green-700 text-2xl"></i>
                    <p class="text-lg text-green-800">Universitas NU Yogyakarta, Jalan Ringroad Barat</p>
                </div>
                <div class="flex items-center space-x-4">
                    <i class="ri-phone-line text-green-700 text-2xl"></i>
                    <p class="text-lg text-green-800">(0274) XXX-XXXX</p>
                </div>
                <div class="flex items-center space-x-4">
                    <i class="ri-mail-line text-green-700 text-2xl"></i>
                    <p class="text-lg text-green-800">ruangnu@unuyogya.ac.id</p>
                </div>
                <div class="flex space-x-6 mt-4">
                    <a href="#" class="text-green-700 text-2xl hover:text-green-900"><i class="ri-facebook-circle-line"></i></a>
                    <a href="#" class="text-green-700 text-2xl hover:text-green-900"><i class="ri-twitter-line"></i></a>
                    <a href="#" class="text-green-700 text-2xl hover:text-green-900"><i class="ri-instagram-line"></i></a>
                    <a href="#" class="text-green-700 text-2xl hover:text-green-900"><i class="ri-linkedin-line"></i></a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic form validation
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const message = document.getElementById('message').value.trim();
            
            if (!name || !email || !message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Mohon lengkapi semua field!',
                    confirmButtonColor: '#10B981'
                });
                return;
            }
            
            // Simulated form submission
            Swal.fire({
                icon: 'success',
                title: 'Pesan Terkirim!',
                text: 'Terima kasih. Kami akan segera menghubungi Anda.',
                confirmButtonColor: '#10B981'
            });
            
            // Reset form
            this.reset();
        });
    </script>
    
    <!-- Sweet Alert 2 for better alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</section>
