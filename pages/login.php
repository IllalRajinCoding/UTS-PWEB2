<?php
session_start();

// Cek jika user sudah login, redirect ke halaman admin
if (isset($_SESSION['loggedin'])) {
  header("Location: ../index.php");
  exit;
}

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  // Validasi login
  if ($username === 'admin' && $password === 'admin') {
    $_SESSION['loggedin'] = true;
    header("Location: dashboard.php");
    exit;
  } else {
    $error = "Username atau password salah!";
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login</title>
  <link rel="stylesheet" href="../src/output.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-sky-100 via-blue-200 to-sky-300 dark:from-sky-900 dark:via-sky-800 dark:to-sky-700 flex items-center justify-center h-screen transition-all duration-500">

  <!-- Dark mode toggle -->
  <button id="toggle" class="absolute top-4 right-4 p-2 rounded-full bg-white/80 dark:bg-sky-700 text-sky-700 dark:text-sky-200 hover:scale-105 shadow-lg transition">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path id="toggle-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
  </button>

  <!-- Login Card -->
  <div class="bg-white/90 dark:bg-sky-800/90 backdrop-blur-md p-8 rounded-xl shadow-2xl w-96 flex flex-col items-center relative animate-fade-in-up">

    <!-- Icon -->
    <div class="mb-4">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-sky-600 dark:text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zM12 14c-2.667 0-8 1.334-8 4v1h16v-1c0-2.666-5.333-4-8-4z" />
      </svg>
    </div>

    <details class="group w-full" open>
      <summary class="flex justify-between items-center cursor-pointer list-none">
        <h1 class="text-2xl font-bold text-sky-800 dark:text-sky-100 group-open:text-sky-600 dark:group-open:text-sky-300 transition-colors">
          Masuk Admin
        </h1>
        <svg class="w-6 h-6 text-sky-700 dark:text-sky-300 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </summary>

      <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="mt-6 w-full space-y-5">
        <?php if (isset($error)): ?>
          <div class="p-3 bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-100 rounded transition-opacity duration-300 animate-pulse">
            <?= $error; ?>
          </div>
        <?php endif; ?>

        <div>
          <label for="username" class="block text-sm font-semibold text-sky-800 dark:text-sky-200 mb-1">Username</label>
          <input type="text" id="username" name="username" required
            class="w-full px-4 py-2 rounded-lg border border-sky-300 dark:border-sky-600 bg-white dark:bg-sky-700 text-sky-900 dark:text-sky-100 focus:outline-none focus:ring-2 focus:ring-sky-500 dark:focus:ring-sky-300 transition" placeholder="Masukkan username">
        </div>

        <div>
          <label for="password" class="block text-sm font-semibold text-sky-800 dark:text-sky-200 mb-1">Password</label>
          <input type="password" id="password" name="password" required
            class="w-full px-4 py-2 rounded-lg border border-sky-300 dark:border-sky-600 bg-white dark:bg-sky-700 text-sky-900 dark:text-sky-100 focus:outline-none focus:ring-2 focus:ring-sky-500 dark:focus:ring-sky-300 transition" placeholder="Masukkan password">
        </div>

        <button type="submit"
          class="w-full bg-sky-600 hover:bg-sky-700 dark:bg-sky-500 dark:hover:bg-sky-600 text-white py-2 rounded-lg font-semibold shadow-md hover:shadow-xl transition duration-300">
          Login
        </button>
      </form>
    </details>

    <div class="mt-6 text-center w-full border-t border-sky-200 dark:border-sky-700 pt-4">
      <a href="../index.php" class="inline-flex items-center text-sm text-sky-600 dark:text-sky-300 hover:text-sky-800 dark:hover:text-sky-100 transition group">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 group-hover:-translate-x-1 transform transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Kembali</span>
      </a>
    </div>
  </div>

  <script src="../src/toggle.js"></script>
</body>


</html>