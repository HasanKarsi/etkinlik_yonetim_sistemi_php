<?php
/**
 * Sayfa: admin/index.php
 * Amaç: Bu sayfa, yalnızca giriş yapmış ve yönetici yetkisine sahip kullanıcıların erişebildiği bir yönetim panelidir.
 * Panel üzerinden kullanıcı yönetimi, etkinlik yönetimi ve duyuru yönetimi sayfalarına geçiş yapılabilir.
 */

// Veritabanı bağlantı dosyasını dahil eder
require '../includes/db.php';

// Ortak header dosyasını dahil eder (genellikle oturum başlatma veya üst kısım içerikleri için)
require '../includes/header.php';

// Eğer kullanıcı oturum açmamışsa veya yönetici değilse, anasayfaya yönlendirilir
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Paneli</title>

    <!-- Bootstrap 5 CSS kütüphanesi -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome ikon kütüphanesi (ikonlar için) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        /* Sayfa arka planı ve genel yazı tipi ayarı */
        body {
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2); /* Açık mavi gradyan arka plan */
            min-height: 100vh; /* Ekran yüksekliği kadar minimum yükseklik */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Yazı tipi ailesi */
        }

        /* Panelin üstten ve alttan boşlukları */
        .admin-panel {
            margin-top: 4rem;
            margin-bottom: 4rem;
        }

        /* Kart bileşeni (Bootstrap card) için özel stil */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1); /* Hafif gölge efekti */
            overflow: hidden;
        }

        /* Kart başlığı için gradyan arka plan ve metin rengi */
        .card-header {
            background: linear-gradient(to right, #4e73df, #224abe); /* Mor-mavi geçiş */
            color: white;
            border-radius: 15px 15px 0 0;
            text-align: center;
            padding: 1.5rem;
        }

        /* Liste elemanlarının iç boşlukları ve geçiş efektleri */
        .list-group-item {
            border: none;
            padding: 1rem;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        /* Hover efekti ile liste öğesini yukarı kaldırır ve arka planını değiştirir */
        .list-group-item:hover {
            transform: translateY(-5px);
            background-color: #f8f9fa;
        }

        /* Özel buton sınıfı: buton hizalama, yazı boyutu, padding, animasyon */
        .btn-custom {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            padding: 0.75rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        /* Buton içindeki ikonlara sağ boşluk verilir */
        .btn-custom i {
            margin-right: 0.5rem;
        }

        /* Kullanıcı yönetimi butonu: mavi tonları */
        .btn-users { border-color: #007bff; color: #007bff; }
        .btn-users:hover { background-color: #007bff; color: white; }

        /* Etkinlik yönetimi butonu: yeşil tonları */
        .btn-events { border-color: #28a745; color: #28a745; }
        .btn-events:hover { background-color: #28a745; color: white; }

        /* Duyuru yönetimi butonu: sarı tonları */
        .btn-announcements { border-color: #ffc107; color: #ffc107; }
        .btn-announcements:hover { background-color: #ffc107; color: white; }

        /* Küçük ekranlar (mobil) için başlık ve buton font boyutları küçültülür */
        @media (max-width: 576px) {
            .card-header h2 { font-size: 1.5rem; }
            .btn-custom { font-size: 1rem; }
        }
    </style>
</head>
<body>
    <!-- Bootstrap kapsayıcı -->
    <div class="container admin-panel">
        <div class="card">
            <!-- Kart başlığı -->
            <div class="card-header">
                <h2 class="mb-0"><i class="fas fa-cog me-2"></i> Yönetici Paneli</h2>
            </div>

            <!-- Kart gövdesi (içerik) -->
            <div class="card-body p-4">
                <ul class="list-group list-group-flush">
                    <!-- Kullanıcı yönetimi bağlantısı -->
                    <li class="list-group-item">
                        <a href="users.php" class="btn btn-custom btn-users w-100">
                            <i class="fas fa-users"></i> Kullanıcı Yönetimi
                        </a>
                    </li>

                    <!-- Etkinlik yönetimi bağlantısı -->
                    <li class="list-group-item">
                        <a href="events.php" class="btn btn-custom btn-events w-100">
                            <i class="fas fa-calendar-alt"></i> Etkinlik Yönetimi
                        </a>
                    </li>

                    <!-- Duyuru yönetimi bağlantısı -->
                    <li class="list-group-item">
                        <a href="announcements.php" class="btn btn-custom btn-announcements w-100">
                            <i class="fas fa-bullhorn"></i> Duyuru Yönetimi
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript ve Popper.js (çalışması için gerekli) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Sayfanın alt kısmında gösterilecek footer içeriklerini dahil eder
require '../includes/footer.php';
?>
