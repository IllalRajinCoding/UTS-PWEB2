<?php
session_start();
require_once '../config/koneksi.php';

// Check if ID parameter exists
if (!isset($_GET['id'])) {
    $_SESSION['message'] = "ID UMKM tidak valid";
    header("Location: umkm.php");
    exit();
}

$id = $_GET['id'];

// Fetch UMKM data
$query = "SELECT u.*, k.nama as kategori, p.nama as pembina, kb.nama as kabkota FROM umkm u
          LEFT JOIN kategori_umkm k ON u.kategori_umkm_id = k.id
          LEFT JOIN pembina p ON u.pembina_id = p.id
          LEFT JOIN kabkota kb ON u.kabkota_id = kb.id
          WHERE u.id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$umkm = mysqli_fetch_assoc($result);

if (!$umkm) {
    $_SESSION['message'] = "Data UMKM tidak ditemukan";
    header("Location: umkm.php");
    exit();
}

// Fetch dropdown data
$kategori_umkm = mysqli_query($koneksi, "SELECT * FROM kategori_umkm ORDER BY nama ASC");
$kabkota_data = mysqli_query($koneksi, "SELECT * FROM kabkota ORDER BY nama ASC");
$pembina_data = mysqli_query($koneksi, "SELECT * FROM pembina ORDER BY nama ASC");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $modal = mysqli_real_escape_string($koneksi, $_POST['modal']);
    $pemilik = mysqli_real_escape_string($koneksi, $_POST['pemilik']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $website = mysqli_real_escape_string($koneksi, $_POST['website']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $rating = mysqli_real_escape_string($koneksi, $_POST['rating']);
    $kategori_umkm_id = mysqli_real_escape_string($koneksi, $_POST['kategori_umkm_id']);
    $kabkota_id = mysqli_real_escape_string($koneksi, $_POST['kabkota_id']);
    $pembina_id = mysqli_real_escape_string($koneksi, $_POST['pembina_id']);

    $update_query = "UPDATE umkm SET 
                    nama = ?,
                    modal = ?,
                    pemilik = ?,
                    alamat = ?,
                    website = ?,
                    email = ?,
                    rating = ?,
                    kategori_umkm_id = ?,
                    kabkota_id = ?,
                    pembina_id = ?
                    WHERE id = ?";

    $stmt = mysqli_prepare($koneksi, $update_query);
    mysqli_stmt_bind_param($stmt, "sssssssiiii", $nama, $modal, $pemilik, $alamat, $website, $email, $rating, $kategori_umkm_id, $kabkota_id, $pembina_id, $id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Data UMKM berhasil diperbarui";
        header("Location: umkm.php");
        exit();
    } else {
        $_SESSION['message'] = "Gagal memperbarui data UMKM: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data UMKM</title>
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
                    <span class="text-xl font-bold text-gray-900 dark:text-white">Edit Data UMKM</span>
                </div>
            </div>
        </div>
    </nav>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-colors duration-300">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-edit text-primary mr-2"></i> Form Edit UMKM
                </h2>
            </div>
            <div class="px-6 py-4">
                <form method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- input fields -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama UMKM</label>
                            <input type="text" id="nama" name="nama" placeholder="Nama UMKM"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white"
                                value="<?= htmlspecialchars($umkm['nama']) ?>" required>
                        </div>
                        <div>
                            <label for="modal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Modal</label>
                            <input type="text" id="modal" name="modal" placeholder="Modal"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white"
                                value="<?= htmlspecialchars($umkm['modal']) ?>" required>
                        </div>
                        <div class="md:col-span-2">
                            <div>
                                <label for="pemilik" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pemilik</label>
                                <input type="text" id="pemilik" name="pemilik" placeholder="Kepemilikan"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white"
                                    value="<?= htmlspecialchars($umkm['pemilik']) ?>" required>
                            </div>
                            <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                            <textarea id="alamat" name="alamat" placeholder="Alamat"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white"
                                rows="3" required><?= htmlspecialchars($umkm['alamat']) ?></textarea>
                        </div>
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Website</label>
                            <input type="url" id="website" name="website" placeholder="Website"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white"
                                value="<?= htmlspecialchars($umkm['website']) ?>">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" id="email" name="email" placeholder="Email"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white"
                                value="<?= htmlspecialchars($umkm['email']) ?>">
                        </div>
                        <div>
                            <label for="rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rating</label>
                            <input type="number" id="rating" name="rating" placeholder="Rating"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white"
                                min="1" max="5" step="0.1" value="<?= htmlspecialchars($umkm['rating']) ?>">
                        </div>
                        <div>
                            <label for="kategori_umkm_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                            <select id="kategori_umkm_id" name="kategori_umkm_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" required>
                                <option value="">Pilih Kategori</option>
                                <?php while ($kategori = mysqli_fetch_assoc($kategori_umkm)): ?>
                                    <option value="<?= $kategori['id'] ?>" <?= $kategori['id'] == $umkm['kategori_umkm_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($kategori['nama']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <label for="kabkota_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kab/Kota</label>
                            <select id="kabkota_id" name="kabkota_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" required>
                                <option value="">Pilih Kab/Kota</option>
                                <?php while ($kabkota = mysqli_fetch_assoc($kabkota_data)): ?>
                                    <option value="<?= $kabkota['id'] ?>" <?= $kabkota['id'] == $umkm['kabkota_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($kabkota['nama']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <label for="pembina_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pembina</label>
                            <select id="pembina_id" name="pembina_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" required>
                                <option value="">Pilih Pembina</option>
                                <?php while ($pembina = mysqli_fetch_assoc($pembina_data)): ?>
                                    <option value="<?= $pembina['id'] ?>" <?= $pembina['id'] == $umkm['pembina_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($pembina['nama']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="pt-4 flex space-x-4">
                        <button name="submit" type="submit" class="px-4 py-2 bg-primary hover:bg-secondary text-white rounded-md shadow-sm flex items-center">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                        <a href="umkm.php" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md shadow-sm flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
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