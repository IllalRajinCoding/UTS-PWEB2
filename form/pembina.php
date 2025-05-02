<?php
include '../config/koneksi.php';
session_start();

class Pembina
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }
    // implement query databases
    public function create($data)
    {
        $nama = mysqli_real_escape_string($this->koneksi, $data['nama']);
        $gender = mysqli_real_escape_string($this->koneksi, $data['gender' == 'L' ? 'Laki-Laki' : 'Perempuan']);
        $tgl_lahir = mysqli_real_escape_string($this->koneksi, $data['tgl_lahir']);
        $tmp_lahir = mysqli_real_escape_string($this->koneksi, $data['tmp_lahir']);
        $keahlian = mysqli_real_escape_string($this->koneksi, $data['keahlian']);

        $query = "INSERT INTO pembina (nama, gender, tgl_lahir, tmp_lahir, keahlian) VALUES ('$nama', '$gender', '$tgl_lahir', '$tmp_lahir', '$keahlian')";
        return mysqli_query($this->koneksi, $query);
    }

    public function readQuery()
    {
        $query = "SELECT * FROM pembina";
        $result = mysqli_query($this->koneksi, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

// create object with method post
$pembina = new Pembina($koneksi);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama'])) {
    if ($pembina->create($_POST)) {
        $_SESSION['message'] = 'Data berhasil ditambahkan!';
    } else {
        $_SESSION['message'] = 'Gagal menambahkan data!';
    }
}
// alert 
if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']);
}

$pembina_data = $pembina->readQuery();

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
            <?php foreach ($pembina_data as $row) : ?>
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
