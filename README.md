<!-- Giriş ve Kapak -->
<h1 align="center">🎟️ Etkinlik Yönetim Sistemi</h1>
<p align="center">
  <em>BMB315 Web Programlama Dersi Projesi</em><br/>
  <strong>Hazırlayan:</strong> Hasan Karşı • <strong>Değerlendiren:</strong> Arş. Gör. Özge Albayrak Ünal<br/>
  <strong>📅 Teslim Tarihi:</strong> 26 Mayıs 2025
</p>

<hr/>

<!-- Özellikler -->
<h2>🚀 Özellikler</h2>

<table>
  <tr>
    <th colspan="2" align="left">👤 Kullanıcı Özellikleri</th>
  </tr>
  <tr>
    <td><strong>Kayıt ve Giriş</strong></td>
    <td>E-posta ve şifre ile kayıt olunur, yönetici onayı zorunludur. İlk girişte şifre değişikliği yapılır.</td>
  </tr>
  <tr>
    <td><strong>Ana Ekran</strong></td>
    <td>Etkinlikler ve duyurular tarih sırasına göre listelenir, ilgi alanlarına göre öneriler gösterilir.</td>
  </tr>
  <tr>
    <td><strong>Hava Durumu</strong></td>
    <td>OpenWeatherMap API ile lokasyona özel gösterilir.</td>
  </tr>
  <tr>
    <td><strong>Bilet Satın Alma</strong></td>
    <td>Bilet türüne göre fiyat hesaplanır, ödeme seçeneği sunulur.</td>
  </tr>
  <tr>
    <td><strong>Kontenjan Yönetimi</strong></td>
    <td>Bilet alındığında kontenjan otomatik güncellenir.</td>
  </tr>
</table>

<br/>

<table>
  <tr>
    <th colspan="2" align="left">🛠️ Yönetici Özellikleri</th>
  </tr>
  <tr>
    <td><strong>Kullanıcı Onayı</strong></td>
    <td>Yeni kayıtlı kullanıcıların onaylanması.</td>
  </tr>
  <tr>
    <td><strong>Etkinlik Yönetimi</strong></td>
    <td>Etkinlik ekleme, düzenleme, silme işlemleri.</td>
  </tr>
  <tr>
    <td><strong>Duyuru Yönetimi</strong></td>
    <td>Duyuru ekleme, silme ve listeleme.</td>
  </tr>
</table>

<hr/>

<!-- Teknolojiler -->
<h2>💻 Kullanılan Teknolojiler</h2>

<table>
  <tr>
    <th>Katman</th>
    <th>Teknolojiler</th>
  </tr>
  <tr>
    <td><strong>Front-End</strong></td>
    <td>HTML5, CSS3, JavaScript</td>
  </tr>
  <tr>
    <td><strong>Back-End</strong></td>
    <td>PHP, MySQL</td>
  </tr>
  <tr>
    <td><strong>Sunucu</strong></td>
    <td>WampServer (Apache, PHP, MySQL)</td>
  </tr>
  <tr>
    <td><strong>API'ler</strong></td>
    <td>OpenWeatherMap, Ticketmaster</td>
  </tr>
  <tr>
    <td><strong>Versiyon Kontrol</strong></td>
    <td>Git, GitHub</td>
  </tr>
</table>

<hr/>

<!-- Kurulum -->
<h2>🔧 Kurulum</h2>

<h4>Gereksinimler</h4>
<ul>
  <li>WampServer (PHP 7.4+)</li>
  <li>Web Tarayıcı (Chrome, Firefox vb.)</li>
  <li>OpenWeatherMap ve Ticketmaster API anahtarları</li>
</ul>

<h4>Adımlar</h4>

<ol>
  <li>
    <strong>WampServer Kurulumu:</strong><br/>
    <a href="https://www.wampserver.com/en/">WampServer</a>'ı kurun ve <code>http://localhost</code> adresinde çalıştığını doğrulayın.
  </li>
  <li>
    <strong>Proje Dosyalarını Klonlayın:</strong><br/>
    <code>git clone https://github.com/HasanKarsi/etkinlik-yonetim-sistemi.git</code><br/>
    Dosyaları <code>C:\wamp64\www\</code> dizinine taşıyın.
  </li>
  <li>
    <strong>Veritabanı Kurulumu:</strong><br/>
    <ul>
      <li><a href="http://localhost/phpmyadmin">phpMyAdmin</a>'e gidin.</li>
      <li>Yeni veritabanı oluşturun: <code>etkinlik_yonetim</code></li>
      <li>Aşağıdaki SQL komutlarını çalıştırın:</li>
    </ul>
    <details>
      <summary><strong>SQL Script (Tıklayarak Göster)</strong></summary>

````sql
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
</details> 

</li> 

<li> 
  <strong>Örnek Veriler:</strong><br/> 
  <pre>
INSERT INTO users (email, password, is_approved, is_admin) VALUES
('admin@example.com', '$2y$10$examplehash', 1, 1),
('user@example.com', '$2y$10$examplehash', 1, 0);
  </pre> 
  <em>Not: Gerçek şifreler için <code>password_hash()</code> fonksiyonunu kullanın.</em> 
</li> 

<li> 
  <strong>API Anahtarları:</strong><br/> 
  <code>home.php</code> içinde <code>apiKey</code> değişkenine anahtarlarınızı ekleyin. 
</li> 

<li> 
  <strong>Projeyi Çalıştırın:</strong><br/> 
  Tarayıcıda <code>http://localhost/etkinlik_yonetim</code> adresine giderek giriş yapın. 
</li> 

</ol> 

<hr/> 

<!-- Kullanım --> 
<h2>📌 Kullanım</h2> 
<ul> 
  <li><strong>Kayıt ve Giriş:</strong> <code>register.php</code> ile kayıt, <code>index.php</code> ile giriş.</li> 
  <li><strong>Ana Sayfa:</strong> Etkinlik ve duyurular, hava durumu, bilet alma işlemleri.</li> 
  <li><strong>Sepet:</strong> Sepete eklenen biletler görüntülenir, ödeme yöntemi seçilerek işlem tamamlanır.</li> 
  <li><strong>Yönetici Paneli:</strong> <code>admin/index.php</code> ile yönetici işlemleri yapılır.</li> 
</ul> 

<hr/> 

<!-- API Entegrasyonu --> 
<h2>🔗 API Entegrasyonu</h2> 
<ul> 
  <li><strong>OpenWeatherMap:</strong> JavaScript ile <code>home.php</code> içinde entegre edilmiştir.</li> 
  <li><strong>Ticketmaster API:</strong> JSON formatında verilerle önerilen etkinlikler listelenmektedir.</li> 
</ul> 

<hr/> 

<!-- Proje Yapısı --> 
<h2>📁 Proje Yapısı</h2> 
<pre>
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
</pre> 

<hr/> 

<!-- Katkı --> 
<h2>🤝 Katkıda Bulunma</h2> 
<ul> 
  <li>Hataları bildirmek için bir <strong>Issue</strong> oluşturun.</li> 
  <li>Yeni özellikler için <strong>Pull Request</strong> gönderin.</li> 
</ul> 

<hr/> 

<!-- Lisans --> 
<h2>📜 Lisans</h2> 
<p>Bu proje yalnızca akademik amaçlarla, BMB315 Web Programlama dersi için geliştirilmiştir. Kodları veya raporu izinsiz paylaşmayınız.</p> 

<hr/> 

<!-- İletişim --> 
<h2>📫 İletişim</h2> 
<p>Her türlü soru ve öneri için: <a href="mailto:karsihasan25@gmail.com">karsihasan25@gmail.com</a></p>
