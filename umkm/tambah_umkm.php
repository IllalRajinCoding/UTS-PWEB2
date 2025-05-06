<?php
include 'koneksi.php';

if (isset($_POST['submit'])) {
    $nama = $_POST['nama_umkm'];
    $jenis = $_POST['jenis'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $id_pembina = $_POST['id_pembina'];

    $query = "INSERT INTO umkm (nama_umkm, jenis, alamat, no_hp, id_pembina) 
              VALUES ('$nama', '$jenis', '$alamat', '$no_hp', '$id_pembina')";
    mysqli_query($conn, $query);
    header("Location: data_umkm.php");
}
?>

<!DOCTYPE html>
<html>
<head><title>Tambah UMKM</title></head>
<body>
<h2>Tambah UMKM</h2>
<form method="post">
    <label>Nama UMKM:</label><br>
    <input type="text" name="nama_umkm" required><br>

    <label>Jenis:</label><br>
    <input type="text" name="jenis" required><br>

    <label>Alamat:</label><br>
    <input type="text" name="alamat" required><br>

    <label>No HP:</label><br>
    <input type="text" name="no_hp" required><br>

    <label>Pembina:</label><br>
    <select name="id_pembina" required>
        <option value="">-- Pilih Pembina --</option>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM pembina");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='{$row['id_pembina']}'>{$row['nama_pembina']}</option>";
        }
        ?>
    </select><br><br>

    <input type="submit" name="submit" value="Simpan">
</form>
</body>
</html>
