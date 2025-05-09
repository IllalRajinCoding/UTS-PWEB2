<?php
include '../config/koneksi.php';
session_start();

class Provinsi
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }

    public function getById($id)
    {
        $id = (int)$id;
        $query = "SELECT * FROM provinsi WHERE id = $id";
        $result = mysqli_query($this->koneksi, $query);
        return mysqli_fetch_assoc($result);
    }

    public function update($id, $data)
    {
        $id = (int)$id;
        $nama = mysqli_real_escape_string($this->koneksi, $data['nama']);
        $ibukota = mysqli_real_escape_string($this->koneksi, $data['ibukota']);
        $latitude = mysqli_real_escape_string($this->koneksi, $data['latitude']);
        $longitude = mysqli_real_escape_string($this->koneksi, $data['longitude']);

        $query = "UPDATE provinsi SET
                  nama = '$nama',
                  ibukota = '$ibukota',
                  latitude = '$latitude',
                  longitude = '$longitude'
                  WHERE id = $id";

        return mysqli_query($this->koneksi, $query);
    }
}

$provinsi = new Provinsi($koneksi);

// Ambil ID dari URL
$id = $_GET['id'] ?? null;

// Jika ID tidak ada, redirect kembali
if (!$id) {
    header('Location: provinsi.php');
    exit;
}

// Ambil data provinsi berdasarkan ID
$data_provinsi = $provinsi->getById($id);

// Jika data provinsi tidak ditemukan, redirect kembali
if (!$data_provinsi) {
    header('Location: provinsi.php');
    exit;
}

// Handle form submission for editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nama' => $_POST['nama'],
        'ibukota' => $_POST['ibukota'],
        'latitude' => $_POST['latitude'],
        'longitude' => $_POST['longitude'],
    ];
    if ($provinsi->update($id, $data)) {
        $_SESSION['message'] = 'Data provinsi berhasil diperbarui!';
        header('Location: provinsi.php');
        exit;
    } else {
        $_SESSION['message'] = 'Gagal memperbarui data provinsi!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Provinsi</title>
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
                        <path id="toggle-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>
                <div class="flex items-center">
                    <span class="text-xl font-bold text-gray-900 dark:text-white">Edit Data Provinsi</span>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-colors duration-300">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <i class="fas fa-edit text-primary dark:text-primary-300 mr-2"></i>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Data Provinsi</h2>
                </div>
            </div>
            <div class="px-6 py-4">
                <form method="post" class="space-y-6">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($data_provinsi['id']) ?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Provinsi</label>
                            <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($data_provinsi['nama']) ?>" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-700 dark:text-white sm:text-sm transition-colors duration-200">
                        </div>
                        <div>
                            <label for="ibukota" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ibukota</label>
                            <input type="text" name="ibukota" id="ibukota" value="<?= htmlspecialchars($data_provinsi['ibukota']) ?>" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-700 dark:text-white sm:text-sm transition-colors duration-200">
                        </div>
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Latitude</label>
                            <input type="text" name="latitude" id="latitude" value="<?= htmlspecialchars($data_provinsi['latitude']) ?>" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-700 dark:text-white sm:text-sm transition-colors duration-200">
                        </div>
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Longitude</label>
                            <input type="text" name="longitude" id="longitude" value="<?= htmlspecialchars($data_provinsi['longitude']) ?>" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-700 dark:text-white sm:text-sm transition-colors duration-200">
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 pt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-gray-800 transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                        <a href="provinsi.php" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-gray-800 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="../src/index.js"></script>
    <script src="../src/toggle.js"></script>
</body>
<?php
if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']);
}
?>

</html>