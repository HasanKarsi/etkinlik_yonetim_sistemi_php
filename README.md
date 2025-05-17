Etkinlik Yönetim Sistemi
Bu proje, BMB315 Web Programlama dersi kapsamında geliştirilen bir Etkinlik Yönetim Sistemidir. Sistem, kullanıcıların etkinlikleri görüntülemesine, bilet satın almasına ve yöneticilerin etkinlik ile duyuru yönetmesine olanak tanır. Proje, web teknolojileri kullanılarak dinamik bir web uygulaması olarak tasarlanmıştır ve sosyal medya entegrasyonu gibi modern özellikler içerir.
Proje, Atatürk Üniversitesi Mühendislik Fakültesi için hazırlanmış olup, teslim tarihi 26 Mayıs 2025’tir. Değerlendirme, Arş. Gör. Özge Albayrak Ünal tarafından yapılacaktır.
Özellikler
Kullanıcı Özellikleri

Kayıt ve Giriş: Kullanıcılar e-posta ve şifre ile kaydolur, yönetici onayı gerekir. İlk girişte şifre değişikliği zorunludur.
Ana Ekran: Etkinlikler ve duyurular tarih sırasına göre listelenir. Kullanıcı ilgi alanlarına göre öneriler alır.
Hava Durumu: OpenWeatherMap API ile etkinlik lokasyonuna göre hava durumu bilgisi gösterilir.
Bilet Satın Alma: Kullanıcılar etkinlik için bilet seçer, sepet ekranında bilet türüne göre fiyat hesaplanır ve ödeme yöntemi seçilir.
Kontenjan Yönetimi: Bilet alımı sonrası etkinlik kontenjanı anlık azalır.

Yönetici Özellikleri

Kullanıcı Onayı: Yeni kayıtlı kullanıcıları onaylar.
Etkinlik Yönetimi: Etkinlik ekleme, düzenleme, silme.
Duyuru Yönetimi: Duyuru ekleme, listeleme, silme.

Kullanılan Teknolojiler

Front-End:
HTML5
CSS3
JavaScript (etkileşimli özellikler için)


Back-End:
PHP (sunucu tarafı mantık ve veritabanı işlemleri)
MySQL (veritabanı yönetimi)


Sunucu: WampServer (Apache, PHP, MySQL)
API’ler:
OpenWeatherMap (hava durumu bilgisi API)
Ticketmaster (etkinlik verileri için  API)


Diğer: Git (versiyon kontrolü), GitHub (kod barındırma)

Kurulum
Gereksinimler

WampServer (Apache, PHP 7.4+, MySQL)
Modern bir web tarayıcısı (Chrome, Firefox vb.)
OpenWeatherMap API anahtarı (kaydol)
Ticketmaster API anahtarı (kaydol)

Adımlar

WampServer Kurulumu:

WampServer’ı indirin ve kurun.
WampServer’ı başlatın ve http://localhost adresinde çalıştığını doğrulayın.


Proje Dosyalarını Klonlama:
git clone https://github.com/kullanici/etkinlik-yonetim-sistemi.git


Dosyaları C:\wamp64\www\ kopyalayın.


Veritabanı Kurulumu:

http://localhost/phpmyadmin adresine gidin.
Yeni bir veritabanı oluşturun: etkinlik_yonetim.
Aşağıdaki SQL dosyasını çalıştırın:CREATE DATABASE etkinlik_yonetim;
USE etkinlik_yonetim;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_approved BOOLEAN DEFAULT 0,
    is_admin BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    event_type VARCHAR(50) NOT NULL,
    date DATETIME NOT NULL,
    location VARCHAR(100) NOT NULL,
    ticket_price DECIMAL(10,2) NOT NULL,
    capacity INT NOT NULL,
    remaining_capacity INT NOT NULL
);

CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    event_id INT,
    ticket_type VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    purchase_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (event_id) REFERENCES events(id)
);

CREATE TABLE interests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    interest VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    event_id INT,
    ticket_type VARCHAR(50) NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (event_id) REFERENCES events(id)
);

------------------------- Örnek veri-------------------------
INSERT INTO users (email, password, is_approved, is_admin) VALUES
('admin@example.com', '$2y$10$examplehash', 1, 1),
('user@example.com', '$2y$10$examplehash', 1, 0);


Not: Şifreler için gerçek hash’ler oluşturmak için PHP’nin password_hash() fonksiyonunu kullanın.


API Anahtarı Ekleme:

OpenWeatherMap’ten bir API anahtarı alın.
home.php dosyasındaki apiKey değişkenine anahtarı ekleyin:const apiKey = 'dc5214703c33d777c096b9387d20250c'; // Kendi anahtarınızı ekleyin
Ticketmaster’ten bir API anahtarı alın.
home.php dosyasındaki $api_url değişkenine api url'sini ekleyin



Proje Çalıştırma:

Tarayıcıda http://localhost/etkinlik_yonetim adresine gidin.
Yönetici hesabı ile giriş yapın (admin@example.com) veya yeni bir kullanıcı kaydedin.



Kullanım

Kayıt ve Giriş:

register.php üzerinden e-posta ve şifre ile kaydolun. Yönetici onayı bekleyin.
index.php üzerinden giriş yapın. İlk girişte şifrenizi değiştirin.


Ana Ekran:

Etkinlikleri ve duyuruları görüntüleyin.
İlgi alanlarınıza göre önerilen etkinlikleri görün.
Hava durumu bilgisi ile etkinlik planlamasını kontrol edin.
“Sepete Ekle” ile bilet satın alma işlemine başlayın.


Sepet:

Seçilen etkinlikleri ve bilet türlerini görün.
Ödeme yöntemi seçerek satın alma işlemini tamamlayın.


Yönetici Paneli:

admin/index.php üzerinden kullanıcı onayı, etkinlik ve duyuru yönetimi yapın.
Yeni etkinlik veya duyuru ekleyin, mevcutları silin.



API Entegrasyonu

OpenWeatherMap: Etkinlik lokasyonuna göre hava durumu bilgisi çeker. home.php içinde JavaScript ile entegre edilmiştir.
Ticketmaster API : alınan api bağlantısı ile direk url üzerinden  json formatında API çekilir ve önerilen etkinlikler kısmında güncel etkinlikler sıralanır

Proje Yapısı
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

Katkıda Bulunma

Hataları bildirmek veya yeni özellik önermek için bir Issue açın.
Kod katkısı için bir Pull Request gönderin.

Lisans
Bu proje, akademik amaçlarla geliştirilmiştir ve yalnızca BMB315 Web Programlama dersi için kullanılabilir. Kodların ve raporun başkalarıyla paylaşılmadığından emin olun.
İletişim
Sorularınız için: [karsihasan25@gmail.com]
