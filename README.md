

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Etkinlik Yönetim Sistemi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            line-height: 1.6;
        }
        h1, h2, h3 {
            color: #2F3E28;
        }
        pre {
            background-color: #f4f4f4;
            padding: 10px;
            border-left: 4px solid #BC6C25;
            overflow-x: auto;
        }
        ul {
            margin-left: 20px;
        }
        code {
            background-color: #eee;
            padding: 2px 4px;
            border-radius: 3px;
        }
    </style>
</head>
<body>

    <h1>Etkinlik Yönetim Sistemi</h1>
    <p>
        Bu proje, <strong>BMB315 Web Programlama</strong> dersi kapsamında geliştirilen bir Etkinlik Yönetim Sistemidir. 
        Sistem, kullanıcıların etkinlikleri görüntülemesine, bilet satın almasına ve yöneticilerin etkinlik ile duyuru yönetmesine olanak tanır. 
        Proje, web teknolojileri kullanılarak dinamik bir web uygulaması olarak tasarlanmıştır ve sosyal medya entegrasyonu gibi modern özellikler içerir.
    </p>
    <p>
        Proje, Atatürk Üniversitesi Mühendislik Fakültesi için hazırlanmış olup, teslim tarihi <strong>26 Mayıs 2025</strong>’tir. 
        Değerlendirme, <strong>Arş. Gör. Özge Albayrak Ünal</strong> tarafından yapılacaktır.
    </p>

    <h2>Kullanıcı Özellikleri</h2>
    <ul>
        <li><strong>Kayıt ve Giriş:</strong> E-posta ve şifre ile kayıt. Yönetici onayı gerekir. İlk girişte şifre değişikliği zorunludur.</li>
        <li><strong>Ana Ekran:</strong> Etkinlikler ve duyurular tarih sırasına göre listelenir. İlgi alanlarına göre öneriler sunulur.</li>
        <li><strong>Hava Durumu:</strong> OpenWeatherMap API ile etkinlik lokasyonuna göre hava durumu bilgisi gösterilir.</li>
        <li><strong>Bilet Satın Alma:</strong> Bilet seçilir, sepet ekranında fiyat hesaplanır ve ödeme yöntemi seçilir.</li>
        <li><strong>Kontenjan Yönetimi:</strong> Bilet alımı sonrası kontenjan güncellenir.</li>
    </ul>

    <h2>Yönetici Özellikleri</h2>
    <ul>
        <li><strong>Kullanıcı Onayı</strong></li>
        <li><strong>Etkinlik Yönetimi:</strong> Ekleme, düzenleme, silme</li>
        <li><strong>Duyuru Yönetimi:</strong> Ekleme, listeleme, silme</li>
    </ul>

    <h2>Kullanılan Teknolojiler</h2>
    <h3>Front-End:</h3>
    <ul>
        <li>HTML5</li>
        <li>CSS3</li>
        <li>JavaScript</li>
    </ul>
    <h3>Back-End:</h3>
    <ul>
        <li>PHP</li>
        <li>MySQL</li>
    </ul>
    <h3>Sunucu:</h3>
    <ul>
        <li>WampServer (Apache, PHP, MySQL)</li>
    </ul>
    <h3>API'ler:</h3>
    <ul>
        <li>OpenWeatherMap</li>
        <li>Ticketmaster</li>
    </ul>
    <h3>Diğer:</h3>
    <ul>
        <li>Git</li>
        <li>GitHub</li>
    </ul>

    <h2>Kurulum</h2>
    <h3>Gereksinimler</h3>
    <ul>
        <li>WampServer (Apache, PHP 7.4+, MySQL)</li>
        <li>Modern tarayıcı (Chrome, Firefox vb.)</li>
        <li>OpenWeatherMap API anahtarı</li>
        <li>Ticketmaster API anahtarı</li>
    </ul>

    <h3>Adımlar</h3>
    <h4>1. WampServer Kurulumu:</h4>
    <ul>
        <li>WampServer’ı indirin ve kurun.</li>
        <li>http://localhost adresinde çalıştığını doğrulayın.</li>
    </ul>

    <h4>2. Proje Dosyalarını Klonlama:</h4>
    <pre><code>git clone https://github.com/kullanici/etkinlik-yonetim-sistemi.git</code></pre>
    <p>Dosyaları <code>C:\wamp64\www\</code> dizinine kopyalayın.</p>

    <h4>3. Veritabanı Kurulumu:</h4>
    <p><code>http://localhost/phpmyadmin</code> adresine gidin. Yeni bir veritabanı oluşturun: <code>etkinlik_yonetim</code></p>
    <pre><code>
