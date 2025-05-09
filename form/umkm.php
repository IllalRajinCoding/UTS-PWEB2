<?php
include '../config/koneksi.php';
session_start();

class UMKM
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }

    public function create($data)
    {
        $kabkota_id = isset($data['kabkota_id']) && $data['kabkota_id'] != '' ? $data['kabkota_id'] : null;

        $stmt = $this->koneksi->prepare("INSERT INTO umkm(nama, modal, pemilik, alamat, website, email, rating, kategori_umkm_id, kabkota_id, pembina_id) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param(
            "sssssssiii",
            $data['nama'],
            $data['modal'],
            $data['pemilik'],
            $data['alamat'],
            $data['website'],
            $data['email'],
            $data['rating'],
            $data['kategori_umkm_id'],
            $kabkota_id,
            $data['pembina_id']
        );

        $result = $stmt->execute();
        if (!$result) {
            error_log("Error inserting UMKM data: " . $stmt->error);
        }
        return $result;
    }

    public function getKategoriUMKM()
    {
        $query = "SELECT * FROM kategori_umkm ORDER BY nama ASC";
        $result = mysqli_query($this->koneksi, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getPembina()
    {
        $query = "SELECT * FROM pembina ORDER BY nama ASC";
        $result = mysqli_query($this->koneksi, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getKabKota()
    {
        $query = "SELECT * FROM kabkota ORDER BY nama ASC";
        $result = mysqli_query($this->koneksi, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function delete($id)
    {
        $stmt = $this->koneksi->prepare("DELETE FROM umkm WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        if (!$result) {
            error_log("Error deleting UMKM data: " . $stmt->error);
        }
        return $result;
    }

    public function readQuery()
    {
        $query = "SELECT u.*, k.nama as kategori, p.nama as pembina, kb.nama as kabkota FROM umkm u
                  LEFT JOIN kategori_umkm k ON u.kategori_umkm_id = k.id
                  LEFT JOIN pembina p ON u.pembina_id = p.id
                  LEFT JOIN kabkota kb ON u.kabkota_id = kb.id
                  ORDER BY u.nama ASC";
        $result = mysqli_query($this->koneksi, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

$umkm = new UMKM($koneksi);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if ($umkm->create($_POST)) {
        $_SESSION['message'] = 'Data UMKM berhasil ditambahkan!';
    } else {
        $_SESSION['message'] = 'Gagal menambahkan data UMKM!';
    }
    header('Location: umkm.php');
    exit;
}

$kategori_umkm = $umkm->getKategoriUMKM();
$pembina_data = $umkm->getPembina();
$kabkota_data = $umkm->getKabKota();
$data_umkm = $umkm->readQuery();

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($umkm->delete($id)) {
        $_SESSION['message'] = 'Data UMKM berhasil dihapus!';
    } else {
        $_SESSION['message'] = 'Gagal menghapus data UMKM!';
    }
    header('Location: umkm.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data UMKM</title>
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
                        danger: '#ef4444'
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
                <div class="flex items-center">
                    <span class="text-xl font-bold text-gray-900 dark:text-white">Data UMKM</span>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-8 transition-colors duration-300">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center">
                    <i class="fas fa-store text-primary dark:text-primary-300 mr-2"></i>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar UMKM</h2>
                </div>
                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" id="searchInput" class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm text-gray-900 dark:text-white" placeholder="Cari UMKM...">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama</th>
                            <th scope="col" class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Modal</th>
                            <th scope="col" class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pemilik</th>
                            <th scope="col" class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alamat</th>
                            <th scope="col" class="hidden lg:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Website</th>
                            <th scope="col" class="hidden lg:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rating</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kategori</th>
                            <th scope="col" class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kab/Kota</th>
                            <th scope="col" class="hidden lg:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pembina</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <?php if (!empty($data_umkm)): ?>
                            <?php foreach ($data_umkm as $i => $row): ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"><?= $i + 1 ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($row['nama']) ?></td>
                                    <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($row['modal']) ?></td>
                                    <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($row['pemilik']) ?></td>
                                    <td class="hidden md:table-cell px-6 py-4 whitespace-normal text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($row['alamat']) ?></td>
                                    <td class="hidden lg:table-cell px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400 hover:underline"><a href="<?= htmlspecialchars($row['website']) ?>" target="_blank"><?= htmlspecialchars($row['website']) ?></a></td>
                                    <td class="hidden lg:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($row['email']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($row['rating']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"><?= htmlspecialchars($row['kategori']) ?></td>
                                    <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($row['kabkota']) ?></td>
                                    <td class="hidden lg:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($row['pembina']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="edit_umkm.php?id=<?= $row['id'] ?>" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition-colors duration-200">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                            <button onclick="confirmDelete(<?= $row['id'] ?>)" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors duration-200">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada data UMKM yang tersedia
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Menampilkan <span id="countResults" class="font-medium"><?= count($data_umkm) ?></span> UMKM
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-colors duration-300 mt-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <i class="fas fa-plus-circle text-primary dark:text-primary-300 mr-2"></i>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Data UMKM</h2>
                </div>
            </div>
            <div class="px-6 py-4">
                <form method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama UMKM</label>
                            <input type="text" id="nama" name="nama" placeholder="Nama UMKM" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" required>
                        </div>
                        <div>
                            <label for="modal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Modal</label>
                            <input type="text" id="modal" name="modal" placeholder="Modal" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" required>
                        </div>
                        <div class="md:col-span-2">
                            <div>
                                <label for="pemilik" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pemilik</label>
                                <input type="text" id="pemilik" name="pemilik" placeholder="Kepemilikan" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" required>
                            </div>
                            <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                            <textarea id="alamat" name="alamat" placeholder="Alamat" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" rows="3" required></textarea>
                        </div>
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Website</label>
                            <input type="url" id="website" name="website" placeholder="Website" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" id="email" name="email" placeholder="Email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rating</label>
                            <input type="number" id="rating" name="rating" placeholder="Rating" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" min="1" max="5" step="0.1">
                        </div>
                        <div>
                            <label for="kategori_umkm_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                            <select id="kategori_umkm_id" name="kategori_umkm_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" required>
                                <option value="">Pilih Kategori</option>
                                <?php foreach ($kategori_umkm as $k): ?>
                                    <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="kabkota_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kab/Kota</label>
                            <select id="kabkota_id" name="kabkota_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" required>
                                <option value="">Pilih Kab/Kota</option>
                                <?php foreach ($kabkota_data as $k): ?>
                                    <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="pembina_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pembina</label>
                            <select id="pembina_id" name="pembina_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" required>
                                <option value="">Pilih Pembina</option>
                                <?php foreach ($pembina_data as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nama']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="pt-4 flex space-x-4">
                        <button name="submit" type="submit" class="px-4 py-2 bg-primary hover:bg-secondary text-white rounded-md shadow-sm flex items-center">
                            <i class="fas fa-save mr-2"></i>Simpan
                        </button>
                        <a href="../pages/dashboard.php" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md shadow-sm flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="../src/index.js"></script>
    <script>
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                window.location.href = '?delete=' + id;
            }
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                const nama = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const modal = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const alamat = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                const website = row.querySelector('td:nth-child(5) a')?.textContent.toLowerCase() || '';
                const email = row.querySelector('td:nth-child(6)').textContent.toLowerCase();
                const kategori = row.querySelector('td:nth-child(8)').textContent.toLowerCase();
                const kabkota = row.querySelector('td:nth-child(9)').textContent.toLowerCase();
                const pembina = row.querySelector('td:nth-child(10)').textContent.toLowerCase();

                if (
                    nama.includes(searchValue) ||
                    modal.includes(searchValue) ||
                    alamat.includes(searchValue) ||
                    website.includes(searchValue) ||
                    email.includes(searchValue) ||
                    kategori.includes(searchValue) ||
                    kabkota.includes(searchValue) ||
                    pembina.includes(searchValue)
                ) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('countResults').textContent = visibleCount;
        });
    </script>

    <?php if (isset($_SESSION['message'])) {
        echo "<script>alert('" . $_SESSION['message'] . "');</script>";
        unset($_SESSION['message']);
    } ?>
</body>

</html>