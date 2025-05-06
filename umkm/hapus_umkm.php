<?php
include 'koneksi.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM umkm WHERE id_umkm='$id'");
header("Location: data_umkm.php");
?>
