<?php
session_start();

// Debugging
error_log("Session status: " . print_r($_SESSION, true));

if (!isset($_SESSION['registration_success'])) {
    error_log("Redirecting to index - no registration success");
    header('Location: ../index.php');
    exit;
}

// Hapus session sebelum output
$message = $_SESSION['message'] ?? 'Pendaftaran berhasil!';
unset($_SESSION['registration_success']);
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-progress {
            animation: progress 3s linear forwards;
            transform-origin: left;
        }
        @keyframes progress {
            0% { transform: scaleX(0); }
            100% { transform: scaleX(1); }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg p-8 max-w-md w-full text-center animate-fade-in">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
            <i class="fas fa-check-circle text-green-600 text-3xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">PENDAFTARAN BERHASIL!</h2>
        <p class="text-gray-600 mb-4"><?= htmlspecialchars($message) ?></p>
        <p class="text-gray-500 text-sm">Anda akan diarahkan ke halaman utama dalam 3 detik...</p>
        
        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-4">
            <div class="bg-primary h-1.5 rounded-full animate-progress"></div>
        </div>
    </div>

    <script>
        setTimeout(function(){ 
            window.location.href = "../index.php"; 
        }, 3000);
    </script>
</body>
</html>