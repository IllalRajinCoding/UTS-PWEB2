<?php
include "../config/koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pembina UMKM</title>
    <link rel="stylesheet" href="../src/output.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                    boxShadow: {
                        'card': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                        'card-hover': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)'
                    }
                }
            }
        }
    </script>
    <style>
        .speaker-card {
            transition: all 0.3s ease;
            background: linear-gradient(to bottom, white 80%, #f8fafc 20%);
        }

        .speaker-card:hover {
            transform: translateY(-5px);
        }

        .expertise-badge {
            position: relative;
            overflow: hidden;
        }

        .expertise-badge::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transform: translateX(-100%);
        }

        .expertise-badge:hover::after {
            animation: shine 1.5s infinite;
        }

        @keyframes shine {
            100% {
                transform: translateX(100%);
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <?php include '../component/navbar.php' ?>

    <main>
        <!-- Hero Section -->
        <div class="relative bg-primary py-16">
            <div class="absolute inset-0 opacity-10 bg-[url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center"></div>
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-3xl mx-auto text-center">
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Pembina & Mentor UMKM</h1>
                    <p class="text-xl text-white opacity-90">Temukan pembina berpengalaman yang siap membantu mengembangkan bisnis Anda</p>
                </div>
            </div>
        </div>

        <!-- Pembina Section -->
        <div class="container mx-auto px-4 py-12">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Tim Pembina Kami</h2>
                <div class="w-24 h-1 bg-secondary mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">Para ahli di bidangnya masing-masing yang telah membantu ratusan UMKM berkembang</p>
            </div>

            <!-- Filter Section -->
            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <button class="px-4 py-2 rounded-full bg-primary text-white font-medium">Semua</button>
                <button class="px-4 py-2 rounded-full bg-gray-200 text-gray-700 font-medium hover:bg-gray-300">Marketing</button>
                <button class="px-4 py-2 rounded-full bg-gray-200 text-gray-700 font-medium hover:bg-gray-300">Keuangan</button>
                <button class="px-4 py-2 rounded-full bg-gray-200 text-gray-700 font-medium hover:bg-gray-300">Produksi</button>
                <button class="px-4 py-2 rounded-full bg-gray-200 text-gray-700 font-medium hover:bg-gray-300">Digital</button>
            </div>

            <!-- Pembina Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php
                $query = "SELECT * FROM pembina";
                $result = mysqli_query($koneksi, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <div class="speaker-card rounded-xl shadow-card hover:shadow-card-hover overflow-hidden border border-gray-100">
                        <!-- Header with image -->
                        <div class="relative h-48 bg-gradient-to-r from-primary to-secondary flex items-center justify-center">
                            <img src="../src/assets/pelatihan.jpg" alt="Pembina UMKM" class="w-full h-full object-cover opacity-90">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-1"><?php echo $row['nama']; ?></h3>
                            <p class="text-gray-500 text-sm mb-4">Pembina UMKM Profesional</p>

                            <div class="mb-4">
                                <span class="expertise-badge inline-block bg-secondary bg-opacity-10 text-secondary text-sm px-4 py-2 rounded-full font-medium">
                                    <i class="fas fa-star mr-2"></i><?php echo $row['keahlian']; ?>
                                </span>
                            </div>

                            <div class="flex justify-between text-sm text-gray-500 mb-4">
                                <span><i class="fas fa-user-graduate mr-1"></i> 15+ Tahun</span>
                                <span><i class="fas fa-users mr-1"></i> 200+ UMKM</span>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                            <a href="#" class="text-primary font-medium text-sm hover:underline flex items-center">
                                <i class="far fa-eye mr-2"></i> Lihat Profil
                            </a>
                            <div class="flex space-x-2">
                                <a href="#" class="text-gray-400 hover:text-primary">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-primary">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-primary">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-primary py-16">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold text-white mb-6">Siap Mengembangkan Bisnis Anda?</h2>
                <p class="text-white opacity-90 max-w-2xl mx-auto mb-8">Dapatkan bimbingan langsung dari para ahli untuk mengoptimalkan potensi bisnis UMKM Anda</p>
                <button class="bg-white text-primary font-bold px-8 py-3 rounded-lg hover:bg-gray-100 transition-colors shadow-lg">
                    Daftar Sekarang <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>
    </main>

    <?php include '../component/footer.php' ?>
</body>

</html>