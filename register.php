<?php
// ========================================
// Bu sayfa, kullanıcıların sisteme kayıt olmalarını sağlar.
// Kayıt işlemi başarılıysa onay beklenir. Aynı e-posta adresiyle tekrar kayıt engellenir.
// ========================================

// Veritabanı bağlantısı
require 'includes/db.php';

// Sayfa başlığı, menüler ve stilleri içeren ortak bileşen dosyası
require 'includes/header.php';

// Bilgilendirme mesajı için boş değişkenler
$message = '';
$error = '';

// Form gönderildiğinde çalışacak blok
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kullanıcının gönderdiği e-posta adresini filtrele
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Şifreyi güvenli hale getirmek için hash (şifreleme) uygula
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        // Yeni kullanıcıyı veritabanına ekle
        $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->execute([$email, $password]);

        // Kayıt başarılıysa bilgilendirme mesajı göster
        $message = "Kayıt başarılı! Yönetici onayı bekleniyor.";
    } catch (PDOException $e) {
        // Eğer hata tekrarlanan kayıt (aynı e-posta) ise özel mesaj göster
        if ($e->getCode() == 23000) {
            $error = "Bu e-posta adresi zaten kayıtlı.";
        } else {
            // Diğer hatalar için genel hata mesajı
            $error = "Hata: " . $e->getMessage();
        }
    }
}
?>

<!-- ===================== -->
<!-- KAYIT FORMU ARAYÜZÜ -->
<!-- ===================== -->

<!-- Sayfa yüksekliği ve ortalama için Bootstrap kullanımı -->
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card password-card shadow-sm p-4">
        <!-- Sayfa başlığı -->
        <h2 class="card-title text-center mb-4">Kayıt Ol</h2>

        <!-- Başarılı ya da hatalı işlem sonrası mesajlar -->
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Kayıt formu -->
        <form method="POST">
            <!-- E-posta alanı -->
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="E-posta" required>
            </div>

            <!-- Şifre alanı -->
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Şifre" required>
            </div>

            <!-- Form gönder butonu -->
            <button type="submit" class="btn btn-outline-primary w-100">Kayıt Ol</button>
        </form>

        <!-- Giriş sayfasına yönlendirme bağlantısı -->
        <div class="text-center mt-3">
            <small>Zaten hesabınız var mı? <a href="index.php">Giriş Yap</a></small>
        </div>
    </div>
</div>

<!-- Ortak footer dosyasını dahil et -->
<?php require 'includes/footer.php'; ?>
