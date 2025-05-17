İşte README.md dosyanda kullanabileceğin şekilde düzenlenmiş ve güzelleştirilmiş haliyle Markdown formatında yapılandırılmış proje tanıtım metni:


---

Etkinlik Yönetim Sistemi

Bu proje, BMB315 Web Programlama dersi kapsamında geliştirilmiştir. Kullanıcılar etkinlikleri görüntüleyebilir, bilet satın alabilirken; yöneticiler etkinlik ve duyuruları yönetebilir. Proje, modern web teknolojileri ile hazırlanmış dinamik bir web uygulamasıdır.

> Teslim Tarihi: 26 Mayıs 2025
Hazırlayan: Atatürk Üniversitesi - Mühendislik Fakültesi
Değerlendiren: Arş. Gör. Özge Albayrak Ünal




---

Özellikler

Kullanıcı Özellikleri

Kayıt ve Giriş: E-posta ve şifre ile kayıt olunur. Yönetici onayı zorunludur. İlk girişte şifre değişikliği yapılmalıdır.

Ana Ekran: Etkinlikler ve duyurular tarih sırasına göre listelenir. Kullanıcıya ilgi alanlarına göre öneriler sunulur.

Hava Durumu: OpenWeatherMap API ile etkinlik lokasyonuna göre gösterilir.

Bilet Satın Alma: Bilet türüne göre fiyat hesaplanır, ödeme seçeneği sunulur.

Kontenjan Yönetimi: Bilet alındığında kontenjan otomatik güncellenir.


Yönetici Özellikleri

Kullanıcı Onayı: Yeni kayıtlı kullanıcıların onaylanması.

Etkinlik Yönetimi: Etkinlik ekleme, düzenleme, silme.

Duyuru Yönetimi: Duyuru ekleme, listeleme, silme.



---

Kullanılan Teknolojiler

Front-End

HTML5

CSS3

JavaScript


Back-End

PHP

MySQL


Sunucu ve API’ler

Sunucu: WampServer (Apache, PHP, MySQL)

API’ler:

OpenWeatherMap (hava durumu)

Ticketmaster (etkinlik verileri)



Diğer

Git, GitHub



---

Kurulum

Gereksinimler

WampServer (PHP 7.4+, MySQL)

Tarayıcı (Chrome, Firefox vb.)

OpenWeatherMap ve Ticketmaster API anahtarları


Adımlar

1. WampServer Kurulumu

WampServer’ı buradan indirip kurun.

http://localhost adresinde çalıştığını kontrol edin.


2. Proje Dosyalarını Klonlayın

git clone https://github.com/kullanici/etkinlik-yonetim-sistemi.git

Dosyaları C:\wamp64\www\ dizinine kopyalayın.


3. Veritabanı Kurulumu

http://localhost/phpmyadmin adresine gidin.

Yeni bir veritabanı oluşturun: etkinlik_yonetim

Aşağıdaki SQL komutlarını çalıştırın:


<details>
<summary>SQL Script (Tıklayarak Gör)</summary>CREATE DATABASE etkinlik_yonetim;
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

</details>4. Örnek Veriler

INSERT INTO users (email, password, is_approved, is_admin) VALUES
('admin@example.com', '$2y$10$examplehash', 1, 1),
('user@example.com', '$2y$10$examplehash', 1, 0);

> Not: Gerçek şifreler için password_hash() kullanın.



5. API Anahtarlarını Ekleyin

home.php içindeki apiKey değişkenine OpenWeatherMap anahtarınızı ekleyin.

Ticketmaster için API URL’sini ilgili değişkene girin.


6. Projeyi Çalıştırın

Tarayıcıdan http://localhost/etkinlik_yonetim adresine gidin.

Giriş yapmak için admin@example.com hesabını ya da yeni kullanıcı oluşturmayı deneyin.



---

Kullanım

Kayıt ve Giriş

register.php üzerinden kayıt olun.

index.php ile giriş yapın (ilk girişte şifre değiştirin).


Ana Sayfa

Etkinlik ve duyuruları görüntüleyin.

İlgi alanlarınıza göre önerilen etkinlikleri takip edin.

Hava durumunu inceleyerek plan yapın.

“Sepete Ekle” ile bilet alın.


Sepet

Seçilen etkinlikleri görüntüleyin.

Ödeme yöntemiyle işlemi tamamlayın.


Yönetici Paneli

admin/index.php ile yöneticilere özel işlemleri gerçekleştirin.



---

API Entegrasyonu

OpenWeatherMap: JavaScript ile home.php içinde entegredir.

Ticketmaster API: JSON formatında verilerle önerilen etkinlikler listelenir.



---

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


---

Katkıda Bulunma

Hataları bildirmek için bir Issue oluşturun.

Yeni özellikler için Pull Request gönderin.



---

Lisans

Bu proje yalnızca akademik amaçlarla, BMB315 Web Programlama dersi için geliştirilmiştir. Kodları veya raporu izinsiz paylaşmayınız.


---

İletişim

Her türlü soru ve öneri için:
karsihasan25@gmail.com


---

İstersen bu dosyayı .md olarak da dışa aktarabilirim. Yardımcı olayım mı?

