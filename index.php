<?php
// ========================================
// Bu sayfa, kullanıcıların sisteme giriş yapmalarını sağlar.
// Giriş bilgileri doğrulanır ve onaylı bir kullanıcı ise oturum başlatılır.
// ========================================

// Veritabanı bağlantısını içerir
require 'includes/db.php';

// Sayfa başlığı, stil ve menüler gibi ortak HTML bileşenleri içeren dosya
require 'includes/header.php';

// Hata mesajı için değişken
$error = '';

// Form gönderildiğinde çalışacak blok
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // E-posta'yı filtreleyerek zararlı karakterlerden arındır
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Kullanıcının gönderdiği şifre
    $password = $_POST['password'];

    // E-posta ile eşleşen ve onaylı (is_approved = 1) kullanıcıyı sorgula
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_approved = 1");
    $stmt->execute([$email]);

    // Veritabanından gelen kullanıcı bilgilerini al
    $user = $stmt->fetch();

    // Kullanıcı bulunduysa ve şifre doğruysa oturum başlat
    if ($user && password_verify($password, $user['password'])) {
        // Oturum bilgilerini sakla
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = $user['is_admin'];
        $_SESSION['first_login'] = true;

        // Ana sayfaya yönlendir
        header('Location: home.php');
        exit;
    } else {
        // Hatalı giriş mesajı
        $error = "Geçersiz e-posta, şifre veya hesap onaylanmadı.";
    }
}
?>

<!-- ===================== -->
<!-- GİRİŞ FORMU ARAYÜZÜ -->
<!-- ===================== -->

<!-- Sayfayı ortalamak için Bootstrap container ve flex yapı -->
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <!-- Giriş kartı -->
    <div class="card password-card shadow-sm p-4">
        <!-- Başlık -->
        <h2 class="card-title text-center mb-4">Giriş Yap</h2>

        <!-- Hatalı giriş mesajı -->
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Giriş formu -->
        <form method="POST">
            <!-- E-posta giriş alanı -->
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="E-posta" required>
            </div>

            <!-- Şifre giriş alanı -->
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Şifre" required>
            </div>

            <!-- Gönderme butonu -->
            <button type="submit" class="btn btn-outline-primary w-100">Giriş Yap</button>
        </form>

        <!-- Kayıt olma bağlantısı -->
        <div class="text-center mt-3">
            <small>Hesabınız yok mu? <a href="register.php">Kayıt Ol</a></small>
        </div>
    </div>
</div>

<!-- Ortak footer dosyasını dahil et -->
<?php require 'includes/footer.php'; ?>
