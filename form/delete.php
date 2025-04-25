<?php
include '../config/koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    if ($id <= 0) {
        $_SESSION['error'] = 'ID tidak valid!';
        header('Location: kabkota.php');
        exit;
    }
    $query = mysqli_prepare($koneksi, "DELETE FROM kabkota WHERE id = ?");
    mysqli_stmt_bind_param($query, 'i', $id);
    $result = mysqli_stmt_execute($query);

    if ($result) {
        $_SESSION['success'] = 'Data berhasil dihapus!';
    } else {
        $_SESSION['error'] = 'Gagal menghapus data: ' . mysqli_error($koneksi);
    }

    mysqli_stmt_close($query);

    header('Location: kabkota.php');
    exit;
} else {
    $_SESSION['error'] = 'Permintaan tidak valid!';
    header('Location: kabkota.php');
    exit;
}
