<?php
require 'includes/db.php';
require 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->execute([$email, $password]);
        echo "<p>Kayıt başarılı! Yönetici onayı bekleniyor.</p>";
    } catch (PDOException $e) {
        echo "<p>Hata: " . $e->getMessage() . "</p>";
    }
}
?>

<div class="form-container">
    <h2>Kayıt Ol</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="E-posta" required>
        <input type="password" name="password" placeholder="Şifre" required>
        <button type="submit">Kayıt Ol</button>
    </form>
</div>

<?php require 'includes/footer.php'; ?>