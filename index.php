<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMKM</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 dark:bg-gray-900">
    <!-- Navbar -->
    <nav class="bg-white dark:bg-sky-800 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <img src="logo.png" alt="Logo" class="h-10 w-10">
                    <span class="ml-2 text-xl font-semibold text-gray-800 dark:text-white">UMKM</span>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <details class="relative">
                        <summary class="text-gray-800 dark:text-white hover:text-gray-600 dark:hover:text-gray-300 px-3 py-2 rounded-md text-sm font-medium cursor-pointer">
                            Menu
                        </summary>
                        <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-sky-700 rounded-md shadow-lg py-1 z-10">
                            <a href="./pages/pelatihan.php" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-sky-600">Pelatihan</a>
                            <a href="./pages/login.php" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-sky-600">Login</a>
                        </div>
                    </details>
                </div>

                <!-- Desktop menu -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="./pages/pelatihan.php" class="text-gray-800 dark:text-white hover:bg-sky-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Pelatihan</a>
                        <a href="./pages/login.php" class="text-gray-800 dark:text-white hover:bg-sky-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Hero -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl sm:tracking-tight lg:text-6xl">
                Selamat Datang di UMKM
            </h1>
            <p class="mt-5 max-w-xl mx-auto text-xl text-gray-500 dark:text-gray-300">
                Platform untuk mengembangkan usaha mikro, kecil, dan menengah Anda.
            </p>
            <div class="mt-10 flex justify-center gap-4">
                <a href="./pages/pelatihan.php" class="px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 md:py-4 md:text-lg md:px-10">
                    Pelatihan
                </a>
                <a href="./pages/profile.php" class="px-8 py-3 border border-transparent text-base font-medium rounded-md text-sky-700 bg-sky-100 hover:bg-sky-200 md:py-4 md:text-lg md:px-10">
                    Profile UMKM
                </a>
            </div>
        </div>
    </main>
</body>

</html>