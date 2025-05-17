<?php
// Oturumu ve veritabanı bağlantısını başlat
require '../includes/db.php';
require '../includes/header.php';

// Admin girişi yapılmış mı kontrol et; yapılmamışsa ana sayfaya yönlendir
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../index.php');
    exit;
}

// Mesajları göstermek için değişkenler tanımlanıyor
$success_message = '';
$error_message = '';

// Duyuru silme işlemi (GET ile gelen delete_id kontrolü)
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];

    try {
        // ID'ye göre duyuruyu sil
        $stmt = $pdo->prepare("DELETE FROM announcements WHERE id = ?");
        $stmt->execute([$delete_id]);

        // Silme başarılıysa mesaj göster
        if ($stmt->rowCount() > 0) {
            $success_message = "Duyuru başarıyla silindi.";
        } else {
            $error_message = "Silinecek duyuru bulunamadı.";
        }
    } catch (PDOException $e) {
        // Hata durumunda mesaj göster
        $error_message = "Duyuru silinirken hata: " . htmlspecialchars($e->getMessage());
    }
}

// Duyuru ekleme işlemi (POST isteği kontrolü)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_announcement'])) {
    // Formdan gelen verileri temizle
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    // Girdi doğrulama
    if (empty($title) || empty($content)) {
        $error_message = "Başlık ve içerik alanlarını doldurun.";
    } elseif (strlen($title) > 100) {
        $error_message = "Başlık 100 karakterden uzun olamaz.";
    } else {
        try {
            // Yeni duyuruyu ekle
            $stmt = $pdo->prepare("
                INSERT INTO announcements (title, content, created_at)
                VALUES (?, ?, CURRENT_TIMESTAMP)
            ");
            $stmt->execute([$title, $content]);
            $success_message = "Duyuru başarıyla eklendi.";
            $_POST = []; // Formu sıfırla
        } catch (PDOException $e) {
            $error_message = "Duyuru eklenirken hata: " . htmlspecialchars($e->getMessage());
        }
    }
}

// Duyuruları listele
try {
    $stmt = $pdo->query("SELECT * FROM announcements ORDER BY created_at DESC");
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Duyurular yüklenirken hata: " . htmlspecialchars($e->getMessage());
    $announcements = [];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duyuru Yönetimi</title>
    <!-- Bootstrap CSS ve FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Duyuru Yönetimi</h2>

        <!-- Başarı veya hata mesajı varsa göster -->
        <?php if ($success_message): ?>
            <div class="alert alert-success text-center"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <!-- Yeni duyuru ekleme formu -->
        <div class="card shadow mb-5">
            <div class="card-body">
                <h5 class="card-title">Yeni Duyuru Ekle</h5>
                <form method="POST" class="row g-3">
                    <!-- Formun gönderildiğini anlamak için gizli input -->
                    <input type="hidden" name="add_announcement" value="1">

                    <div class="col-12">
                        <label for="title" class="form-label">Duyuru Başlığı</label>
                        <input type="text" name="title" id="title" class="form-control" maxlength="100"
                            placeholder="Duyuru Başlığı"
                            value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" required>
                    </div>

                    <div class="col-12">
                        <label for="content" class="form-label">Duyuru İçeriği</label>
                        <textarea name="content" id="content" class="form-control" rows="6"
                            placeholder="Duyuru İçeriği" required><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                    </div>

                    <div class="col-12 d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> Duyuru Ekle
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Duyuru listesi -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Başlık</th>
                        <th>İçerik</th>
                        <th>Oluşturma Tarihi</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($announcements)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">Henüz duyuru bulunmamaktadır.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($announcements as $announcement): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($announcement['title'] ?? ''); ?></td>
                                <td>
                                    <?php
                                    $content = $announcement['content'] ?? '';
                                    echo htmlspecialchars(strlen($content) > 100 ? substr($content, 0, 100) . '...' : $content);
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo isset($announcement['created_at']) 
                                        ? date('d.m.Y H:i', strtotime($announcement['created_at'])) 
                                        : 'Tarih belirtilmemiş';
                                    ?>
                                </td>
                                <td>
                                    <a href="?delete_id=<?php echo htmlspecialchars($announcement['id'] ?? ''); ?>"
                                       class="btn btn-sm btn-danger delete-announcement">
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

    <!-- Silme işlemi için JavaScript onay kutusu -->
    <script>
        document.querySelectorAll('.delete-announcement').forEach(button => {
            button.addEventListener('click', function(event) {
                if (!confirm('Bu duyuruyu silmek istediğinizden emin misiniz?')) {
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
