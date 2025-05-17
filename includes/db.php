<?php
// Bu dosya, veritabanı bağlantısını sağlar.
// MySQL veritabanına PDO kullanarak bağlanır ve bağlantı hatası durumunda işlem durdurulur.

$host = 'localhost';  // Veritabanı sunucusunun adresi
$dbname = 'etkinlik_yonetim_sistemi_db';  // Bağlanılacak veritabanı adı
$username = 'root';  // Veritabanı kullanıcı adı
$password = '';  // Veritabanı şifresi (boş bırakılmış)

// PDO (PHP Data Objects) ile MySQL veritabanına bağlanma denemesi
try {
    // PDO nesnesi oluşturulur ve hata modu istisna fırlatacak şekilde ayarlanır
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Bağlantı hatası oluşursa, hata mesajı ile script durdurulur
    die("Bağlantı hatası: " . $e->getMessage());
}
?>
