<?php
require 'includes/db.php';
require 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_approved = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = $user['is_admin'];
        $_SESSION['first_login'] = true; // İlk giriş kontrolü
        header('Location: home.php');
        exit;
    } else {
        echo "<p>Geçersiz e-posta, şifre veya hesap onaylanmadı.</p>";
    }
}
?>

<div class="form-container">
    <h2>Giriş Yap</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="E-posta" required>
        <input type="password" name="password" placeholder="Şifre" required>
        <button type="submit">Giriş Yap</button>
    </form>
</div>

<?php require 'includes/footer.php'; ?>