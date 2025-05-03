<?php
include '../config/koneksi.php';
session_start();

class Pembina
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }

    public function createD($data)
    {
        $nama = mysqli_real_escape_string($this->koneksi, $data['nama']);
        $gender = mysqli_real_escape_string($this->koneksi, $data['gender']);
        $tgl_lahir = mysqli_real_escape_string($this->koneksi, $data['tgl_lahir']);
        $tmp_lahir = mysqli_real_escape_string($this->koneksi, $data['tmp_lahir']);
        $keahlian = mysqli_real_escape_string($this->koneksi, $data['keahlian']);

        $query = "INSERT INTO pembina(nama, gender, tgl_lahir, tmp_lahir, keahlian) VALUES ('$nama', '$gender', '$tgl_lahir', '$tmp_lahir', '$keahlian')";

        return mysqli_query($this->koneksi, $query);
    }

    public function readQuery()
    {
        $query = "SELECT * FROM pembina ORDER BY nama ASC";
        $result = mysqli_query($this->koneksi, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

$pembina = new Pembina($koneksi);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama'])) {
    if ($pembina->createD($_POST)) {
        $_SESSION['message'] = 'Data berhasil ditambahkan!';
    } else {
        $_SESSION['message'] = 'Gagal menambahkan data!';
    }
    header('Location: pembina.php');
    exit;
}

$pembina_data = $pembina->readQuery();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pembina</title>
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
                    <span class="text-xl font-bold text-gray-900 dark:text-white">Data Pembina</span>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Data Table Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-8 transition-colors duration-300">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center">
                    <i class="fas fa-users text-primary dark:text-primary-300 mr-2"></i>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Pembina</h2>
                </div>
                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" id="searchInput" class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm text-gray-900 dark:text-white" placeholder="Cari pembina...">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Pembina</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Gender</th>
                            <th scope="col" class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Lahir</th>
                            <th scope="col" class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tempat Lahir</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Keahlian</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <?php if (!empty($pembina_data)): ?>
                            <?php foreach ($pembina_data as $i => $row): ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"><?= $i + 1 ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($row['nama']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?= $row['gender'] == 'L' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200' : 'bg-pink-100 dark:text-pink-200' ?>">
                                            <?= $row['gender'] == 'L' ? 'Laki-Laki' : 'Perempuan' ?>
                                        </span>
                                    </td>
                                    <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($row['tgl_lahir']) ?></td>
                                    <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($row['tmp_lahir']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"><?= htmlspecialchars($row['keahlian']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="editpembina.php?id=<?= $row['id'] ?>" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-4 transition-colors duration-200">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                        <a href="#" onclick="return confirmDelete(<?= $row['id'] ?>)" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors duration-200">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada data yang tersedia
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Menampilkan <span id="countResults" class="font-medium"><?= count($pembina_data) ?></span> pembina
                </div>
            </div>
        </div>

        <!-- Add Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-colors duration-300">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <i class="fas fa-plus-circle text-primary dark:text-primary-300 mr-2"></i>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Data Pembina</h2>
                </div>
            </div>
            <div class="px-6 py-4">
                <form method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-700 dark:text-white sm:text-sm transition-colors duration-200">
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Kelamin</label>
                            <select name="gender" id="gender" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-700 dark:text-white sm:text-sm transition-colors duration-200">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-Laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label for="tgl_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" id="tgl_lahir" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-700 dark:text-white sm:text-sm transition-colors duration-200">
                        </div>
                        <div>
                            <label for="tmp_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tempat Lahir</label>
                            <input type="text" name="tmp_lahir" id="tmp_lahir" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-700 dark:text-white sm:text-sm transition-colors duration-200">
                        </div>
                        <div class="md:col-span-2">
                            <label for="keahlian" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keahlian</label>
                            <input type="text" name="keahlian" id="keahlian" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-700 dark:text-white sm:text-sm transition-colors duration-200">
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 pt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-gray-800 transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>Simpan
                        </button>
                        <a href="../pages/dashboard.php" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-gray-800 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="../src/index.js"></script>
    <script src="../src/toggle.js"></script>
    <script>
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                window.location.href = '../prosess/deletepembina.php?id=' + id;
            }
            return false;
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                const nama = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const keahlian = row.querySelector('td:nth-child(6)').textContent.toLowerCase();

                if (nama.includes(searchValue) || keahlian.includes(searchValue)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('countResults').textContent = visibleCount;
        });
    </script>
</body>

<?php
if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']);
}
?>

</html>