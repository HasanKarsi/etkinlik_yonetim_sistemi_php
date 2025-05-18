<!-- Kapak -->
<h1 align="center">🎫 Etkinlik Yönetim Sistemi</h1>
<p align="center">
  <em>BMB315 Web Programlama dersi kapsamında geliştirilen bir dinamik web uygulaması.</em><br/>
  <strong>Teslim Tarihi:</strong> 26 Mayıs 2025<br/>
  <strong>Hazırlayan:</strong> Hasan Karşı<br/>
  <strong>Değerlendiren:</strong> Arş. Gör. Özge Albayrak Ünal
</p>

<hr/>

## 🚀 Proje Hakkında

Bu proje, kullanıcıların etkinlikleri görüntüleyip bilet satın alabileceği, yöneticilerin ise etkinlik ve duyuru yönetimi yapabileceği dinamik bir web sistemidir. Modern web teknolojileri kullanılarak geliştirilmiştir.

---

## 🌟 Özellikler

### 👤 Kullanıcılar İçin
- **Kayıt ve Giriş:** E-posta ve şifre ile kayıt olunur. Giriş için yönetici onayı gerekir. İlk girişte şifre değişimi zorunludur.
- **Ana Ekran:** Tarihe göre sıralanmış etkinlikler ve duyurular gösterilir. İlgi alanlarına göre öneriler sunulur.
- **Hava Durumu:** Etkinlik lokasyonuna göre OpenWeatherMap API üzerinden gösterilir.
- **Bilet Satın Alma:** Bilet türüne göre fiyat hesaplanır. Ödeme seçeneği ile satın alma tamamlanır.
- **Kontenjan Takibi:** Bilet alındığında kontenjan otomatik olarak güncellenir.

### 🛠️ Yönetici Paneli
- **Kullanıcı Onayı:** Yeni kullanıcıların onay süreci.
- **Etkinlik Yönetimi:** Etkinlik ekleme, düzenleme ve silme.
- **Duyuru Yönetimi:** Yeni duyurular oluşturma ve listeleme.

---

## 🛠️ Kullanılan Teknolojiler

### 🎨 Front-End
- HTML5
- CSS3
- JavaScript

### 🧠 Back-End
- PHP
- MySQL

### 🌐 Sunucu ve API’ler
- **Sunucu:** WampServer (Apache, PHP, MySQL)
- **API’ler:**
  - OpenWeatherMap (hava durumu)
  - Ticketmaster (etkinlik önerileri)

### 📁 Diğer
- Git, GitHub

---

## ⚙️ Kurulum

### 🔧 Gereksinimler
- WampServer (PHP 7.4+)
- Chrome, Firefox vb. bir tarayıcı
- OpenWeatherMap ve Ticketmaster API anahtarları

### 📌 Kurulum Adımları

1. **WampServer’ı Kurun**  
   [WampServer](https://www.wampserver.com/en/) indirip kurun ve `http://localhost` adresinde çalıştığını doğrulayın.

2. **Proje Dosyalarını Klonlayın**
   ```bash
   git clone https://github.com/HasanKarsi/etkinlik-yonetim-sistemi.git
   ```
   Ardından klasörü `C:\wamp64\www\` dizinine taşıyın.

3. **Veritabanı Kurulumu**

   `http://localhost/phpmyadmin` adresinden yeni bir veritabanı oluşturun:

   ```sql
   CREATE DATABASE etkinlik_yonetim;
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

   CREATE TABLE cart (
       id INT AUTO_INCREMENT PRIMARY KEY,
       user_id INT,
       event_id INT,
       ticket_type VARCHAR(50) NOT NULL,
       quantity INT NOT NULL,
       FOREIGN KEY (user_id) REFERENCES users(id),
       FOREIGN KEY (event_id) REFERENCES events(id)
   );
   ```

4. **Örnek Veriler**
   ```sql
   INSERT INTO users (email, password, is_approved, is_admin) VALUES
   ('admin@example.com', '$2y$10$examplehash', 1, 1),
   ('user@example.com', '$2y$10$examplehash', 1, 0);
   ```
   > 🔐 Not: Gerçek şifreler için `password_hash()` fonksiyonunu kullanmalısınız.

5. **API Anahtarlarını Tanımlayın**
   - `home.php` içindeki `apiKey` değişkenine OpenWeatherMap API anahtarınızı ekleyin.
   - Ticketmaster API için de ilgili endpoint URL’sini belirtin.

6. **Projeyi Çalıştırın**
   Tarayıcıda şu adrese gidin:  
   👉 `http://localhost/etkinlik_yonetim`

---

## 🖱️ Kullanım

- `register.php` üzerinden kullanıcı kaydı yapın.
- `index.php` ile giriş sağlayın (ilk girişte şifre değişimi istenir).
- `home.php` ile duyuruları ve etkinlikleri görüntüleyin.
- Hava durumuna göre plan yapabilir, biletleri sepete ekleyip satın alabilirsiniz.
- `cart.php` ile sepeti görüntüleyip ödeme işlemini tamamlayabilirsiniz.
- `admin/index.php` yönetici paneline yönlendirir.

---

## 🔌 API Entegrasyonu

| API              | Açıklama                                       |
|------------------|------------------------------------------------|
| **OpenWeatherMap** | Hava durumu verisi sağlar (`home.php`)       |
| **Ticketmaster**   | Etkinlik verileri listelenir (JSON formatında) |

---

## 📁 Proje Yapısı

```
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
```

---

## 🤝 Katkıda Bulunma

- Hataları bildirmek için bir **Issue** açabilirsiniz.
- Yeni özellik önerileri için **Pull Request** gönderebilirsiniz.

---

## 📜 Lisans

Bu proje yalnızca akademik amaçlıdır ve **BMB315 Web Programlama** dersi için geliştirilmiştir. Kod veya dökümanlar izinsiz paylaşılamaz.

---

## 📫 İletişim

Her türlü soru ve öneri için bana ulaşın:  
📧 [karsihasan25@gmail.com](mailto:karsihasan25@gmail.com)

---
