<?php
include 'koneksi.php';

$query = "SELECT umkm.*, pembina.nama_pembina FROM umkm 
          LEFT JOIN pembina ON umkm.id_pembina = pembina.id_pembina";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data UMKM</title>
</head>
<body>
    <h2>Data UMKM</h2>
    <a href="tambah_umkm.php">Tambah UMKM</a>
    <table border="1">
        <tr>
            <th>No</th>
            <th>Nama UMKM</th>
            <th>Jenis</th>
            <th>Alamat</th>
            <th>No HP</th>
            <th>Pembina</th>
            <th>Aksi</th>
        </tr>
        <?php
        $no = 1;
        while($row = mysqli_fetch_assoc($result)){
            echo "<tr>
                <td>{$no}</td>
                <td>{$row['nama_umkm']}</td>
                <td>{$row['jenis']}</td>
                <td>{$row['alamat']}</td>
                <td>{$row['no_hp']}</td>
                <td>{$row['nama_pembina']}</td>
                <td>
                    <a href='edit_umkm.php?id={$row['id_umkm']}'>Edit</a> |
                    <a href='hapus_umkm.php?id={$row['id_umkm']}' onclick='return confirm(\"Yakin ingin hapus?\")'>Hapus</a>
                </td>
            </tr>";
            $no++;
        }
        ?>
    </table>
</body>
</html>
