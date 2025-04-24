<?php
include '../config/koneksi.php';


if (isset($_POST['nama'])) {
    $nama = $_POST['nama'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $provinsi_id = $_POST['provinsi_id'];

    $query = mysqli_query($koneksi, "INSERT INTO kabkota (nama, latitude, longitude, provinsi_id) VALUES ('$nama', '$latitude', '$longitude', '$provinsi_id')");

    if ($query) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location.href='kabkota.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data!'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domisili</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-light: #e9efff;
            --secondary-color: #3f37c9;
            --text-color: #333;
            --light-text: #555;
            --border-color: #e0e0e0;
            --bg-color: #f8f9fa;
            --card-bg: #ffffff;
            --hover-bg: #f3f4f6;
            --radius: 8px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            line-height: 1.6;
        }

        .navbar {
            background-color: var(--card-bg);
            box-shadow: var(--shadow-sm);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 92%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
        }

        .logo i {
            margin-right: 10px;
        }

        .container {
            width: 92%;
            max-width: 1200px;
            margin: 25px auto;
        }

        .card {
            background-color: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            transition: var(--transition);
        }

        .card-header {
            padding: 20px 25px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-color);
            margin: 0;
            display: flex;
            align-items: center;
        }

        .card-title i {
            color: var(--primary-color);
            margin-right: 12px;
        }

        .search-container {
            display: flex;
            max-width: 500px;
            position: relative;
            flex-grow: 1;
        }

        .search-input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            font-size: 0.9rem;
            transition: var(--transition);
            padding-left: 40px;
            outline: none;
        }

        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--light-text);
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 16px 20px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background-color: var(--bg-color);
            font-weight: 600;
            color: var(--light-text);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        tr:hover {
            background-color: var(--hover-bg);
        }

        td {
            font-size: 0.95rem;
        }

        .province-badge {
            background-color: var(--primary-light);
            color: var(--primary-color);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            display: inline-block;
            font-weight: 500;
        }

        .no-results {
            text-align: center;
            padding: 40px 20px;
            color: var(--light-text);
        }

        .card-footer {
            padding: 15px 25px;
            background-color: var(--bg-color);
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .results-count {
            font-size: 0.9rem;
            color: var(--light-text);
        }

        .map-link {
            color: var(--primary-color);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: var(--transition);
        }

        .map-link:hover {
            color: var(--secondary-color);
        }

        .map-link i {
            margin-right: 5px;
        }

        .pagination {
            display: flex;
            list-style: none;
            gap: 5px;
        }

        .page-item {
            display: inline-block;
        }

        .page-link {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            color: var(--text-color);
            text-decoration: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
        }

        .page-link:hover,
        .page-link.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .responsive-info {
            display: none;
        }

        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-container {
                width: 100%;
                margin-top: 10px;
            }

            th,
            td {
                padding: 12px 15px;
            }

            .hide-mobile {
                display: none;
            }

            .responsive-info {
                display: block;
                font-size: 0.8rem;
                margin-top: 5px;
                color: var(--light-text);
            }

            .card-footer {
                flex-direction: column;
                align-items: flex-start;
            }

            .pagination {
                margin-top: 10px;
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .card-title {
                font-size: 1.2rem;
            }

            th,
            td {
                padding: 10px 12px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <i class="fas fa-map-marker-alt"></i>
                <span>Data Domisili</span>
            </div>
        </div>
    </nav>

    <main class="container">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <i class="fas fa-city"></i>
                    Daftar Kabupaten/Kota
                </h1>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="searchInput" class="search-input" placeholder="Cari kabupaten atau provinsi...">
                </div>
            </div>

            <div class="table-responsive">
                <table id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th class="hide-mobile">Latitude</th>
                            <th class="hide-mobile">Longitude</th>
                            <th>Provinsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($koneksi, "SELECT kab.*, prov.nama AS nama_provinsi FROM kabkota kab LEFT JOIN provinsi prov ON kab.provinsi_id = prov.id ORDER BY prov.nama ASC, kab.nama ASC");
                        $i = 1;
                        if (mysqli_num_rows($query) > 0) {
                            while ($data = mysqli_fetch_array($query)) {
                        ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td>
                                        <?= htmlspecialchars($data['nama']); ?>
                                        <div class="responsive-info">
                                            <small>Koordinat: <?= htmlspecialchars($data['latitude']); ?>, <?= htmlspecialchars($data['longitude']); ?></small>
                                        </div>
                                    </td>
                                    <td class="hide-mobile"><?= htmlspecialchars($data['latitude']); ?></td>
                                    <td class="hide-mobile"><?= htmlspecialchars($data['longitude']); ?></td>
                                    <td><span class="province-badge"><?= htmlspecialchars($data['nama_provinsi']); ?></span></td>
                                    <td>
                                        <a href="https://www.google.com/maps?q=<?= $data['latitude']; ?>,<?= $data['longitude']; ?>" target="_blank" class="map-link">
                                            <i class="fas fa-map-marked-alt"></i> Lihat Peta
                                        </a>
                                    </td>
                                </tr>
                            <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="6" class="no-results">Tidak ada data yang tersedia</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                <div class="results-count">
                    Menampilkan <span id="countResults"><?= mysqli_num_rows($query); ?></span> kabupaten/kota
                </div>
            </div>
        </div>
    </main>

    <main class="container">
        <div>
            <div>
                <h1>Tambah Data Kabupaten/Kota</h1>
            </div>
            <div>
                <form action="" method="post">
                    <div>
                        <label for="nama">Nama Kabupaten/Kota</label>
                        <input type="text" name="nama" id="nama" required>
                    </div>
                    <div>
                        <label for="latitude">Latitude</label>
                        <input type="text" name="latitude" id="latitude" required>
                    </div>
                    <div>
                        <label for="longitude">Longitude</label>
                        <input type="text" name="longitude" id="longitude" required>
                    </div>
                    <div>
                        <label for="provinsi_id">Provinsi</label>
                        <select name="provinsi_id" id="provinsi_id" required>
                            <?php
                            $query_provinsi = mysqli_query($koneksi, "SELECT * FROM provinsi ORDER BY nama ASC");
                            while ($data_provinsi = mysqli_fetch_array($query_provinsi)) {
                            ?>
                                <option value="<?= $data_provinsi['id']; ?>"><?= htmlspecialchars($data_provinsi['nama']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit">Simpan</button>
                    <div>
                        <a href="index.php">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const dataTable = document.getElementById('dataTable');
        const countResults = document.getElementById('countResults');

        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = dataTable.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            let visibleCount = 0;

            for (let i = 0; i < rows.length; i++) {
                const nameCell = rows[i].getElementsByTagName('td')[1];
                const provinceCell = rows[i].getElementsByTagName('td')[4];

                if (nameCell && provinceCell) {
                    const nameText = nameCell.textContent || nameCell.innerText;
                    const provinceText = provinceCell.textContent || provinceCell.innerText;

                    if (nameText.toLowerCase().includes(searchTerm) ||
                        provinceText.toLowerCase().includes(searchTerm)) {
                        rows[i].style.display = '';
                        visibleCount++;
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }

            countResults.textContent = visibleCount;
        });
    </script>
</body>

</html>