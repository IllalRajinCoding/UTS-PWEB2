<?php
$localhost = "localhost";
$username = "root";
$password = "";
$database = "umkm";

$koneksi = mysqli_connect($localhost, $username, $password, $database) or die("Koneksi gagal: " . mysqli_connect_error());
