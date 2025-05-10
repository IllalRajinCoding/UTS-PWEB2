<?php
include '../config/koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Prepare data
    $kabkota_id = !empty($_POST['kabkota_id']) ? $_POST['kabkota_id'] : null;
    $pembina_id = !empty($_POST['pembina_id']) ? $_POST['pembina_id'] : null;
    
    // Insert into database
    $stmt = $koneksi->prepare("INSERT INTO umkm (
        nama, 
        modal, 
        pemilik, 
        alamat, 
        website, 
        email, 
        kabkota_id, 
        rating, 
        kategori_umkm_id, 
        pembina_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param(
        "ssssssiiii",
        $_POST['nama'],
        $_POST['modal'],
        $_POST['pemilik'],
        $_POST['alamat'],
        $_POST['website'],
        $_POST['email'],
        $kabkota_id,
        $_POST['rating'],
        $_POST['kategori_umkm_id'],
        $pembina_id
    );
    
    if ($stmt->execute()) {
        $_SESSION['message'] = 'Data UMKM berhasil ditambahkan!';
        header('Location: ../index.php');
        exit;
    } else {
        $_SESSION['error'] = 'Gagal menambahkan data UMKM: ' . $stmt->error;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
} else {
    header('Location: ../index.php');
    $_SESSION['error'] = 'Permintaan tidak valid!';
    exit;
}