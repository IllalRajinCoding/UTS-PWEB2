<?php
include '../config/koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $latitude = mysqli_real_escape_string($koneksi, $_POST['latitude']);
    $longitude = mysqli_real_escape_string($koneksi, $_POST['longitude']);
    $provinsi_id = (int)$_POST['provinsi_id'];

    $query = mysqli_query($koneksi, "INSERT INTO kabkota (nama, latitude, longitude, provinsi_id) VALUES ('$nama', '$latitude', '$longitude', '$provinsi_id')");

    $_SESSION['message'] = $query ? 'Data berhasil ditambahkan!' : 'Gagal menambahkan data!';
    header('Location: kabkota.php');
    exit;
}

$query = mysqli_query($koneksi, "SELECT kab.*, prov.nama AS nama_provinsi FROM kabkota kab LEFT JOIN provinsi prov ON kab.provinsi_id = prov.id ORDER BY prov.nama ASC, kab.nama ASC");
$kabkota_data = mysqli_num_rows($query) > 0 ? mysqli_fetch_all($query, MYSQLI_ASSOC) : [];

$query_provinsi = mysqli_query($koneksi, "SELECT * FROM provinsi ORDER BY nama ASC");
$provinsi_data = mysqli_fetch_all($query_provinsi, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Domisili</title>
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
                    <span class="text-xl font-bold text-gray-900 dark:text-white">Data Domisili</span>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Data Table Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-8 transition-colors duration-300">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center">
                    <i class="fas fa-city text-primary dark:text-primary-300 mr-2"></i>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Kabupaten/Kota</h2>
                </div>
                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" id="searchInput" class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm text-gray-900 dark:text-white" placeholder="Cari kabupaten atau provinsi...">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama</th>
                            <th scope="col" class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Latitude</th>
                            <th scope="col" class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Longitude</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Provinsi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <?php if (!empty($kabkota_data)): ?>
                            <?php foreach ($kabkota_data as $i => $data): ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"><?= $i + 1 ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($data['nama']) ?></div>
                                        <div class="md:hidden text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Koordinat: <?= htmlspecialchars($data['latitude']) ?>, <?= htmlspecialchars($data['longitude']) ?>
                                        </div>
                                    </td>
                                    <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($data['latitude']) ?></td>
                                    <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($data['longitude']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                            <?= htmlspecialchars($data['nama_provinsi']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="edit.php?id=<?= $data['id'] ?>" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-4 transition-colors duration-200">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                        <a href="#" onclick="return confirmDelete(<?= $data['id'] ?>)" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors duration-200">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada data yang tersedia
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Menampilkan <span id="countResults" class="font-medium"><?= count($kabkota_data) ?></span> kabupaten/kota
                </div>
            </div>
        </div>

        <!-- Add Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-colors duration-300">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <i class="fas fa-plus-circle text-primary dark:text-primary-300 mr-2"></i>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Data Kabupaten/Kota</h2>
                </div>
            </div>
            <div class="px-6 py-4">
                <form method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Kabupaten/Kota</label>
                            <input type="text" name="nama" id="nama" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-700 dark:text-white sm:text-sm transition-colors duration-200">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Latitude</label>
                                <input type="text" name="latitude" id="latitude" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-700 dark:text-white sm:text-sm transition-colors duration-200">
                            </div>
                            <div>
                                <label for="longitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Longitude</label>
                                <input type="text" name="longitude" id="longitude" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-700 dark:text-white sm:text-sm transition-colors duration-200">
                            </div>
                        </div>
                        <div>
                            <label for="provinsi_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Provinsi</label>
                            <select name="provinsi_id" id="provinsi_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-700 dark:text-white sm:text-sm transition-colors duration-200">
                                <?php foreach ($provinsi_data as $provinsi): ?>
                                    <option value="<?= $provinsi['id'] ?>"><?= htmlspecialchars($provinsi['nama']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 pt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-gray-800 transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>Simpan
                        </button>
                        <a href="../index.php" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-gray-800 transition-colors duration-200">
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