<?php
// ========================================
// Bu sayfa, giriş yapan kullanıcıların ana sayfasıdır.
// Kullanıcı ilk kez giriş yapıyorsa, şifre değiştirmesi istenir.
// Sayfada sistemdeki etkinlikler, duyurular, önerilen (API'den çekilen) etkinlikler ve hava durumu bölümü bulunur.
// ========================================

// Veritabanı bağlantı dosyası
require 'includes/db.php';

// Ortak header (menü, stiller) dosyası
require 'includes/header.php';

// Giriş kontrolü – Kullanıcı giriş yapmamışsa login sayfasına yönlendirilir
if (!isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit;
}

// Kullanıcının "ilk giriş" durumunu veritabanından çek
$stmt = $pdo->prepare("SELECT first_entry FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Session'a ilk giriş bilgisini kaydet
$_SESSION['first_entry'] = $user['first_entry'];

// Eğer kullanıcı ilk kez giriş yapıyorsa, şifre değiştirme zorunlu
if ($_SESSION['first_entry'] == 1) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
        // Yeni şifreyi hashle ve veritabanına kaydet
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ?, first_entry = 0 WHERE id = ?");
        $stmt->execute([$new_password, $_SESSION['user_id']]);

        // Artık kullanıcı ilk girişte değil
        $_SESSION['first_entry'] = 0;

        // Başarı mesajı göster
        echo "<div class='alert alert-success text-center mx-auto' style='max-width: 400px;'>Şifre değiştirildi!</div>";
    } else {
        // Şifre değiştirme formu gösterilir
        ?>
        <div class="container mt-5">
            <div class="card shadow-lg mx-auto password-card">
                <div class="card-body p-4">
                    <h2 class="card-title text-center mb-4">Şifre Değiştir</h2>
                    <div class="alert alert-info text-center" role="alert">
                        Güvenliğiniz için, ilk girişte şifrenizi değiştirmeniz gerekmektedir.
                    </div>
                    <form method="POST">
                        <div class="mb-3">
                            <input type="password" name="new_password" class="form-control" placeholder="Yeni Şifre" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Değiştir</button>
                    </form>
                </div>
            </div>
        </div>
        <?php
        // Footer dahil edilip çıkılır
        require 'includes/footer.php';
        exit;
    }
}

// --------------------------
// Etkinlikler veritabanından çekilir
$stmt = $pdo->query("SELECT * FROM events ORDER BY date ASC");
$events = $stmt->fetchAll();

// Duyurular çekilir
$announcements = $pdo->query("SELECT * FROM announcements ORDER BY created_at DESC")->fetchAll();

// --------------------------
// Ticketmaster API'den İstanbul'daki önerilen etkinlikleri çek
$api_url = "https://app.ticketmaster.com/discovery/v2/events?apikey=ApOwDGH7bjwPYSLcVZbxKuyOAGTGqAgd&locale=*&city=%C4%B0stanbul";
$recommended_events = [];
$error_message = '';

try {
    // API'den veri çekilir
    $json = @file_get_contents($api_url);

    // Eğer veri çekilemezse hata mesajı belirlenir
    if ($json === false) {
        $error_message = "API'den veri çekilemedi. Lütfen daha sonra tekrar deneyin.";
    } else {
        $data = json_decode($json, true);
        // API'den dönen etkinlik verileri varsa değişkene aktar
        if (isset($data['_embedded']['events'])) {
            $recommended_events = $data['_embedded']['events'];
        } else {
            $error_message = "İstanbul için etkinlik bulunamadı.";
        }
    }
} catch (Exception $e) {
    // API çağrısı sırasında oluşan istisnai durumlar
    $error_message = "API hatası: " . htmlspecialchars($e->getMessage());
}
?>

<!-- ====================================== -->
<!-- HTML ARAYÜZ BAŞLANGICI -->
<!-- ====================================== -->

<div class="container mt-5 mb-5">
    <!-- Sistem Etkinlikleri -->
    <h2 class="mb-4 text-center">Etkinlikler</h2>
    <div class="row">
        <?php foreach ($events as $event): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 event-card">
                    <div class="card-body">
                        <!-- Etkinlik bilgileri -->
                        <h3 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p class="card-text"><?php echo htmlspecialchars($event['description']); ?></p>
                        <p><strong>Tarih:</strong> <?php echo $event['date']; ?></p>
                        <p><strong>Yer:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                        <p><strong>Fiyat:</strong> <?php echo $event['ticket_price']; ?> TL</p>
                        <p><strong>Kalan Kontenjan:</strong> <?php echo $event['remaining_capacity']; ?></p>
                        <!-- Sepete ekle butonu -->
                        <a href="/cart.php?event_id=<?php echo $event['id']; ?>" class="btn btn-outline-primary w-100">
                            <i class="fas fa-cart-plus me-2"></i>Sepete Ekle
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Duyurular -->
    <h2 class="mb-4 text-center">Duyurular</h2>
    <div class="row">
        <?php foreach ($announcements as $ann): ?>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm announcement-card">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo htmlspecialchars($ann['title']); ?></h3>
                        <p class="card-text"><?php echo htmlspecialchars($ann['content']); ?></p>
                        <p class="text-muted small">Yayınlanma: <?php echo $ann['created_at']; ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Önerilen Etkinlikler (API'den gelenler) -->
    <h2 class="mb-4 text-center">Önerilen Etkinlikler</h2>
    <?php if ($error_message): ?>
        <div class="alert alert-warning text-center"><?php echo $error_message; ?></div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($recommended_events as $event): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100 event-card">
                        <!-- Etkinlik resmi -->
                        <?php if (!empty($event['images'][0]['url'])): ?>
                            <img src="<?php echo htmlspecialchars($event['images'][0]['url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($event['name'] ?? 'Etkinlik'); ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <!-- Etkinlik detayları -->
                            <h3 class="card-title"><?php echo htmlspecialchars($event['name'] ?? 'Bilinmiyor'); ?></h3>
                            <p class="card-text"><strong>Tip:</strong> <?php echo htmlspecialchars($event['type'] ?? 'Bilinmiyor'); ?></p>
                            <p><strong>Tarih:</strong> <?php echo htmlspecialchars($event['dates']['start']['localDate'] ?? 'Bilinmiyor'); ?></p>
                            <p><strong>Saat Dilimi:</strong> <?php echo htmlspecialchars(isset($event['_embedded']['venues'][0]['timezone']) ? $event['_embedded']['venues'][0]['timezone'] : ($event['timezone'] ?? 'Bilinmiyor')); ?></p>
                            <!-- Etkinlik sayfasına yönlendirme -->
                            <a href="<?php echo htmlspecialchars($event['url'] ?? '#'); ?>" class="btn btn-outline-primary w-100" target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i>Etkinlik Sayfası
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Hava durumu bileşeni -->
    <h2 class="mb-4 text-center">Hava Durumu</h2>
    <div id="weather" class="card shadow-sm p-4 mb-5 text-center">
        <p class="text-muted">Hava durumu yükleniyor...</p>
    </div>
</div>

<!-- Ortak footer bileşeni -->
<?php require 'includes/footer.php'; ?>
