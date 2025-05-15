<?php session_start(); ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etkinlik Yönetim Sistemi</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Giriş</a>
            <a href="register.php">Kayıt</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="home.php">Ana Sayfa</a>
                <a href="cart.php">Sepet</a>
                <?php if ($_SESSION['is_admin']): ?>
                    <a href="admin/index.php">Yönetici Paneli</a>
                <?php endif; ?>
                <a href="logout.php">Çıkış</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>