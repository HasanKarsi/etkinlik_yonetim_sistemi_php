<?php
// ========================================
// Bu sayfa, kullanıcının oturumunu sonlandırmak için kullanılır.
// Oturumu tamamen kapatır ve giriş sayfasına (index.php) yönlendirir.
// ========================================

// Oturumu başlat (varsa mevcut oturumu tanımak için gerekir)
session_start();

// Oturumu sonlandır (tüm session verilerini temizler)
session_destroy();

// Kullanıcıyı giriş sayfasına yönlendir
header('Location: index.php');
exit;
?>
