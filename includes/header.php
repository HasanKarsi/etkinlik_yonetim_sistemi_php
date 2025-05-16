<?php
session_start();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etkinlik Yönetim Sistemi</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Özel CSS -->
    <link rel="stylesheet" href="/css/style.css">
    <!-- JavaScript Dosyaları -->
    <script src="/js/script.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body>
    <!-- Sayfa yüksekliğini kontrol eden wrapper başlangıcı -->
    <div class="wrapper d-flex flex-column min-vh-100">
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="/home.php">
                        <i class="fas fa-ticket-alt me-2"></i> Etkinlik Sistemi
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Menüyü aç/kapat">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <?php if (!isset($_SESSION['user_id'])): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="/index.php"><i class="fas fa-sign-in-alt"></i> Giriş</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/register.php"><i class="fas fa-user-plus"></i> Kayıt</a>
                                </li>
                            <?php else: ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="/home.php"><i class="fas fa-home"></i> Ana Sayfa</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/cart.php"><i class="fas fa-shopping-cart"></i> Sepet</a>
                                </li>
                                <?php if ($_SESSION['is_admin']): ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="/admin/index.php"><i class="fas fa-cog"></i> Yönetici Paneli</a>
                                    </li>
                                <?php endif; ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="/logout.php"><i class="fas fa-sign-out-alt"></i> Çıkış</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Ana içerik başlangıcı -->
        <main class="flex-grow-1">
