<?php
include '../config/koneksi.php';
session_start();

// Cek jika tidak ada ID yang dikirim
if (!isset($_GET['id'])) {
    $_SESSION['error'] = 'ID kabupaten/kota tidak valid!';
    header('Location: kabkota.php');
    exit;
}

$id = (int)$_GET['id'];

// Proses update data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $latitude = mysqli_real_escape_string($koneksi, $_POST['latitude']);
    $longitude = mysqli_real_escape_string($koneksi, $_POST['longitude']);
    $provinsi_id = (int)$_POST['provinsi_id'];

    $query = mysqli_query($koneksi, "UPDATE kabkota SET nama='$nama', latitude='$latitude', longitude='$longitude', provinsi_id=$provinsi_id WHERE id=$id");

    if ($query) {
        $_SESSION['message'] = 'Data berhasil diperbarui!';
        header('Location: kabkota.php');
        exit;
    } else {
        $_SESSION['error'] = 'Gagal memperbarui data!';
    }
}

// Ambil data kabupaten/kota yang akan diedit
$query = mysqli_query($koneksi, "SELECT * FROM kabkota WHERE id = $id");
$kabkota = mysqli_num_rows($query) > 0 ? mysqli_fetch_assoc($query) : null;

if (!$kabkota) {
    $_SESSION['error'] = 'Data kabupaten/kota tidak ditemukan!';
    header('Location: kabkota.php');
    exit;
}

// Ambil data provinsi untuk dropdown
$query_provinsi = mysqli_query($koneksi, "SELECT * FROM provinsi ORDER BY nama ASC");
$provinsi_data = mysqli_fetch_all($query_provinsi, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kabupaten/Kota</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#4361ee',
                        secondary: '#3f37c9',
                    }
                }
            }
        }
    </script>
</head>

<body class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <nav class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-10 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <button id="toggle" class="absolute top-4 right-4 p-2 rounded-full bg-gray-200 dark:bg-sky-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-sky-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path id="toggle-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>
                <div class="flex items-center">
                    <i class="fas fa-map-marker-alt text-primary dark:text-primary-300 mr-2"></i>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">Edit Kabupaten/Kota</span>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Edit Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-colors duration-300">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <i class="fas fa-edit text-primary dark:text-primary-300 mr-2"></i>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Data Kabupaten/Kota</h2>
                </div>
            </div>
            <div class="px-6 py-4">
                <form method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Kabupaten/Kota</label>
                            <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($kabkota['nama']) ?>" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-700 dark:text-white sm:text-sm transition-colors duration-200">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Latitude</label>
                                <input type="text" name="latitude" id="latitude" value="<?= htmlspecialchars($kabkota['latitude']) ?>" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-700 dark:text-white sm:text-sm transition-colors duration-200">
                            </div>
                            <div>
                                <label for="longitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Longitude</label>
                                <input type="text" name="longitude" id="longitude" value="<?= htmlspecialchars($kabkota['longitude']) ?>" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-700 dark:text-white sm:text-sm transition-colors duration-200">
                            </div>
                        </div>
                        <div>
                            <label for="provinsi_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Provinsi</label>
                            <select name="provinsi_id" id="provinsi_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-700 dark:text-white sm:text-sm transition-colors duration-200">
                                <?php foreach ($provinsi_data as $provinsi): ?>
                                    <option value="<?= $provinsi['id'] ?>" <?= $provinsi['id'] == $kabkota['provinsi_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($provinsi['nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 pt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-gray-800 transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                        <a href="kabkota.php" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-gray-800 transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="../src/toggle.js"></script>
</body>
<?php
if (isset($_SESSION['error'])) {
    echo "<script>alert('" . $_SESSION['error'] . "');</script>";
    unset($_SESSION['error']);
}
?>
</html>