CREATE DATABASE etkinlik_yonetim;
USE etkinlik_yonetim;
-- ardından tabloları oluşturun (users, events, announcements, tickets, interests, cart)
    </code></pre>

    <h4>4. Örnek Veri:</h4>
    <pre><code>
INSERT INTO users (email, password, is_approved, is_admin) VALUES
('admin@example.com', '$2y$10$examplehash', 1, 1),
('user@example.com', '$2y$10$examplehash', 1, 0);
    </code></pre>
    <p><strong>Not:</strong> Gerçek şifreler için <code>password_hash()</code> fonksiyonunu kullanın.</p>

    <h4>5. API Anahtarları:</h4>
    <ul>
        <li>OpenWeatherMap API anahtarını <code>home.php</code> dosyasındaki <code>apiKey</code> değişkenine ekleyin.</li>
        <li>Ticketmaster API anahtarını <code>$api_url</code> değişkenine ekleyin.</li>
    </ul>

    <h4>6. Projeyi Çalıştırma:</h4>
    <ul>
        <li>Tarayıcıda <code>http://localhost/etkinlik_yonetim</code> adresine gidin.</li>
        <li><code>admin@example.com</code> ile giriş yapın veya yeni kullanıcı kaydedin.</li>
    </ul>

    <h2>Kullanım</h2>
    <ul>
        <li><strong>Kayıt ve Giriş:</strong> <code>register.php</code> üzerinden kayıt olun. <code>index.php</code> üzerinden giriş yapın.</li>
        <li><strong>Ana Ekran:</strong> Etkinlikleri ve duyuruları görüntüleyin. İlgi alanına göre öneriler alın.</li>
        <li><strong>Sepet:</strong> Etkinlikleri ve bilet türlerini görüntüleyin, ödeme yapın.</li>
        <li><strong>Yönetici Paneli:</strong> <code>admin/index.php</code> üzerinden kullanıcı ve içerik yönetimi yapın.</li>
    </ul>

    <h2>API Entegrasyonu</h2>
    <ul>
        <li><strong>OpenWeatherMap:</strong> Hava durumu bilgisi <code>home.php</code> içinde JavaScript ile çekilir.</li>
        <li><strong>Ticketmaster:</strong> Güncel etkinlikler JSON formatında alınır ve öneri listesinde gösterilir.</li>
    </ul>

    <h2>Proje Yapısı</h2>
    <pre><code>
etkinlik_yonetim/
├── css/
│   └── style.css
├── js/
│   └── script.js
├── images/
├── includes/
│   ├── db.php
│   ├── header.php
│   └── footer.php
├── api/
│   └── fetch_events.php
├── admin/
│   ├── index.php
│   ├── users.php
│   ├── events.php
│   └── announcements.php
├── index.php
├── register.php
├── home.php
├── cart.php
└── logout.php
    </code></pre>

    <h2>Katkıda Bulunma</h2>
    <ul>
        <li>Hatalar veya yeni özellikler için Issue açın.</li>
        <li>Kod katkısı için Pull Request gönderin.</li>
    </ul>

    <h2>Lisans</h2>
    <p>Bu proje, akademik amaçlarla geliştirilmiştir ve yalnızca BMB315 Web Programlama dersi için kullanılabilir.</p>

    <h2>İletişim</h2>
    <p>Her türlü soru için: <a href="mailto:karsihasan25@gmail.com">karsihasan25@gmail.com</a></p>

</body>
</html>

Bu yapıyı bir .html dosyasına kaydedip tarayıcıda açarak güzel biçimlendirilmiş bir tanıtım sayfası olarak kullanabilirsin. İstersen Markdown formatına uygun versiyonunu da sağlayabilirim.

