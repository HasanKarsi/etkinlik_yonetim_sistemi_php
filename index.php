<?php
require 'includes/db.php';
require 'includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_approved = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = $user['is_admin'];
        $_SESSION['first_login'] = true;
        header('Location: home.php');
        exit;
    } else {
        $error = "Geçersiz e-posta, şifre veya hesap onaylanmadı.";
    }
}
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card password-card shadow-sm p-4">
        <h2 class="card-title text-center mb-4">Giriş Yap</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="E-posta" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Şifre" required>
            </div>
            <button type="submit" class="btn btn-outline-primary w-100">Giriş Yap</button>
        </form>

        <div class="text-center mt-3">
            <small>Hesabınız yok mu? <a href="register.php">Kayıt Ol</a></small>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
