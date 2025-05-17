<?php
/**
 * Sayfa: admin/users.php
 * Amaç: Yönetici tarafından onay bekleyen kullanıcıların listelendiği ve onaylanabildiği kullanıcı yönetim paneli.
 * Bu sayfada is_approved = 0 olan kullanıcılar listelenir. Yönetici, kullanıcıyı "Onayla" butonuyla onaylayabilir.
 */

// Veritabanı bağlantısı dahil edilir
require '../includes/db.php';

// Ortak header dosyası dahil edilir (oturum kontrolü ve üst kısım içerikleri için)
require '../includes/header.php';

// Yönetici kontrolü: Eğer kullanıcı oturumu yoksa veya yönetici değilse, ana sayfaya yönlendir
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: /index.php');
    exit;
}

// Kullanıcı onaylama işlemi: Eğer URL'de approve_id parametresi varsa,
// o kullanıcı için is_approved sütunu 1 olarak güncellenir.
if (isset($_GET['approve_id'])) {
    $stmt = $pdo->prepare("UPDATE users SET is_approved = 1 WHERE id = ?");
    $stmt->execute([$_GET['approve_id']]);

    // Başarı mesajı gösterilir
    echo "<div class='alert alert-success text-center mx-auto' style='max-width: 500px;'>Kullanıcı başarıyla onaylandı!</div>";
}

// Veritabanından onay bekleyen (is_approved = 0) tüm kullanıcıları çek
$users = $pdo->query("SELECT * FROM users WHERE is_approved = 0")->fetchAll();
?>

<!-- Kullanıcı yönetimi paneli -->
<div class="container mt-5 mb-5">
    <h2 class="mb-4 text-center">Kullanıcı Yönetimi</h2>

    <!-- Eğer onay bekleyen kullanıcı yoksa bilgi mesajı göster -->
    <?php if (empty($users)): ?>
        <div class="card shadow-sm p-4 text-center">
            <p class="mb-0 text-muted">Onay bekleyen kullanıcı bulunmamaktadır.</p>
        </div>

    <!-- Onay bekleyen kullanıcılar varsa tablo ile listele -->
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>E-posta</th> <!-- Kullanıcının e-posta adresi -->
                                <th>Kayıt Tarihi</th> <!-- Kullanıcının kayıt tarihi -->
                                <th>İşlem</th> <!-- Onayla butonu için sütun -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <!-- HTML özel karakterlerden koruma ile e-posta gösterimi -->
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                                    <td>
                                        <!-- Onayla butonu: tıklanınca sayfa ?approve_id= kullanıcı ID ile reload olur -->
                                        <a href="?approve_id=<?php echo $user['id']; ?>" class="btn btn-success btn-sm">
                                            <i class="fas fa-check me-1"></i> Onayla
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
