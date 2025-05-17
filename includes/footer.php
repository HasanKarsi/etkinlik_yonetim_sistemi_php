<!--
Bu sayfa yapısının son kısmı, sayfanın ana içeriğini (main) kapatır ve altında bir footer (altbilgi) bölümünü içerir.
Footer, sayfanın alt kısmında sabit görünür ve sayfa sahibine ait telif hakkı bilgisi ile mevcut yılı dinamik olarak gösterir.
Ayrıca, footer stil olarak degrade arka plan, beyaz yazı rengi ve hafif gölge efekti ile görsel olarak öne çıkarılmıştır.
-->

        </main> <!-- Ana içerik alanının sonu -->

        <footer class="footer text-center py-4" 
                style="background: linear-gradient(to right, #4e73df, #224abe); 
                       color: white; 
                       box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1); 
                       border-top-left-radius: 20px; 
                       border-top-right-radius: 20px;">
            <!-- Telif hakkı bilgisi ve güncel yıl -->
            <p class="mb-0">© Hasan Karşı, Etkinlik Yönetim Sistemi, <?php echo date('Y'); ?></p>
        </footer>

    </div> <!-- wrapper (sayfa genel kapsayıcı) bitişi -->

</body>
</html>
