<?php
include 'koneksi.php';

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM umkm WHERE id_umkm='$id'");
$data = mysqli_fetch_assoc($query);

if (isset($_POST['submit'])) {
    $nama = $_POST['nama_umkm'];
    $jenis = $_POST['jenis'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $id_pembina = $_POST['id_pembina'];

    $update = "UPDATE umkm SET 
                nama_umkm='$nama', 
                jenis='$jenis', 
                alamat='$alamat', 
                no_hp='$no_hp', 
                id_pembina='$id_pembina' 
                WHERE id_umkm='$id'";
    mysqli_query($conn, $update);
    header("Location: data_umkm.php");
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit UMKM</title></head>
<body>
<h2>Edit UMKM</h2>
<form method="post">
    <label>Nama UMKM:</label><br>
    <input type="text" name="nama_umkm" value="<?= $data['nama_umkm'] ?>" required><br>

    <label>Jenis:</label><br>
    <input type="text" name="jenis" value="<?= $data['jenis'] ?>" required><br>

    <label>Alamat:</label><br>
    <input type="text" name="alamat" value="<?= $data['alamat'] ?>" required><br>

    <label>No HP:</label><br>
    <input type="text" name="no_hp" value="<?= $data['no_hp'] ?>" required><br>

    <label>Pembina:</label><br>
    <select name="id_pembina" required>
        <option value="">-- Pilih Pembina --</option>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM pembina");
        while ($row = mysqli_fetch_assoc($result)) {
            $selected = ($row['id_pembina'] == $data['id_pembina']) ? 'selected' : '';
            echo "<option value='{$row['id_pembina']}' $selected>{$row['nama_pembina']}</option>";
        }
        ?>
    </select><br><br>

    <input type="submit" name="submit" value="Update">
</form>
</body>
</html>
