<?php
include '../config/koneksi.php';
session_start();

class Pembina {
    private $koneksi;
    public function __construct($koneksi) {
        $this->koneksi = $koneksi;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembina</title>
    <link rel="stylesheet" href="../src/output.css">
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pembina</th>
                <th>Gender</th>
                <th>Tanggal Lahir</th>
                <th>Tempat Lahir</th>
                <th>Keahlian</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <thead>
            <?php foreach ($pembina as $row) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['nama']; ?></td>
                    <td><?php echo $row['gender'] == 'L' ? 'Laki-Laki' : 'perempuan'; ?></td>
                    <td><?php echo $row['tgl_lahir']; ?></td>
                    <td><?php echo $row['tmp_lahir']; ?></td>
                    <td><?php echo $row['keahlian']; ?></td>
                    <td>
                        <a href="">edit</a>
                        <a href="">delete</a>
                    </td>

                <?php endforeach; ?>
        </thead>
    </table>
</body>

</html>