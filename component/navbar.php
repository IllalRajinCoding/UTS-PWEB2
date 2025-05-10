   <nav class="bg-white shadow-md sticky top-0 z-50">
       <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
           <div class="flex items-center justify-between h-16">
               <!-- Logo -->
               <div class="flex-shrink-0 flex items-center">
                   <img src="../src/assets/logo.png" alt="Logo" class="h-10 w-10 rounded-md">
                   <span class="ml-2 text-xl font-semibold text-primary">UMKM<span class="text-accent">Indonesia</span></span>
               </div>

               <!-- Desktop menu -->
               <div class="hidden md:block">
                   <div class="ml-10 flex items-center space-x-4">
                       <a href="../index.php" class="text-dark hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">Beranda</a>
                       <a href="../index.php" class="text-dark hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">Produk</a>
                       <a href="../index.php" class="text-dark hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">Lokasi</a>
                       <a href="../pages/pelatihan.php" class="text-dark hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">Pelatihan</a>
                       <a href="../pages/login.php" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition-all text-sm font-medium shadow hover:shadow-md">
                           <i class="fas fa-user mr-1"></i>Login
                       </a>
                   </div>
               </div>

               <!-- Mobile menu button -->
               <div class="md:hidden flex items-center">
                   <button id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-800 hover:text-primary focus:outline-none transition-colors" aria-expanded="false" aria-controls="mobile-menu">
                       <span class="sr-only">Open main menu</span>
                       <!-- Hamburger icon -->
                       <svg id="menu-open-icon" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                       </svg>
                       <!-- Close icon (hidden by default) -->
                       <svg id="menu-close-icon" class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                       </svg>
                   </button>
               </div>
           </div>
       </div>

       <!-- Mobile menu (hidden by default) -->
       <div id="mobile-menu" class="mobile-menu md:hidden hidden bg-white shadow-lg">
           <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
               <a href="../index.php" class="block px-3 py-2 rounded-md text-base font-medium text-dark hover:text-primary hover:bg-gray-50 transition-colors">Beranda</a>
               <a href="#produk" class="block px-3 py-2 rounded-md text-base font-medium text-dark hover:text-primary hover:bg-gray-50 transition-colors">Produk</a>
               <a href="#lokasi" class="block px-3 py-2 rounded-md text-base font-medium text-dark hover:text-primary hover:bg-gray-50 transition-colors">Lokasi</a>
               <a href="../pages/pelatihan.php" class="block px-3 py-2 rounded-md text-base font-medium text-dark hover:text-primary hover:bg-gray-50 transition-colors">Pelatihan</a>
               <a href="../pages/login.php" class="block w-full text-center bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition-colors text-base font-medium mt-2">
                   <i class="fas fa-user mr-1"></i>Login
               </a>
           </div>
       </div>
   </nav>

   <!-- JavaScript for mobile menu toggle -->
   <script>
       document.addEventListener('DOMContentLoaded', function() {
           const mobileMenuButton = document.getElementById('mobile-menu-button');
           const mobileMenu = document.getElementById('mobile-menu');
           const menuOpenIcon = document.getElementById('menu-open-icon');
           const menuCloseIcon = document.getElementById('menu-close-icon');

           mobileMenuButton.addEventListener('click', function() {
               const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';

               // Toggle menu visibility
               mobileMenu.classList.toggle('hidden');
               mobileMenu.classList.toggle('show');

               // Toggle icons
               menuOpenIcon.classList.toggle('hidden');
               menuCloseIcon.classList.toggle('hidden');

               // Update aria-expanded
               mobileMenuButton.setAttribute('aria-expanded', !isExpanded);
           });
       });
   </script>