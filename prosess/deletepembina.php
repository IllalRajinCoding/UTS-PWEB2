<?php
require_once '../config/koneksi.php';
session_start();

// Validasi CSRF token
if (!isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Token keamanan tidak valid!';
    header('Location: ../form/pembina.php');
    exit;
}

class PembinaHandler
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }

    public function delete($id)
    {
        // Validasi ID
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidArgumentException('ID Pembina tidak valid!');
        }

        // Persiapkan statement
        $stmt = mysqli_prepare($this->koneksi, "DELETE FROM pembina WHERE id = ?");
        if (!$stmt) {
            throw new RuntimeException('Gagal menyiapkan query: ' . mysqli_error($this->koneksi));
        }

        // Bind parameter
        mysqli_stmt_bind_param($stmt, 'i', $id);

        // Eksekusi
        $result = mysqli_stmt_execute($stmt);

        // Tutup statement
        mysqli_stmt_close($stmt);

        if (!$result) {
            throw new RuntimeException('Gagal menghapus data Pembina: ' . mysqli_error($this->koneksi));
        }

        return true;
    }
}

try {
    // Validasi request
    if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['id'])) {
        throw new RuntimeException('Permintaan tidak valid!');
    }

    $handler = new PembinaHandler($koneksi);
    $id = (int)$_GET['id'];

    if ($handler->delete($id)) {
        $_SESSION['success'] = 'Data Pembina berhasil dihapus!';
    }

    header('Location: ../form/pembina.php');
    exit;
} catch (InvalidArgumentException $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: ../form/pembina.php?error=invalid_id');
    exit;
} catch (RuntimeException $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: ../form/pembina.php?error=database_error');
    exit;
} catch (Exception $e) {
    $_SESSION['error'] = 'Terjadi kesalahan sistem: ' . $e->getMessage();
    header('Location: ../form/pembina.php?error=system_error');
    exit;
}
