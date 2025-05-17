// Hava Durumu API Çağrısı - Sayfa yüklendiğinde çalışır
document.addEventListener('DOMContentLoaded', () => {
    console.log('script.js yüklendi, hava durumu kodu çalışıyor...');

    // Hava durumu bilgisinin gösterileceği div elementini al
    const weatherDiv = document.getElementById('weather');
    
    // Eğer div bulunamazsa hata mesajı yaz ve işlemi durdur
    if (!weatherDiv) {
        console.log('Hata: #weather div bulunamadı, hava durumu kodu çalıştırılmadı.');
        return;
    }

    // API anahtarı ve şehir bilgisi
    const apiKey = 'dc5214703c33d777c096b9387d20250c'; 
    const city = 'Istanbul';
    console.log('Hava durumu API çağrısı yapılıyor: ', city);

    // OpenWeatherMap API'sine fetch ile istek yap
    fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`)
        .then(response => {
            // API yanıtı alındığında durum kodunu logla
            console.log('API yanıtı alındı, durum kodu:', response.status);

            // Eğer yanıt başarılı değilse hata fırlat
            if (!response.ok) {
                throw new Error(`HTTP hatası! Durum: ${response.status}`);
            }
            // JSON formatında yanıtı döndür
            return response.json();
        })
        .then(data => {
            // API'den dönen veriyi konsola yazdır
            console.log('API verisi:', data);

            // API'den hata kodu varsa fırlat
            if (data.cod && data.cod !== 200) {
                throw new Error(`API hatası: ${data.message}`);
            }

            // Hava durumu ikon kodunu al ve URL oluştur
            const iconCode = data.weather[0].icon;
            const iconUrl = `https://openweathermap.org/img/wn/${iconCode}@2x.png`;

            // weather div içine HTML olarak hava durumu bilgilerini yerleştir
            weatherDiv.innerHTML = `
                <div class="weather-icon mb-3">
                    <img src="${iconUrl}" alt="${data.weather[0].description}">
                </div>
                <h5><strong>${data.name}</strong>: ${data.weather[0].description}</h5>
                <p class="mb-0">Sıcaklık: ${data.main.temp}°C</p>
            `;
            console.log('Hava durumu verileri başarıyla yüklendi.');
        })
        .catch(error => {
            // Herhangi bir hata durumunda konsola hata yazdır ve kullanıcıya bildir
            console.error('Hava durumu alınamadı:', error);
            weatherDiv.innerHTML = `<p class="text-danger">Hava durumu bilgisi alınamadı: ${error.message}</p>`;
        });
});
