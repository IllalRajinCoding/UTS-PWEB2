<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../src/output.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#4361ee',
                        secondary: '#3f37c9',
                    },
                },
            },
        };
    </script>
    <style>
        .backdrop-blur {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 40;
            display: none;
        }

        .mobile-menu {
            z-index: 50;
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-100 to-white dark:from-sky-900 dark:to-gray-800 min-h-screen">
    <nav class="bg-white/80 dark:bg-gray-900/80 shadow backdrop-blur-md sticky top-0 z-30">
        <div class="max-w-screen-xl mx-auto flex items-center justify-between p-4">
            <div class="flex items-center space-x-4">
                <button id="theme-toggle" class="text-gray-600 dark:text-gray-300 hover:text-primary transition">
                    <!-- Default icon (will be updated by JS) -->
                    <svg id="theme-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <!-- Matahari (sun) -->
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>

                <span class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</span>
            </div>
            <div class="md:hidden">
                <button id="hamburger"
                    class="text-gray-600 dark:text-gray-300 hover:text-primary focus:outline-none focus:ring">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            <ul id="navbar-default"
                class="hidden md:flex space-x-6 font-medium text-gray-700 dark:text-gray-200">
                <li><a href="#" class="hover:text-primary transition-colors">Home</a></li>
                <li><a href="#" class="hover:text-primary transition-colors">Data UMKM</a></li>
                <li><a href="#" class="hover:text-primary transition-colors">Data Kota</a></li>
                <li><a href="#" class="hover:text-primary transition-colors">Data Pembina</a></li>
                <li><a href="../prosess/logout.php"
                        class="hover:text-red-500 transition-colors font-semibold">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div id="backdrop-blur" class="backdrop-blur"></div>
    <div id="mobile-menu"
        class="mobile-menu hidden bg-white dark:bg-gray-800 rounded-b-lg shadow-lg p-4 mx-4">
        <a href="#" class="block py-2 text-gray-800 dark:text-gray-200 hover:text-primary">Home</a>
        <a href="#" class="block py-2 text-gray-800 dark:text-gray-200 hover:text-primary">Data UMKM</a>
        <a href="#" class="block py-2 text-gray-800 dark:text-gray-200 hover:text-primary">Data Kota</a>
        <a href="#" class="block py-2 text-gray-800 dark:text-gray-200 hover:text-primary">Data Pembina</a>
        <a href="#" class="block py-2 text-gray-800 dark:text-gray-200 hover:text-primary">Data Provinsi</a>
        <a href="../prosess/logout.php" class="block py-2 text-red-600 hover:text-red-800 font-bold">Logout</a>
    </div>

    <main class="py-10 px-4 md:px-12 lg:px-24">
        <div class="text-center mb-10">
            <h1 class="text-5xl font-extrabold text-gray-800 dark:text-white">Selamat Datang</h1>
            <p class="mt-2 text-xl text-gray-600 dark:text-gray-300">Aplikasi Manajemen Data UMKM</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
            <div
                class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-2">Data Pembina</h2>
                <p class="text-gray-600 dark:text-gray-400">Kelola data pembina dan informasi terkait.</p>
                <a href="../form/pembina.php"
                    class="mt-4 inline-block px-4 py-2 bg-primary text-white rounded-lg shadow hover:bg-secondary transition">Lihat
                    Data</a>
            </div>

            <div
                class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-2">Data Kategori UMKM</h2>
                <p class="text-gray-600 dark:text-gray-400">Kelola kategori usaha mikro, kecil, dan menengah.</p>
                <a href="../form/umkm.php"
                    class="mt-4 inline-block px-4 py-2 bg-primary text-white rounded-lg shadow hover:bg-secondary transition">Lihat
                    Data</a>
            </div>

            <div
                class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-2">Data Kota</h2>
                <p class="text-gray-600 dark:text-gray-400">Kelola data wilayah kabupaten dan kota.</p>
                <a href="../form/kabkota.php"
                    class="mt-4 inline-block px-4 py-2 bg-primary text-white rounded-lg shadow hover:bg-secondary transition">Lihat
                    Data</a>
            </div>
            <div
                class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-2">Data Provinsi</h2>
                <p class="text-gray-600 dark:text-gray-400">Kelola data provinsi.</p>
                <a href="../form/provinsi.php"
                    class="mt-4 inline-block px-4 py-2 bg-primary text-white rounded-lg shadow hover:bg-secondary transition">Lihat
                    Data</a>
            </div>
        </div>
    </main>

    <script src="../src/toggle.js"></script>
    <script>
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobile-menu');
        const backdrop = document.getElementById('backdrop-blur');

        hamburger.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            backdrop.style.display = mobileMenu.classList.contains('hidden') ? 'none' : 'block';
            document.body.style.overflow = mobileMenu.classList.contains('hidden') ? 'auto' : 'hidden';
        });

        backdrop.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            backdrop.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
    </script>

    <script>
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');

        // Ganti icon
        function updateIcon(isDark) {
            themeIcon.innerHTML = isDark ?
                `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                 d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z" />` // Moon
                :
                `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                 d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />`; // Sun
        }

        // Toggle dark mode
        themeToggle.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateIcon(isDark);
        });

        // Inisialisasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            const userTheme = localStorage.getItem('theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = userTheme === 'dark' || (!userTheme && systemPrefersDark);

            if (isDark) document.documentElement.classList.add('dark');
            updateIcon(isDark);
        });
    </script>

</body>

</html>