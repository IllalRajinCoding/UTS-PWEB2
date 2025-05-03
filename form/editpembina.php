<?php
require_once '../config/koneksi.php';
session_start();

// Validasi koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

class PembinaHandler
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }

    public function getById($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidArgumentException('ID Pembina tidak valid!');
        }

        $stmt = mysqli_prepare($this->koneksi, "SELECT * FROM pembina WHERE id = ?");
        if (!$stmt) {
            throw new RuntimeException('Gagal menyiapkan query: ' . mysqli_error($this->koneksi));
        }

        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!$data) {
            throw new RuntimeException('Data Pembina tidak ditemukan!');
        }

        return $data;
    }

    public function update($id, $data)
    {
        $nama = mysqli_real_escape_string($this->koneksi, $data['nama']);
        $gender = mysqli_real_escape_string($this->koneksi, $data['gender']);
        $tgl_lahir = mysqli_real_escape_string($this->koneksi, $data['tgl_lahir']);
        $tmp_lahir = mysqli_real_escape_string($this->koneksi, $data['tmp_lahir']);
        $keahlian = mysqli_real_escape_string($this->koneksi, $data['keahlian']);

        $stmt = mysqli_prepare(
            $this->koneksi,
            "UPDATE pembina SET nama = ?, gender = ?, tgl_lahir = ?, tmp_lahir = ?, keahlian = ? WHERE id = ?"
        );

        if (!$stmt) {
            throw new RuntimeException('Gagal menyiapkan query: ' . mysqli_error($this->koneksi));
        }

        mysqli_stmt_bind_param($stmt, 'sssssi', $nama, $gender, $tgl_lahir, $tmp_lahir, $keahlian, $id);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if (!$result) {
            throw new RuntimeException('Gagal memperbarui data Pembina: ' . mysqli_error($this->koneksi));
        }

        return true;
    }
}

$pembina = new PembinaHandler($koneksi);
$pembina_data = null;

// Ambil data jika GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    try {
        $id = (int)$_GET['id'];
        $pembina_data = $pembina->getById($id);
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header('Location: ../form/pembina.php');
        exit;
    }
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    try {
        $requiredFields = ['nama', 'gender', 'tgl_lahir', 'tmp_lahir', 'keahlian'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Field $field wajib diisi.");
            }
        }

        $id = (int)$_POST['id'];
        if ($pembina->update($id, $_POST)) {
            $_SESSION['success'] = 'Data Pembina berhasil diperbarui!';
        }
        header('Location: ../form/pembina.php');
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        $pembina_data = $_POST;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Data Pembina</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <nav class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16 items-center">
                <button id="toggle" class="absolute top-4 right-4 p-2 rounded-full bg-gray-200 dark:bg-sky-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-sky-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" stroke="currentColor">
                        <path id="toggle-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>
                <div class="flex items-center">
                    <span class="text-xl font-bold text-gray-900 dark:text-white">Edit Data Pembina</span>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white"><i class="fas fa-edit mr-2"></i>Edit Data Pembina</h2>
            </div>
            <div class="px-6 py-4">

                <form method="post" class="space-y-6">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($pembina_data['id'] ?? '') ?>">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" required value="<?= htmlspecialchars($pembina_data['nama'] ?? '') ?>" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Kelamin</label>
                            <select name="gender" id="gender" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" <?= (isset($pembina_data['gender']) && $pembina_data['gender'] == 'L' ? 'selected' : '') ?>>Laki-Laki</option>
                                <option value="P" <?= (isset($pembina_data['gender']) && $pembina_data['gender'] == 'P' ? 'selected' : '') ?>>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label for="tgl_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" id="tgl_lahir" required value="<?= htmlspecialchars($pembina_data['tgl_lahir'] ?? '') ?>" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                        </div>
                        <div>
                            <label for="tmp_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tempat Lahir</label>
                            <input type="text" name="tmp_lahir" id="tmp_lahir" required value="<?= htmlspecialchars($pembina_data['tmp_lahir'] ?? '') ?>" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label for="keahlian" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keahlian</label>
                            <input type="text" name="keahlian" id="keahlian" required value="<?= htmlspecialchars($pembina_data['keahlian'] ?? '') ?>" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 pt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                        <a href="../form/pembina.php" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="../src/toggle.js"></script>
    <script src="../src/index.js"></script>
</body>

</html>