<?php
include '../config/koneksi.php';
session_start();

class KabupatenKotaHandler {
    private $koneksi;
    
    public function __construct($koneksi) {
        $this->koneksi = $koneksi;
    }
    
    public function deleteKabupatenKota($id) {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID tidak valid!');
        }
        
        $query = mysqli_prepare($this->koneksi, "DELETE FROM kabkota WHERE id = ?");
        if (!$query) {
            throw new RuntimeException('Gagal menyiapkan query: ' . mysqli_error($this->koneksi));
        }
        
        mysqli_stmt_bind_param($query, 'i', $id);
        $result = mysqli_stmt_execute($query);
        
        if (!$result) {
            throw new RuntimeException('Gagal menghapus data: ' . mysqli_error($this->koneksi));
        }
        
        mysqli_stmt_close($query);
        return true;
    }
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
        $handler = new KabupatenKotaHandler($koneksi);
        $id = (int)$_GET['id'];
        
        if ($handler->deleteKabupatenKota($id)) {
            $_SESSION['success'] = 'Data berhasil dihapus!';
        }
        
        header('Location: ../form/kabkota.php');
        exit;
    } else {
        throw new RuntimeException('Permintaan tidak valid!');
    }
} catch (InvalidArgumentException $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: kabkota.php');
    exit;
} catch (RuntimeException $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: ../form/kabkota.php');
    exit;
} catch (Exception $e) {
    $_SESSION['error'] = 'Terjadi kesalahan: ' . $e->getMessage();
    header('Location: ../form/kabkota.php');
    exit;
}