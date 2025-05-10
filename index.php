<?php
include './config/koneksi.php';
class umkmDisplay
{
  private $koneksi;

  public function __construct($koneksi)
  {
    $this->koneksi = $koneksi;
  }

  public function getUmkm()
  {
    $query = "SELECT * FROM umkm";
    $result = mysqli_query($this->koneksi, $query);
    $umkm = [];
    while ($row = mysqli_fetch_assoc($result)) {
      $umkm[] = $row;
    }
    return $umkm;
  }
}

$umkmData = new umkmDisplay($koneksi);
$listumkm = $umkmData->getUmkm();

class KabupatenKota
{
  private $koneksi;

  public function __construct($koneksi)
  {
    $this->koneksi = $koneksi;
  }

  public function getProvinsiList()
  {
    $query = "SELECT * FROM provinsi ORDER BY nama ASC";
    $result = mysqli_query($this->koneksi, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
  }
}

$kabupatenKota = new KabupatenKota($koneksi);
$provinsi_data = $kabupatenKota->getProvinsiList();

?>




<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Platform UMKM Indonesia untuk memajukan usaha kecil dan menengah">
  <title>Direktori UMKM Indonesia</title>

  <!-- Preconnect for external resources -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://cdnjs.cloudflare.com">
  <link rel="preconnect" href="https://cdn.amcharts.com">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#4D55CC',
            secondary: '#2DAA9E',
            accent: '#f59e0b',
            dark: '#1f2937',
            light: '#f3f4f6',
          },
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
          },
          transitionProperty: {
            'transform-shadow': 'transform, box-shadow',
          }
        }
      }
    }
  </script>

  <!-- amCharts Resources -->
  <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/geodata/indonesiaLow.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

  <style>
    /* Map Styles */
    #indonesia-map {
      width: 100%;
      height: 100%;
      min-height: 400px;
      background-color: theme('colors.gray.100');
    }

    @media (min-width: 768px) {
      #indonesia-map {
        min-height: 500px;
      }
    }

    /* Map Container */
    .map-container {
      position: relative;
      height: 100%;
      overflow: hidden;
      border-radius: 0.75rem;
    }

    /* Loading Indicator */
    .loading-indicator {
      position: absolute;
      inset: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: rgba(255, 255, 255, 0.9);
      z-index: 10;
      backdrop-filter: blur(2px);
    }

    /* Province Card Animation */
    .province-item {
      transition: transform-shadow 0.3s ease;
    }

    .province-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
        0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    /* Focus Styles for Accessibility */
    button:focus,
    a:focus {
      outline: 2px solid #2563eb;
      /* Replace with the actual primary color value */
      outline-offset: 2px;
    }
  </style>
</head>

