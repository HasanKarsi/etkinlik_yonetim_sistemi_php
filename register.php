<?php
require 'includes/db.php';
require 'includes/header.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->execute([$email, $password]);
        $message = "Kayıt başarılı! Yönetici onayı bekleniyor.";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $error = "Bu e-posta adresi zaten kayıtlı.";
        } else {
            $error = "Hata: " . $e->getMessage();
        }
    }
}
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card password-card shadow-sm p-4">
        <h2 class="card-title text-center mb-4">Kayıt Ol</h2>

        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="E-posta" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Şifre" required>
            </div>
            <button type="submit" class="btn btn-outline-primary w-100">Kayıt Ol</button>
        </form>

        <div class="text-center mt-3">
            <small>Zaten hesabınız var mı? <a href="index.php">Giriş Yap</a></small>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
