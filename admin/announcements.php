<?php
// Oturum ve veritabanı bağlantısı
require '../includes/db.php';
require '../includes/header.php';

// Admin kontrolü
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../index.php');
    exit;
}

// Mesaj değişkenleri
$success_message = '';
$error_message = '';

// Etkinlik silme işlemi
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];

    try {
        // İlişkili kayıt kontrolü (tickets ve cart)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tickets WHERE event_id = ?");
        $stmt->execute([$delete_id]);
        $ticket_count = $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM cart WHERE event_id = ?");
        $stmt->execute([$delete_id]);
        $cart_count = $stmt->fetchColumn();

        if ($ticket_count > 0 || $cart_count > 0) {
            $error_message = "Bu etkinliğe ait bilet veya sepet öğeleri var. Silinemez.";
        } else {
            // Etkinliği sil
            $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
            $stmt->execute([$delete_id]);

            if ($stmt->rowCount() > 0) {
                $success_message = "Etkinlik başarıyla silindi.";
            } else {
                $error_message = "Silinecek etkinlik bulunamadı.";
            }
        }
    } catch (PDOException $e) {
        $error_message = "Etkinlik silinirken hata: " . htmlspecialchars($e->getMessage());
    }
}

// Etkinlik ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
    // Girdi doğrulama
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $event_type = trim($_POST['event_type'] ?? '');
    $date = $_POST['date'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $ticket_price = filter_var($_POST['ticket_price'] ?? 0, FILTER_VALIDATE_FLOAT);
    $capacity = filter_var($_POST['capacity'] ?? 0, FILTER_VALIDATE_INT);

    // Doğrulama
    if (empty($title) || empty($description) || empty($event_type) || empty($date) || empty($location)) {
        $error_message = "Tüm zorunlu alanları doldurun.";
    } elseif ($ticket_price === false || $ticket_price < 0) {
        $error_message = "Geçerli bir bilet fiyatı girin.";
    } elseif ($capacity === false || $capacity < 1) {
        $error_message = "Kapasite 1 veya daha büyük olmalı.";
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO events (title, description, event_type, date, location, ticket_price, capacity, remaining_capacity)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$title, $description, $event_type, $date, $location, $ticket_price, $capacity, $capacity]);
            $success_message = "Etkinlik başarıyla eklendi.";
            $_POST = []; // Formu sıfırla
        } catch (PDOException $e) {
            $error_message = "Etkinlik eklenirken hata: " . htmlspecialchars($e->getMessage());
        }
    }
}

// Etkinlikleri listele
try {
    $stmt = $pdo->query("SELECT * FROM events ORDER BY date DESC");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Etkinlikler yüklenirken hata: " . htmlspecialchars($e->getMessage());
    $events = [];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etkinlik Yönetimi</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Etkinlik Yönetimi</h2>

        <!-- Mesajlar -->
        <?php if ($success_message): ?>
            <div class="alert alert-success text-center"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <!-- Etkinlik Ekleme Formu -->
        <div class="card shadow mb-5">
            <div class="card-body">
                <h5 class="card-title">Yeni Etkinlik Ekle</h5>
                <form method="POST" class="row g-3">
                    <input type="hidden" name="add_event" value="1">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Etkinlik Başlığı</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Etkinlik Başlığı" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="event_type" class="form-label">Etkinlik Türü</label>
                        <input type="text" name="event_type" id="event_type" class="form-control" placeholder="Etkinlik Türü" value="<?php echo isset($_POST['event_type']) ? htmlspecialchars($_POST['event_type']) : ''; ?>" required>
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label">Etkinlik Açıklaması</label>
                        <textarea name="description" id="description" class="form-control" placeholder="Etkinlik Açıklaması" rows="3" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="date" class="form-label">Tarih ve Saat</label>
                        <input type="datetime-local" name="date" id="date" class="form-control" value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="location" class="form-label">Yer</label>
                        <input type="text" name="location" id="location" class="form-control" placeholder="Yer" value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="ticket_price" class="form-label">Bilet Fiyatı (₺)</label>
                        <input type="number" name="ticket_price" id="ticket_price" class="form-control" placeholder="Bilet Fiyatı" step="0.01" min="0" value="<?php echo isset($_POST['ticket_price']) ? htmlspecialchars($_POST['ticket_price']) : ''; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="capacity" class="form-label">Kapasite</label>
                        <input type="number" name="capacity" id="capacity" class="form-control" placeholder="Kapasite" min="1" value="<?php echo isset($_POST['capacity']) ? htmlspecialchars($_POST['capacity']) : ''; ?>" required>
                    </div>
                    <div class="col-md-4 d-grid">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle me-1"></i> Etkinlik Ekle</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Etkinlik Listesi -->
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
                                <td><?php echo htmlspecialchars($event['title'] ?? ''); ?></td>
                                <td><?php echo isset($event['date']) ? date('d.m.Y H:i', strtotime($event['date'])) : 'Tarih belirtilmemiş'; ?></td>
                                <td>
                                    <a href="?delete_id=<?php echo htmlspecialchars($event['id'] ?? ''); ?>" class="btn btn-sm btn-danger delete-event">
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

    <!-- Silme onayı için JavaScript -->
    <script>
        document.querySelectorAll('.delete-event').forEach(button => {
            button.addEventListener('click', function(event) {
                if (!confirm('Bu etkinliği silmek istediğinizden emin misiniz?')) {
                    event.preventDefault();
                }
            });
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php require '../includes/footer.php'; ?>