<?php
// Bu sayfa, Etkinlik Yönetim Sistemi için genel site şablonunun baş kısmını oluşturur.
// Kullanıcı oturumunu başlatır, sayfa başlığı ve meta bilgilerini ayarlar.
// Bootstrap, Font Awesome ve özel CSS/JS dosyalarını dahil eder.
// Üst navigasyon menüsünü oluşturur ve kullanıcı giriş durumuna göre farklı menü seçenekleri gösterir.
// Bu dosya, site genelinde ortak kullanılan header ve navigation yapısını içerir.

session_start(); // Oturumu başlatır, kullanıcı bilgileri burada takip edilir.
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8"> <!-- Sayfa karakter seti -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive tasarım için viewport ayarı -->
    <title>Etkinlik Yönetim Sistemi</title> <!-- Tarayıcı sekmesinde görünen başlık -->

    <!-- Bootstrap 5 CSS framework'ü sayfaya stil verir -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome ikon kütüphanesi -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Özel CSS dosyası -->
    <link rel="stylesheet" href="/css/style.css">
    <!-- Sayfa ile ilişkili JavaScript dosyaları, defer ile sayfa yüklenince çalışır -->
    <script src="/js/script.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body>
    <!-- Sayfanın tam yüksekliğini kaplayan esnek wrapper başlangıcı -->
    <div class="wrapper d-flex flex-column min-vh-100">
        <header>
            <!-- Bootstrap navbar, sayfa üstü menü çubuğu -->
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="container-fluid">
                    <!-- Site logosu ve ana sayfa linki -->
                    <a class="navbar-brand" href="/home.php">
                        <i class="fas fa-ticket-alt me-2"></i> Etkinlik Sistemi
                    </a>
                    <!-- Küçük ekranlarda hamburger menü butonu -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Menüyü aç/kapat">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <!-- Menü öğelerinin gövdesi, ekran genişliğine göre gizlenir/gösterilir -->
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <?php if (!isset($_SESSION['user_id'])): ?>
                                <!-- Giriş yapmamış kullanıcılar için menü -->
                                <li class="nav-item">
                                    <a class="nav-link" href="/index.php"><i class="fas fa-sign-in-alt"></i> Giriş</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/register.php"><i class="fas fa-user-plus"></i> Kayıt</a>
                                </li>
                            <?php else: ?>
                                <!-- Giriş yapmış kullanıcılar için menü -->
                                <li class="nav-item">
                                    <a class="nav-link" href="/home.php"><i class="fas fa-home"></i> Ana Sayfa</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/cart.php"><i class="fas fa-shopping-cart"></i> Sepet</a>
                                </li>
                                <?php if ($_SESSION['is_admin']): ?>
                                    <!-- Yönetici yetkisine sahip kullanıcılar için özel menü -->
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

        <!-- Ana içerik alanı, sayfanın geri kalan içeriği buraya gelecek -->
        <main class="flex-grow-1">