<body class="font-sans bg-gray-50 text-gray-800">
  <!-- Navbar -->
  <nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <!-- Logo -->
        <div class="flex-shrink-0 flex items-center">
          <img src="./src/assets/logo.png" alt="Logo" class="h-10 w-10 rounded-md">
          <span class="ml-2 text-xl font-semibold text-primary">UMKM<span class="text-accent">Indonesia</span></span>
        </div>

        <!-- Desktop menu -->
        <div class="hidden md:block">
          <div class="ml-10 flex items-center space-x-4">
            <a href="" class="text-dark hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">Beranda</a>
            <a href="#produk" class="text-dark hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">Produk</a>
            <a href="#lokasi" class="text-dark hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">Lokasi</a>
            <a href="./pages/pelatihan.php" class="text-dark hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">Pelatihan</a>
            <a href="./pages/login.php" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition-all text-sm font-medium shadow hover:shadow-md">
              <i class="fas fa-user mr-1"></i>Login
            </a>
          </div>
        </div>

        <!-- Mobile menu button -->
        <div class="md:hidden flex items-center">
          <button id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-800 hover:text-primary focus:outline-none transition-colors">
            <span class="sr-only">Open main menu</span>
            <!-- Hamburger icon -->
            <svg id="menu-open-icon" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <!-- Close icon (hidden by default) -->
            <svg id="menu-close-icon" class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu (hidden by default) -->
    <div id="mobile-menu" class="md:hidden hidden origin-top transition-all duration-300 ease-out">
      <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white shadow-lg">
        <a href="" class="block px-3 py-2 rounded-md text-base font-medium text-dark hover:text-primary hover:bg-gray-50 transition-colors">Beranda</a>
        <a href="#produk" class="block px-3 py-2 rounded-md text-base font-medium text-dark hover:text-primary hover:bg-gray-50 transition-colors">Produk</a>
        <a href="#lokasi" class="block px-3 py-2 rounded-md text-base font-medium text-dark hover:text-primary hover:bg-gray-50 transition-colors">Lokasi</a>
        <a href="./pages/pelatihan.php" class="block px-3 py-2 rounded-md text-base font-medium text-dark hover:text-primary hover:bg-gray-50 transition-colors">Pelatihan</a>
        <a href="./pages/login.php" class="block w-full text-center bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition-colors text-base font-medium mt-2">
          <i class="fas fa-user mr-1"></i>Login
        </a>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="relative bg-gradient-to-r from-primary to-secondary text-white overflow-hidden">
    <div class="absolute inset-0 opacity-10">
      <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1469&q=80')] bg-cover bg-center"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-24 md:py-32 relative z-10">
      <div class="max-w-3xl mx-auto text-center animate-fade-in">
        <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
          Temukan <span class="text-accent">UMKM</span> di Seluruh Indonesia
        </h1>
        <p class="text-xl md:text-2xl mb-8 opacity-90">
          Platform direktori UMKM terintegrasi untuk menemukan produk lokal berkualitas dari berbagai provinsi dan kabupaten/kota di Indonesia.
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
          <a href="#produk" class="bg-accent hover:bg-yellow-500 text-dark font-bold px-8 py-4 rounded-lg transition transform hover:scale-105 shadow-lg">
            Jelajahi Produk <i class="fas fa-arrow-right ml-2"></i>
          </a>
          <a href="#umkm" class="bg-white hover:bg-gray-100 text-primary font-bold px-8 py-4 rounded-lg transition transform hover:scale-105 shadow-lg">
            Daftarkan UMKM <i class="fas fa-store ml-2"></i>
          </a>
        </div>
      </div>
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-16 bg-white transform skew-y-1 -mb-8 z-0"></div>
  </section>

  <!-- Stats Section -->
  <section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
        <div class="bg-light p-6 rounded-xl shadow-sm animate-fade-in delay-100">
          <div class="text-4xl font-bold text-primary mb-2">34</div>
          <div class="text-gray-600">Provinsi</div>
        </div>
        <div class="bg-light p-6 rounded-xl shadow-sm animate-fade-in delay-200">
          <div class="text-4xl font-bold text-primary mb-2">514</div>
          <div class="text-gray-600">Kabupaten/Kota</div>
        </div>
        <div class="bg-light p-6 rounded-xl shadow-sm animate-fade-in delay-300">
          <div class="text-4xl font-bold text-primary mb-2">65K+</div>
          <div class="text-gray-600">UMKM Terdaftar</div>
        </div>
        <div class="bg-light p-6 rounded-xl shadow-sm animate-fade-in delay-300">
          <div class="text-4xl font-bold text-primary mb-2">24/7</div>
          <div class="text-gray-600">Layanan Dukungan</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Location Map Section -->
  <section id="lokasi" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold text-dark mb-4">Jelajahi UMKM Berdasarkan <span class="text-primary">Lokasi</span></h2>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
          Temukan produk UMKM dari berbagai daerah di seluruh Indonesia.
        </p>
      </div>

      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-3">
          <!-- Map Visualization -->
          <div class="lg:col-span-2 p-6">
            <div class="map-container h-full min-h-[400px]">
              <div id="indonesia-map"></div>
              <div class="loading-indicator">
                <div class="text-center p-4 bg-white bg-opacity-90 rounded-lg shadow-sm">
                  <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary mx-auto mb-4"></div>
                  <p class="text-gray-700 font-medium">Memuat peta interaktif...</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Location List -->
          <div class="bg-gray-50 p-6 border-t lg:border-t-0 lg:border-l border-gray-200">
            <h3 class="text-xl font-bold text-dark mb-4">Provinsi Populer</h3>
            <div class="space-y-3 max-h-[360px] overflow-y-auto pr-2" id="province-list">
              <!-- Province items will be added by JavaScript -->
            </div>

            <div class="mt-6">
              <a href="#" class="text-primary font-medium hover:underline flex items-center">
                Lihat Semua Provinsi <i class="fas fa-chevron-right ml-2"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Featured Products -->
  <section id="produk" class="py-16 bg-white">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold text-dark mb-4">Daftar <span class="text-primary">UMKM</span></h2>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
          Temukan berbagai UMKM berkualitas dari seluruh Indonesia.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($listumkm as $umkm) : ?>
          <div class="bg-white rounded-lg shadow-lg  hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="p-6">
              <h3 class="text-x font-semibold text-gray-900 mb-2"><?= htmlspecialchars($umkm['nama']) ?></h3>
              <div>
                <img src="./src/assets/umkm.jpeg" alt="">
              </div>
              <p class="text-sm text-gray-500 mb-2">Pemilik: <?= htmlspecialchars($umkm['pemilik']) ?></p>
              <p class="text-sm text-gray-500 mb-2">Modal: Rp <?= number_format($umkm['modal'], 0, ',', '.') ?></p>
              <p class="text-sm text-gray-500 mb-2">Alamat: <?= htmlspecialchars($umkm['alamat']) ?></p>
              <div class="flex items-center mb-2">
                <?php
                $rating = $umkm['rating'] ?? 0;
                for ($i = 1; $i <= 5; $i++) {
                  if ($i <= floor($rating)) {
                    echo '<i class="fas fa-star text-yellow-400"></i>';
                  } elseif ($i == ceil($rating) && $rating != floor($rating)) {
                    echo '<i class="fas fa-star-half-alt text-yellow-400"></i>';
                  } else {
                    echo '<i class="far fa-star text-yellow-400"></i>';
                  }
                }
                ?>
                <span class="ml-1 text-xs text-gray-500">(<?= $rating ?>)</span>
              </div>
              <?php if (!empty($umkm['website'])) : ?>
                <a href="<?= htmlspecialchars($umkm['website']) ?>" target="_blank" class="text-sm text-blue-500 hover:underline block mb-2">Website</a>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- UMKM Registration Section -->
  <section id="umkm" class="py-16 md:py-24 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-800 mb-4 leading-tight">
          Daftarkan <span class="text-primary">UMKM</span> Anda
        </h2>
        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
          Bergabunglah dengan platform kami untuk menjangkau lebih banyak pelanggan dan mengembangkan bisnis Anda.
        </p>
      </div>

      <!-- Two Column Layout -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 xl:gap-12 items-start">
        <!-- Benefits Column -->
        <div class="animate-fade-in order-2 lg:order-1">
          <div class="bg-primary/5 p-6 sm:p-8 rounded-2xl border border-primary/10 shadow-sm hover:shadow-md transition-shadow">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Keuntungan Bergabung</h3>
            <ul class="space-y-5">
              <li class="flex items-start gap-4 p-3 rounded-lg hover:bg-white/50 transition">
                <div class="flex-shrink-0 mt-0.5">
                  <div class="h-7 w-7 rounded-full bg-primary text-white flex items-center justify-center shadow-sm">
                    <i class="fas fa-check text-xs"></i>
                  </div>
                </div>
                <div>
                  <p class="text-gray-700">
                    <span class="font-semibold text-gray-800">Promosi Gratis</span> - Produk Anda akan dipromosikan ke ribuan pengunjung.
                  </p>
                </div>
              </li>
              <li class="flex items-start gap-4 p-3 rounded-lg hover:bg-white/50 transition">
                <div class="flex-shrink-0 mt-0.5">
                  <div class="h-7 w-7 rounded-full bg-primary text-white flex items-center justify-center shadow-sm">
                    <i class="fas fa-check text-xs"></i>
                  </div>
                </div>
                <div>
                  <p class="text-gray-700">
                    <span class="font-semibold text-gray-800">Pelatihan Bisnis</span> - Akses ke workshop dan materi pengembangan UMKM.
                  </p>
                </div>
              </li>
              <li class="flex items-start gap-4 p-3 rounded-lg hover:bg-white/50 transition">
                <div class="flex-shrink-0 mt-0.5">
                  <div class="h-7 w-7 rounded-full bg-primary text-white flex items-center justify-center shadow-sm">
                    <i class="fas fa-check text-xs"></i>
                  </div>
                </div>
                <div>
                  <p class="text-gray-700">
                    <span class="font-semibold text-gray-800">Akses Pasar</span> - Jangkau pelanggan di seluruh Indonesia melalui platform kami.
                  </p>
                </div>
              </li>
              <li class="flex items-start gap-4 p-3 rounded-lg hover:bg-white/50 transition">
                <div class="flex-shrink-0 mt-0.5">
                  <div class="h-7 w-7 rounded-full bg-primary text-white flex items-center justify-center shadow-sm">
                    <i class="fas fa-check text-xs"></i>
                  </div>
                </div>
                <div>
                  <p class="text-gray-700">
                    <span class="font-semibold text-gray-800">Dukungan Pemerintah</span> - Fasilitas khusus dari pemerintah untuk UMKM terdaftar.
                  </p>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <!-- Registration Form Column -->
        <div class="animate-fade-in delay-200 order-1 lg:order-2">
          <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden dark:bg-gray-800 dark:border-gray-700">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-primary to-secondary p-6">
              <h3 class="text-2xl font-bold text-white text-center">Form Pendaftaran UMKM</h3>
            </div>

            <!-- Form Content -->
            <div class="p-6 sm:p-8">
              <form method="POST" action="./prosess/process_umkm.php" class="space-y-5">
                <!-- Business Name -->
                <div>
                  <label for="nama" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Nama UMKM <span class="text-red-500">*</span></label>
                  <input type="text" id="nama" name="nama" required
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Owner Name -->
                <div>
                  <label for="pemilik" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Nama Pemilik <span class="text-red-500">*</span></label>
                  <input type="text" id="pemilik" name="pemilik" required
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Business Capital -->
                <div>
                  <label for="modal" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Modal (Rp) <span class="text-red-500">*</span></label>
                  <input type="number" id="modal" name="modal" step="0.01" required
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Location -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label for="provinsi" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Provinsi</label>
                    <select id="provinsi"
                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition dark:bg-gray-700 dark:text-white">
                      <option value="">Pilih Provinsi</option>
                      <?php
                      // Query to get provinces from database
                      $query = "SELECT * FROM provinsi ORDER BY nama ASC";
                      $result = mysqli_query($koneksi, $query);
                      while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row['id'] . '">' . $row['nama'] . '</option>';
                      }
                      ?>
                    </select>
                  </div>
                  <div>
                    <label for="kabkota_id" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Kabupaten/Kota <span class="text-red-500">*</span></label>
                    <select id="kabkota_id" name="kabkota_id" required
                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition dark:bg-gray-700 dark:text-white">
                      <option value="">Pilih Kabupaten/Kota</option>
                      <?php
                      // Query to get provinces from database
                      $query = "SELECT * FROM kabkota ORDER BY nama ASC";
                      $result = mysqli_query($koneksi, $query);
                      while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row['id'] . '">' . $row['nama'] . '</option>';
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <!-- Business Category -->
                <div>
                  <label for="kategori_umkm_id" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Kategori Usaha <span class="text-red-500">*</span></label>
                  <select id="kategori_umkm_id" name="kategori_umkm_id" required
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition dark:bg-gray-700 dark:text-white">
                    <option value="">Pilih Kategori</option>
                    <?php
                    // Query to get categories from database
                    $query = "SELECT * FROM kategori_umkm ORDER BY nama ASC";
                    $result = mysqli_query($koneksi, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                      echo '<option value="' . $row['id'] . '">' . $row['nama'] . '</option>';
                    }
                    ?>
                  </select>
                </div>

                <!-- Address -->
                <div>
                  <label for="alamat" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                  <textarea id="alamat" name="alamat" rows="3" required
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition dark:bg-gray-700 dark:text-white"></textarea>
                </div>

                <!-- Website -->
                <div>
                  <label for="website" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Website</label>
                  <input type="url" id="website" name="website"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Email -->
                <div>
                  <label for="email" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Email</label>
                  <input type="email" id="email" name="email"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Rating -->
                <div>
                  <label for="rating" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Rating (1-5)</label>
                  <input type="number" id="rating" name="rating" min="1" max="5" step="0.1"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Pembina -->
                <div>
                  <label for="pembina_id" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Pembina</label>
                  <select id="pembina_id" name="pembina_id"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition dark:bg-gray-700 dark:text-white">
                    <option value="">Pilih Pembina</option>
                    <?php
                    // Query to get pembina from database
                    $query = "SELECT * FROM pembina ORDER BY nama ASC";
                    $result = mysqli_query($koneksi, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                      echo '<option value="' . $row['id'] . '">' . $row['nama'] . '</option>';
                    }
                    ?>
                  </select>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="submit"
                  class="w-full bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white font-bold py-4 px-6 rounded-lg transition-all transform hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                  Daftar Sekarang <i class="fas fa-arrow-right ml-2"></i>
                </button>

                <p class="text-sm text-gray-500 dark:text-gray-400 text-center">
                  Dengan mendaftar, Anda menyetujui <a href="#" class="text-primary hover:underline">Syarat & Ketentuan</a> kami.
                </p>
              </form>
            </div>
          </div>
        </div>

        <script>
          // Dynamic kabkota dropdown based on selected provinsi
          document.getElementById('provinsi').addEventListener('change', function() {
            const provinsiId = this.value;
            const kabkotaSelect = document.getElementById('kabkota_id');

            if (provinsiId) {
              fetch('get_kabkota.php?provinsi_id=' + provinsiId)
                .then(response => response.json())
                .then(data => {
                  kabkotaSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                  data.forEach(kabkota => {
                    kabkotaSelect.innerHTML += `<option value="${kabkota.id}">${kabkota.nama}</option>`;
                  });
                });
            } else {
              kabkotaSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
            }
          });
        </script>
      </div>
    </div>
  </section>

  <!-- Testimonials -->
  <section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Apa Kata <span class="text-blue-600">Mereka</span></h2>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
          Testimoni dari UMKM yang telah merasakan manfaat platform kami
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        <!-- Testimonial 1 -->
        <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 border border-gray-100 transform hover:-translate-y-2">
          <div class="flex items-center mb-6">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Budi Santoso" class="w-14 h-14 rounded-full mr-4 object-cover border-2 border-blue-100">
            <div>
              <h4 class="font-bold text-lg text-gray-800">Budi Santoso</h4>
              <p class="text-gray-500 text-sm">UMKM Keripik Singkong, Jawa Barat</p>
            </div>
          </div>
          <div class="text-gray-600 mb-6 italic relative">
            <svg class="absolute -top-4 -left-2 w-8 h-8 text-gray-200" fill="currentColor" viewBox="0 0 24 24">
            </svg>
            "Sejak bergabung dengan platform ini, penjualan kami meningkat pesat. Banyak pelanggan baru yang datang dari berbagai daerah."
            <svg class="absolute -bottom-4 -right-2 w-8 h-8 text-gray-200" fill="currentColor" viewBox="0 0 24 24">
            </svg>
          </div>
          <div class="flex text-yellow-400 text-lg">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
          </div>
        </div>

        <!-- Testimonial 2 -->
        <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl duration-300 border border-gray-100 transform hover:-translate-y-2 transition-transform">
          <div class="flex items-center mb-6">
            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Siti Aminah" class="w-14 h-14 rounded-full mr-4 object-cover border-2 border-blue-100">
            <div>
              <h4 class="font-bold text-lg text-gray-800">Siti Aminah</h4>
              <p class="text-gray-500 text-sm">UMKM Tas Rajut, Jawa Tengah</p>
            </div>
          </div>
          <div class="text-gray-600 mb-6 italic relative">
            <svg class="absolute -top-4 -left-2 w-8 h-8 text-gray-200" fill="currentColor" viewBox="0 0 24 24">
            </svg>
            "Pelatihan yang diberikan sangat bermanfaat. Sekarang saya bisa mengelola keuangan bisnis dengan lebih baik dan produk saya terjual hingga ke luar kota."
            <svg class="absolute -bottom-4 -right-2 w-8 h-8 text-gray-200" fill="currentColor" viewBox="0 0 24 24">
            </svg>
          </div>
          <div class="flex text-yellow-400 text-lg">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
          </div>
        </div>

        <!-- Testimonial 3 -->
        <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl duration-300 border border-gray-100 transform hover:-translate-y-2 transition-transform">
          <div class="flex items-center mb-6">
            <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="Ahmad Fauzi" class="w-14 h-14 rounded-full mr-4 object-cover border-2 border-blue-100">
            <div>
              <h4 class="font-bold text-lg text-gray-800">Ahmad Fauzi</h4>
              <p class="text-gray-500 text-sm">UMKM Kopi Arabika, Aceh</p>
            </div>
          </div>
          <div class="text-gray-600 mb-6 italic relative">
            <svg class="absolute -top-4 -left-2 w-8 h-8 text-gray-200" fill="currentColor" viewBox="0 0 24 24">
            </svg>
            "Dukungan dari pemerintah melalui platform ini sangat membantu. Sekarang produk kopi saya bisa dikenal lebih luas dan omzet meningkat signifikan."
            <svg class="absolute -bottom-4 -right-2 w-8 h-8 text-gray-200" fill="currentColor" viewBox="0 0 24 24">
            </svg>
          </div>
          <div class="flex text-yellow-400 text-lg">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section id="tentang" class="py-16 md:py-24 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 items-center">
        <!-- Image Column -->
        <div class="animate-fade-in order-2 md:order-1">
          <div class="relative overflow-hidden rounded-xl shadow-lg aspect-w-16 aspect-h-9">
            <img
              src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80"
              alt="Tentang UMKM Indonesia"
              class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
              loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent rounded-xl"></div>
          </div>
        </div>

        <!-- Content Column -->
        <div class="animate-fade-in delay-100 order-1 md:order-2">
          <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-800 mb-6 leading-tight">
            Tentang <span class="text-primary">Direktori UMKM Indonesia</span>
          </h2>

          <div class="space-y-4 mb-8">
            <p class="text-gray-600 text-base lg:text-lg">
              Direktori UMKM Indonesia adalah platform digital yang dibangun untuk mempromosikan dan mengembangkan Usaha Mikro, Kecil, dan Menengah (UMKM) di seluruh wilayah Indonesia.
            </p>
            <p class="text-gray-600 text-base lg:text-lg">
              Kami berkomitmen untuk menjadi jembatan antara pelaku UMKM dengan konsumen, sekaligus memberikan berbagai fasilitas pendukung seperti pelatihan, akses pembiayaan, dan bantuan pemasaran digital berbasis lokasi.
            </p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Mission Card -->
            <div class="bg-primary/10 px-4 py-3 rounded-lg border border-primary/20 hover:border-primary/40 transition-colors">
              <div class="flex items-center">
                <div class="bg-primary text-white p-2 rounded-full mr-3 flex-shrink-0">
                  <i class="fas fa-bullseye text-sm"></i>
                </div>
                <span class="font-medium text-gray-700 text-sm sm:text-base">Misi: Memberdayakan UMKM lokal</span>
              </div>
            </div>

            <!-- Vision Card -->
            <div class="bg-primary/10 px-4 py-3 rounded-lg border border-primary/20 hover:border-primary/40 transition-colors">
              <div class="flex items-center">
                <div class="bg-primary text-white p-2 rounded-full mr-3 flex-shrink-0">
                  <i class="fas fa-eye text-sm"></i>
                </div>
                <span class="font-medium text-gray-700 text-sm sm:text-base">Visi: UMKM Indonesia go digital</span>
              </div>
            </div>

            <!-- Strategy Card -->
            <div class="bg-primary/10 px-4 py-3 rounded-lg border border-primary/20 hover:border-primary/40 transition-colors sm:col-span-2 lg:col-span-1">
              <div class="flex items-center">
                <div class="bg-primary text-white p-2 rounded-full mr-3 flex-shrink-0">
                  <i class="fas fa-chart-line text-sm"></i>
                </div>
                <span class="font-medium text-gray-700 text-sm sm:text-base">Strategi: Kemitraan daerah</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Newsletter -->
  <section class="py-16 bg-primary text-white">
    <div class="container mx-auto px-4">
      <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">Dapatkan Info Terbaru</h2>
        <p class="text-xl opacity-90 mb-8">
          Berlangganan newsletter kami untuk mendapatkan informasi terbaru tentang program UMKM, pelatihan, dan promo menarik berdasarkan lokasi Anda.
        </p>
        <form class="flex flex-col sm:flex-row gap-4 max-w-2xl mx-auto">
          <div class="flex-grow">
            <input type="email" placeholder="Alamat email Anda" class="w-full px-6 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-accent">
          </div>
          <div class="location-selector flex-grow">
            <select class="w-full px-6 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-accent">
              <?php foreach ($provinsi_data as $provinsi): ?>
                <option value="<?= $provinsi['id'] ?>"><?= htmlspecialchars($provinsi['nama']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" class="bg-accent hover:bg-yellow-500 text-dark font-bold px-8 py-3 rounded-lg transition whitespace-nowrap">
            Berlangganan <i class="fas fa-paper-plane ml-2"></i>
          </button>
        </form>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section id="kontak" class="py-16 bg-white">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold text-dark mb-4">Hubungi <span class="text-primary">Kami</span></h2>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
          Kami siap membantu Anda. Silakan hubungi tim kami melalui berbagai cara berikut.
        </p>
      </div>

      <div class="grid md:grid-cols-2 gap-12">
        <div class="animate-fade-in">
          <div class="bg-light rounded-xl p-6 h-full">
            <h3 class="text-xl font-bold text-dark mb-6">Informasi Kontak</h3>
            <div class="space-y-6">
              <div class="flex items-start">
                <div class="flex-shrink-0 mt-1">
                  <div class="h-10 w-10 rounded-full bg-primary bg-opacity-10 text-primary flex items-center justify-center">
                    <i class="fas fa-map-marker-alt"></i>
                  </div>
                </div>
                <div class="ml-4">
                  <h4 class="font-bold text-gray-800">Alamat</h4>
                  <p class="text-gray-600">Gedung Kementerian UMKM, Jl. Jend. Sudirman Kav. 1, Jakarta Pusat</p>
                </div>
              </div>
              <div class="flex items-start">
                <div class="flex-shrink-0 mt-1">
                  <div class="h-10 w-10 rounded-full bg-primary bg-opacity-10 text-primary flex items-center justify-center">
                    <i class="fas fa-phone-alt"></i>
                  </div>
                </div>
                <div class="ml-4">
                  <h4 class="font-bold text-gray-800">Telepon</h4>
                  <p class="text-gray-600">(021) 1234567</p>
                  <p class="text-gray-600">+62 812 3456 7890 (WhatsApp)</p>
                </div>
              </div>
              <div class="flex items-start">
                <div class="flex-shrink-0 mt-1">
                  <div class="h-10 w-10 rounded-full bg-primary bg-opacity-10 text-primary flex items-center justify-center">
                    <i class="fas fa-envelope"></i>
                  </div>
                </div>
                <div class="ml-4">
                  <h4 class="font-bold text-gray-800">Email</h4>
                  <p class="text-gray-600">info@umkm-indonesia.go.id</p>
                  <p class="text-gray-600">umkm@kemenkopukm.go.id</p>
                </div>
              </div>
              <div class="flex items-start">
                <div class="flex-shrink-0 mt-1">
                  <div class="h-10 w-10 rounded-full bg-primary bg-opacity-10 text-primary flex items-center justify-center">
                    <i class="fas fa-clock"></i>
                  </div>
                </div>
                <div class="ml-4">
                  <h4 class="font-bold text-gray-800">Jam Operasional</h4>
                  <p class="text-gray-600">Senin - Jumat: 08.00 - 16.00 WIB</p>
                  <p class="text-gray-600">Sabtu: 08.00 - 14.00 WIB</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="animate-fade-in delay-100">
          <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 h-full">
            <h3 class="text-xl font-bold text-dark mb-6">Kirim Pesan</h3>
            <form>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                  <label for="name" class="block text-gray-700 font-medium mb-2">Nama Lengkap</label>
                  <input type="text" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                <div>
                  <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                  <input type="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
              </div>
              <div class="mb-4">
                <label for="subject" class="block text-gray-700 font-medium mb-2">Subjek</label>
                <input type="text" id="subject" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
              </div>
              <div class="mb-4">
                <label for="message" class="block text-gray-700 font-medium mb-2">Pesan</label>
                <textarea id="message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
              </div>
              <div>
                <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-bold py-3 px-4 rounded-lg transition">
                  Kirim Pesan <i class="fas fa-paper-plane ml-2"></i>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Map Section -->
  <div class="bg-gray-100 h-96">
    <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.923516761292!2d106.82255731537056!3d-6.897718195019382!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6849e9a9f06a45%3A0x2a5e5d3c2297f6b5!2sKementerian%20Koperasi%20dan%20UKM%20RI!5e0!3m2!1sid!2sid!4v1621234567890!5m2!1sid!2sid"
      width="100%"
      height="100%"
      style="border:0;"
      allowfullscreen=""
      loading="lazy"
      class="filter grayscale(20%) contrast(110%)">
    </iframe>
  </div>

  <!-- Footer -->
  <footer class="bg-dark text-white pt-16 pb-8">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
        <div>
          <div class="flex items-center mb-4">
            <img src="./src/assets/logo.png" alt="Logo UMKM Indonesia" class="h-8 w-auto mr-2 rounded-sm">
            <span class="text-xl font-bold text-white">UMKM<span class="text-accent">Indonesia</span></span>
          </div>
          <p class="text-gray-400 mb-4">
            Platform resmi untuk pengembangan dan promosi UMKM lokal di seluruh Indonesia.
          </p>
          <div class="flex space-x-4">
            <a href="#" class="text-gray-400 hover:text-white transition">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="text-gray-400 hover:text-white transition">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="text-gray-400 hover:text-white transition">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="text-gray-400 hover:text-white transition">
              <i class="fab fa-youtube"></i>
            </a>
          </div>
        </div>

        <div>
          <h4 class="text-lg font-bold mb-4">Tautan Cepat</h4>
          <ul class="space-y-2">
            <li><a href="" class="text-gray-400 hover:text-white transition">Beranda</a></li>
            <li><a href="#produk" class="text-gray-400 hover:text-white transition">Produk</a></li>
            <li><a href="#lokasi" class="text-gray-400 hover:text-white transition">Lokasi</a></li>
            <li><a href="#umkm" class="text-gray-400 hover:text-white transition">Daftar UMKM</a></li>
            <li><a href="#tentang" class="text-gray-400 hover:text-white transition">Tentang Kami</a></li>
          </ul>
        </div>

        <div>
          <h4 class="text-lg font-bold mb-4">Layanan</h4>
          <ul class="space-y-2">
            <li><a href="./pages/pelatihan.php" class="text-gray-400 hover:text-white transition">Pelatihan UMKM</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition">Pendampingan Bisnis</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition">Akses Pembiayaan</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition">Pemasaran Digital</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition">Legalitas Usaha</a></li>
          </ul>
        </div>

        <div>
          <h4 class="text-lg font-bold mb-4">Kontak</h4>
          <ul class="space-y-3">
            <li class="flex items-start">
              <i class="fas fa-map-marker-alt text-gray-400 mt-1 mr-3"></i>
              <span class="text-gray-400">Gedung Kementerian UMKM, Jl. Jend. Sudirman Kav. 1, Jakarta Pusat</span>
            </li>
            <li class="flex items-start">
              <i class="fas fa-phone-alt text-gray-400 mt-1 mr-3"></i>
              <span class="text-gray-400">(021) 1234567</span>
            </li>
            <li class="flex items-start">
              <i class="fas fa-envelope text-gray-400 mt-1 mr-3"></i>
              <span class="text-gray-400">info@umkm-indonesia.go.id</span>
            </li>
          </ul>
        </div>
      </div>

      <div class="border-t border-gray-800 pt-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
          <p class="text-gray-500 text-sm mb-4 md:mb-0">
            &copy; 2025 Direktori UMKM Indonesia. Hak Cipta Dilindungi.
          </p>
          <div class="flex space-x-6">
            <a href="#" class="text-gray-500 hover:text-white text-sm transition">Kebijakan Privasi</a>
            <a href="#" class="text-gray-500 hover:text-white text-sm transition">Syarat & Ketentuan</a>
            <a href="#" class="text-gray-500 hover:text-white text-sm transition">FAQ</a>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <!-- Back to Top Button -->
  <button id="back-to-top" class="fixed bottom-8 right-8 bg-primary text-white p-3 rounded-full shadow-lg opacity-0 invisible transition-all duration-300 hover:bg-secondary">
    <i class="fas fa-arrow-up"></i>
  </button>

  <script type="module" src="./src/main.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const mobileMenuButton = document.getElementById('mobile-menu-button');
      const mobileMenu = document.getElementById('mobile-menu');
      const menuOpenIcon = document.getElementById('menu-open-icon');
      const menuCloseIcon = document.getElementById('menu-close-icon');

      // Fungsi untuk toggle menu mobile
      function toggleMobileMenu() {
        // Toggle visibility menu mobile
        mobileMenu.classList.toggle('hidden');

        // Toggle animasi
        mobileMenu.classList.toggle('origin-top');
        mobileMenu.classList.toggle('transition-all');
        mobileMenu.classList.toggle('duration-300');
        mobileMenu.classList.toggle('ease-out');

        // Toggle icons
        menuOpenIcon.classList.toggle('hidden');
        menuCloseIcon.classList.toggle('hidden');
      }

      // Event listener untuk tombol menu mobile
      mobileMenuButton.addEventListener('click', toggleMobileMenu);

      // Tutup menu ketika mengklik item menu (untuk navigasi anchor)
      const mobileMenuItems = mobileMenu.querySelectorAll('a[href^="#"]');
      mobileMenuItems.forEach(item => {
        item.addEventListener('click', function() {
          // Tunggu sebentar sebelum menutup untuk memastikan navigasi terjadi
          setTimeout(() => {
            toggleMobileMenu();
          }, 100);
        });
      });

      // Tutup menu ketika mengklik di luar menu
      document.addEventListener('click', function(event) {
        const isClickInsideNavbar = event.target.closest('nav');
        const isClickOnMenuButton = event.target.closest('#mobile-menu-button');
        const isClickInsideMobileMenu = event.target.closest('#mobile-menu');

        if (!mobileMenu.classList.contains('hidden') &&
          isClickInsideNavbar &&
          !isClickOnMenuButton &&
          !isClickInsideMobileMenu) {
          toggleMobileMenu();
        }
      });

      // Tutup menu ketika ukuran layar berubah menjadi lebih besar (desktop)
      window.addEventListener('resize', function() {
        if (window.innerWidth >= 768 && !mobileMenu.classList.contains('hidden')) {
          toggleMobileMenu();
        }
      });
    });
  </script>
</body>

</html>