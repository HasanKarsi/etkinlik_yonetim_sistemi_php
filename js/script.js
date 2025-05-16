// Hava Durumu API Çağrısı
document.addEventListener('DOMContentLoaded', () => {
    console.log('script.js yüklendi, hava durumu kodu çalışıyor...');
    const weatherDiv = document.getElementById('weather');
    if (!weatherDiv) {
        console.log('Hata: #weather div bulunamadı, hava durumu kodu çalıştırılmadı.');
        return;
    }

    const apiKey = 'dc5214703c33d777c096b9387d20250c'; 
    const city = 'Istanbul';
    console.log('Hava durumu API çağrısı yapılıyor: ', city);

    fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`)
        .then(response => {
            console.log('API yanıtı alındı, durum kodu:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP hatası! Durum: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('API verisi:', data);
            if (data.cod && data.cod !== 200) {
                throw new Error(`API hatası: ${data.message}`);
            }
            const iconCode = data.weather[0].icon;
            const iconUrl = `https://openweathermap.org/img/wn/${iconCode}@2x.png`;
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
            console.error('Hava durumu alınamadı:', error);
            weatherDiv.innerHTML = `<p class="text-danger">Hava durumu bilgisi alınamadı: ${error.message}</p>`;
        });
});

