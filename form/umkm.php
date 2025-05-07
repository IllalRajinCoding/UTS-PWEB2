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

        $stmt = $this->koneksi->prepare("INSERT INTO umkm(nama, modal, alamat, website, email, rating, kategori_umkm_id, kabkota_id, pembina_id) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param(
            "ssssssiii",
            $data['nama'],
            $data['modal'],
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
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data UMKM</title>
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
                    <span class="text-xl font-bold text-gray-900 dark:text-white">Tambah Data UMKM</span>
                </div>
            </div>
        </div>
    </nav>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <?php
        $query = "SELECT u.*, k.nama as kategori, p.nama as pembina, kb.nama as kabkota FROM umkm u
              LEFT JOIN kategori_umkm k ON u.kategori_umkm_id = k.id
              LEFT JOIN pembina p ON u.pembina_id = p.id
              LEFT JOIN kabkota kb ON u.kabkota_id = kb.id
              ORDER BY u.nama ASC";
        $data_umkm = mysqli_query($koneksi, $query);
        ?>

        <div class="mt-10">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <i class="fas fa-database mr-2 text-primary"></i> Data UMKM Terdaftar
            </h2>
            <div class="overflow-auto rounded-lg shadow border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-left">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 uppercase">
                        <tr>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Modal</th>
                            <th class="px-4 py-3">Alamat</th>
                            <th class="px-4 py-3">Website</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Rating</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Kab/Kota</th>
                            <th class="px-4 py-3">Pembina</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <?php while ($row = mysqli_fetch_assoc($data_umkm)): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-4 py-2 text-gray-900 dark:text-white"><?= htmlspecialchars($row['nama']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['modal']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['alamat']) ?></td>
                                <td class="px-4 py-2"><a href="<?= htmlspecialchars($row['website']) ?>" class="text-primary hover:underline" target="_blank"><?= htmlspecialchars($row['website']) ?></a></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['email']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['rating']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['kategori']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['kabkota']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['pembina']) ?></td>
                                <td class="px-4 py-2">
                                    <div class="flex space-x-2">
                                        <!-- Edit Button -->
                                        <a href="edit_umkm.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <!-- Delete Button -->
                                        <button onclick="confirmDelete(<?= $row['id'] ?>)" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-colors duration-300 mt-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-store text-primary mr-2"></i> Form Tambah UMKM
                </h2>
            </div>
            <div class="px-6 py-4">
                <form method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- input fields -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama UMKM</label>
                            <input type="text" id="nama" name="nama" placeholder="Nama UMKM" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" required>
                        </div>
                        <div>
                            <label for="modal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Modal</label>
                            <input type="text" id="modal" name="modal" placeholder="Modal" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" required>
                        </div>
                        <div class="md:col-span-2">
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

    <script>
        // Delete confirmation function
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                window.location.href = 'delete_umkm.php?id=' + id;
            }
        }

        // Dark mode toggle
        const toggle = document.getElementById('toggle');
        toggle?.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
        });
    </script>

    <?php if (isset($_SESSION['message'])) {
        echo "<script>alert('" . $_SESSION['message'] . "');</script>";
        unset($_SESSION['message']);
    } ?>
</body>

</html>