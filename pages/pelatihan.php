<?php
include '../config/koneksi.php';
?>  

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pembina Pelatihan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        .gender-icon {
            font-size: 1.5rem;
            margin-right: 5px;
        }
        .male {
            color: #2575fc;
        }
        .female {
            color: #ff6b6b;
        }
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .table th {
            background-color: #6a11cb;
            color: white;
        }
        .badge-keahlian {
            background-color: #6a11cb;
            margin-right: 5px;
            margin-bottom: 5px;
        }
        .profile-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .age-badge {
            background-color: #2575fc;
        }
    </style>
</head>
<body>
    <div class="header text-center">
        <div class="container">
            <h1><i class="fas fa-chalkboard-teacher me-2"></i>Data Pembina Pelatihan</h1>
            <p class="lead">Daftar pembina beserta informasi lengkap mereka</p>
        </div>
    </div>

    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Total Pembina</h5>
                    </div>
                    <div class="card-body text-center">
                        <h2 class="display-4">
                            <?php 
                                $query_total = "SELECT COUNT(*) FROM pembina";
                                $result_total = mysqli_query($conn, $query_total);
                                $row_total = mysqli_fetch_array($result_total);
                                echo $row_total[0];
                            ?>
                        </h2>
                        <p class="text-muted">Total Pembina Terdaftar</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-venus-mars me-2"></i>Jenis Kelamin</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <i class="fas fa-male gender-icon male"></i>
                                <h4>
                                    <?php 
                                        $query_male = "SELECT COUNT(*) FROM pembina WHERE gender = 'L'";
                                        $result_male = mysqli_query($conn, $query_male);
                                        $row_male = mysqli_fetch_array($result_male);
                                        echo $row_male[0];
                                    ?>
                                </h4>
                                <p class="text-muted">Laki-laki</p>
                            </div>
                            <div class="col-6">
                                <i class="fas fa-female gender-icon female"></i>
                                <h4>
                                    <?php 
                                        $query_female = "SELECT COUNT(*) FROM pembina WHERE gender = 'P'";
                                        $result_female = mysqli_query($conn, $query_female);
                                        $row_female = mysqli_fetch_array($result_female);
                                        echo $row_female[0];
                                    ?>
                                </h4>
                                <p class="text-muted">Perempuan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-list me-2"></i>Daftar Pembina</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Foto</th>
                                <th>Nama Pembina</th>
                                <th>Jenis Kelamin</th>
                                <th>TTL</th>
                                <th>Usia</th>
                                <th>Keahlian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Hitung usia
                                $tgl_lahir = new DateTime($row['tgl_Jahir']);
                                $today = new DateTime();
                                $usia = $today->diff($tgl_lahir)->y;
                                
                                // Format tanggal lahir
                                $tgl_lahir_formatted = date('d M Y', strtotime($row['tgl_Jahir']));
                                
                                // Pisahkan keahlian
                                $keahlian_arr = explode(',', $row['keahlian']);
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($row['nama']); ?>&background=6a11cb&color=fff" alt="Foto Profil" class="profile-img">
                                </td>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td>
                                    <?php 
                                        if ($row['gender'] == 'L') {
                                            echo '<i class="fas fa-male gender-icon male"></i> Laki-laki';
                                        } else {
                                            echo '<i class="fas fa-female gender-icon female"></i> Perempuan';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($row['tmp_Jahir']) . ', ' . $tgl_lahir_formatted; ?>
                                </td>
                                <td>
                                    <span class="badge rounded-pill age-badge"><?php echo $usia; ?> tahun</span>
                                </td>
                                <td>
                                    <?php 
                                        foreach ($keahlian_arr as $keahlian) {
                                            echo '<span class="badge badge-keahlian rounded-pill">' . trim(htmlspecialchars($keahlian)) . '</span>';
                                        }
                                    ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; <?php echo date('Y'); ?> Sistem Pelatihan. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>

<?php
// Tutup koneksi database
mysqli_close($conn);
?>