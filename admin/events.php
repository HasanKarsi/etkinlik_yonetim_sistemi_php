<?php
// Veritabanı ve üst bilgi (header) dosyalarını dahil et
require '../includes/db.php';
require '../includes/header.php';

// Yalnızca admin kullanıcılar erişebilir; değilse ana sayfaya yönlendir
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../index.php');
    exit;
}

// Başarı ve hata mesajlarını tutacak değişkenler
$success_message = '';
$error_message = '';

// Etkinlik silme işlemi
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];

    // Silinmek istenen etkinliğe ait bilet var mı kontrol et
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tickets WHERE event_id = ?");
    $stmt->execute([$delete_id]);
    $ticket_count = $stmt->fetchColumn();

    // Silinmek istenen etkinliğe ait sepet öğesi var mı kontrol et
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM cart WHERE event_id = ?");
    $stmt->execute([$delete_id]);
    $cart_count = $stmt->fetchColumn();

    // Eğer bu etkinliğe ait bilet ya da sepet öğesi varsa silinemez
    if ($ticket_count > 0 || $cart_count > 0) {
        $error_message = "Bu etkinliğe ait bilet veya sepet öğeleri var. Silme işlemi yapılamaz.";
    } else {
        try {
            // Hiçbir bağlı kaydı yoksa etkinlik silinir
            $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
            $stmt->execute([$delete_id]);
            $success_message = "Etkinlik başarıyla silindi.";
        } catch (PDOException $e) {
            // Silme işlemi sırasında hata oluşursa gösterilir
            $error_message = "Etkinlik silinirken hata oluştu: " . htmlspecialchars($e->getMessage());
        }
    }
}

// Etkinlik ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formdan gelen verileri al ve temizle
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $event_type = trim($_POST['event_type'] ?? '');
    $date = $_POST['date'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $ticket_price = filter_var($_POST['ticket_price'] ?? 0, FILTER_VALIDATE_FLOAT);
    $capacity = filter_var($_POST['capacity'] ?? 0, FILTER_VALIDATE_INT);

    // Tüm alanların doldurulduğunu ve geçerli olduğunu kontrol et
    if (empty($title) || empty($description) || empty($event_type) || empty($date) || empty($location) || $ticket_price === false || $capacity === false || $capacity < 1) {
        $error_message = "Lütfen tüm alanları doğru şekilde doldurun.";
    } else {
        try {
            // Etkinlik veritabanına eklenir
            $stmt = $pdo->prepare("
                INSERT INTO events (title, description, event_type, date, location, ticket_price, capacity, remaining_capacity)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$title, $description, $event_type, $date, $location, $ticket_price, $capacity, $capacity]);
            $success_message = "Etkinlik başarıyla eklendi.";
        } catch (PDOException $e) {
            $error_message = "Etkinlik eklenirken hata oluştu: " . htmlspecialchars($e->getMessage());
        }
    }
}

// Veritabanından tüm etkinlikleri çek
$events = $pdo->query("SELECT * FROM events ORDER BY date DESC")->fetchAll();
?>

<!-- HTML Bölümü -->
<div class="container my-5">
    <h2 class="text-center mb-4">Etkinlik Yönetimi</h2>

    <!-- Başarı ve hata mesajlarını göster -->
    <?php if ($success_message): ?>
        <div class="alert alert-success text-center"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if ($error_message): ?>
        <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Etkinlik Ekleme Formu -->
    <div class="card shadow mb-5">
        <div class="card-body">
            <h5 class="card-title">Yeni Etkinlik Ekle</h5>
            <form method="POST" class="row g-3">
                <!-- Etkinlik Başlığı -->
                <div class="col-md-6">
                    <input type="text" name="title" class="form-control" placeholder="Etkinlik Başlığı"
                        value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" required>
                </div>

                <!-- Etkinlik Türü -->
                <div class="col-md-6">
                    <input type="text" name="event_type" class="form-control" placeholder="Etkinlik Türü"
                        value="<?php echo isset($_POST['event_type']) ? htmlspecialchars($_POST['event_type']) : ''; ?>" required>
                </div>

                <!-- Etkinlik Açıklaması -->
                <div class="col-12">
                    <textarea name="description" class="form-control" placeholder="Etkinlik Açıklaması" rows="3" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                </div>

                <!-- Tarih -->
                <div class="col-md-6">
                    <input type="datetime-local" name="date" class="form-control"
                        value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : ''; ?>" required>
                </div>

                <!-- !! HATA: 'douche' kelimesi burada yanlışlıkla yazılmış -->
                <!-- Konum -->
                <div class="col-md-6">
                    <input type="text" name="location" class="form-control" placeholder="Yer"
                        value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>" required>
                </div>

                <!-- Bilet Fiyatı -->
                <div class="col-md-4">
                    <input type="number" name="ticket_price" class="form-control" placeholder="Bilet Fiyatı (₺)" step="0.01"
                        value="<?php echo isset($_POST['ticket_price']) ? htmlspecialchars($_POST['ticket_price']) : ''; ?>" required>
                </div>

                <!-- Kapasite -->
                <div class="col-md-4">
                    <input type="number" name="capacity" class="form-control" placeholder="Kapasite" min="1"
                        value="<?php echo isset($_POST['capacity']) ? htmlspecialchars($_POST['capacity']) : ''; ?>" required>
                </div>

                <!-- Gönder Butonu -->
                <div class="col-md-4 d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Etkinlik Ekle
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Etkinlik Listesi Tablosu -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Başlık</th>
                    <th>Tarih</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($events)): ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">Henüz etkinlik bulunmamaktadır.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                            <td><?php echo date('d.m.Y H:i', strtotime($event['date'])); ?></td>
                            <td>
                                <a href="?delete_id=<?php echo $event['id']; ?>" class="btn btn-sm btn-danger delete-event"
                                   onclick="return confirm('Bu etkinliği silmek istediğinizden emin misiniz?');">
                                    <i class="fas fa-trash-alt"></i> Sil
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JavaScript: Silme onayı için kullanıcıdan onay al -->
<script>
document.querySelectorAll('.delete-event').forEach(button => {
    button.addEventListener('click', function(event) {
        if (!confirm('Bu etkinliği silmek istediğinizden emin misiniz?')) {
            event.preventDefault();
        }
    });
});
</script>

<!-- Footer dosyasını dahil et -->
<?php require '../includes/footer.php'; ?>
