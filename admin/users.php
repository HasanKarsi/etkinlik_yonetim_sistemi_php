<?php
require '../includes/db.php';
require '../includes/header.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: /index.php');
    exit;
}

// Kullanıcı onayı
if (isset($_GET['approve_id'])) {
    $stmt = $pdo->prepare("UPDATE users SET is_approved = 1 WHERE id = ?");
    $stmt->execute([$_GET['approve_id']]);
    echo "<div class='alert alert-success text-center mx-auto' style='max-width: 500px;'>Kullanıcı başarıyla onaylandı!</div>";
}

// Kullanıcıları listele
$users = $pdo->query("SELECT * FROM users WHERE is_approved = 0")->fetchAll();
?>

<div class="container mt-5 mb-5">
    <h2 class="mb-4 text-center">Kullanıcı Yönetimi</h2>
    
    <?php if (empty($users)): ?>
        <div class="card shadow-sm p-4 text-center">
            <p class="mb-0 text-muted">Onay bekleyen kullanıcı bulunmamaktadır.</p>
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>E-posta</th>
                                <th>Kayıt Tarihi</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                                    <td>
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
</div