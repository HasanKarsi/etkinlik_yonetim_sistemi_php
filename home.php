<?php
require 'includes/db.php';
require 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit;
}

// Kullanıcının first_entry değerini çek
$stmt = $pdo->prepare("SELECT first_entry FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
$_SESSION['first_entry'] = $user['first_entry'];

// İlk girişte şifre değiştirme
if ($_SESSION['first_entry'] == 1) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ?, first_entry = 0 WHERE id = ?");
        $stmt->execute([$new_password, $_SESSION['user_id']]);
        $_SESSION['first_entry'] = 0;
        echo "<div class='alert alert-success text-center mx-auto' style='max-width: 400px;'>Şifre değiştirildi!</div>";
    } else {
        ?>
        <div class="container mt-5">
            <div class="card shadow-lg mx-auto password-card">
                <div class="card-body p-4">
                    <h2 class="card-title text-center mb-4">Şifre Değiştir</h2>
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
        require 'includes/footer.php';
        exit;
    }
}

// Etkinlikleri veritabanından çek
$stmt = $pdo->query("SELECT * FROM events ORDER BY date ASC");
$events = $stmt->fetchAll();

// Duyuruları çek
$announcements = $pdo->query("SELECT * FROM announcements ORDER BY created_at DESC")->fetchAll();

// Kullanıcı ilgi alanlarını çek
$stmt = $pdo->prepare("SELECT interest FROM interests WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$interests = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Öneri: İlgi alanlarına göre etkinlik öner
$recommended_events = [];
if ($interests) {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE event_type IN (" . implode(',', array_fill(0, count($interests), '?')) . ") ORDER BY date ASC");
    $stmt->execute($interests);
    $recommended_events = $stmt->fetchAll();
}
?>

<div class="container mt-5 mb-5">
    <!-- Etkinlikler -->
    <h2 class="mb-4 text-center">Etkinlikler</h2>
    <div class="row">
        <?php foreach ($events as $event): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 event-card">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p class="card-text"><?php echo htmlspecialchars($event['description']); ?></p>
                        <p><strong>Tarih:</strong> <?php echo $event['date']; ?></p>
                        <p><strong>Yer:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                        <p><strong>Fiyat:</strong> <?php echo $event['ticket_price']; ?> TL</p>
                        <p><strong>Kalan Kontenjan:</strong> <?php echo $event['remaining_capacity']; ?></p>
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

    <!-- Önerilen Etkinlikler -->
    <h2 class="mb-4 text-center">Önerilen Etkinlikler</h2>
    <div class="row">
        <?php foreach ($recommended_events as $event): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 event-card">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p class="card-text"><?php echo htmlspecialchars($event['description']); ?></p>
                        <p><strong>Tarih:</strong> <?php echo $event['date']; ?></p>
                        <a href="/cart.php?event_id=<?php echo $event['id']; ?>" class="btn btn-outline-primary w-100">
                            <i class="fas fa-cart-plus me-2"></i>Sepete Ekle
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Hava Durumu -->
    <h2 class="mb-4 text-center">Hava Durumu</h2>
    <div id="weather" class="card shadow-sm p-4 mb-5 text-center">
        <p class="text-muted">Hava durumu yükleniyor...</p>
    </div>
</div>

<?php require 'includes/footer.php'; ?>