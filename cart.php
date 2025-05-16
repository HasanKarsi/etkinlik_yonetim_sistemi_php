<?php
require 'includes/db.php';
require 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit;
}

// Sepetten çıkar
if (isset($_GET['remove_cart_id'])) {
    $cart_id = (int)$_GET['remove_cart_id'];
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$cart_id, $_SESSION['user_id']]);
    header('Location: /cart.php'); // Sepet sayfasına geri yönlendir
    exit;
}

// Sepete ekle (Aynı event_id için tekrar eklemeyi önle)
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
    $ticket_type = $_POST['ticket_type'] ?? 'Standart';
    $quantity = (int)($_POST['quantity'] ?? 1);

    // Kontenjan kontrolü
    $stmt = $pdo->prepare("SELECT remaining_capacity, ticket_price FROM events WHERE id = ?");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch();

    if ($event && $event['remaining_capacity'] >= $quantity) {
        // Aynı event_id ve ticket_type için mevcut kaydı kontrol et
        $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND event_id = ? AND ticket_type = ?");
        $stmt->execute([$_SESSION['user_id'], $event_id, $ticket_type]);
        $existing_cart = $stmt->fetch();

        if ($existing_cart) {
            // Mevcut kaydı güncelle (quantity artır)
            $new_quantity = $existing_cart['quantity'] + $quantity;
            $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $stmt->execute([$new_quantity, $existing_cart['id']]);
        } else {
            // Yeni kayıt ekle
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, event_id, ticket_type, quantity) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $event_id, $ticket_type, $quantity]);
        }
    } else {
        echo "<div class='alert alert-danger text-center mx-auto' style='max-width: 500px;'>Yetersiz kontenjan veya etkinlik bulunamadı!</div>";
    }
}

// Sepet içeriğini çek
$stmt = $pdo->prepare("
    SELECT c.*, e.title, e.ticket_price
    FROM cart c
    JOIN events e ON c.event_id = e.id
    WHERE c.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll();

// Ödeme işlemi
$success_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $payment_method = $_POST['payment_method'];

    // Transaction başlat
    $pdo->beginTransaction();
    try {
        // event_id bazında gruplandırma
        $processed_events = []; // İşlenen event_id'leri takip etmek için
        foreach ($cart_items as $item) {
            // Kontenjan kontrolü
            $stmt = $pdo->prepare("SELECT remaining_capacity FROM events WHERE id = ?");
            $stmt->execute([$item['event_id']]);
            $event = $stmt->fetch();

            if ($event['remaining_capacity'] < $item['quantity']) {
                throw new Exception("Yetersiz kontenjan: " . htmlspecialchars($item['title']));
            }

            // Bilet kaydı
            $price = $item['ticket_type'] === 'VIP' ? $item['ticket_price'] * 1.5 : $item['ticket_price'];
            $stmt = $pdo->prepare("INSERT INTO tickets (user_id, event_id, ticket_type, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $item['event_id'], $item['ticket_type'], $price]);

            // Kapasiteyi sadece bir kez azalt (event_id daha önce işlenmediyse)
            if (!in_array($item['event_id'], $processed_events)) {
                $stmt = $pdo->prepare("UPDATE events SET remaining_capacity = remaining_capacity - 1 WHERE id = ?");
                $stmt->execute([$item['event_id']]);
                $processed_events[] = $item['event_id']; // event_id'yi işaretle
            }
        }

        // Sepeti temizle
        $pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$_SESSION['user_id']]);
        $pdo->commit();
        $success_message = "Ödeme başarılı! Biletleriniz kaydedildi.";
        header('Location: /home.php'); // Ödeme sonrası home.php'ye yönlendir
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<div class='alert alert-danger text-center mx-auto' style='max-width: 500px;'>Hata: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}
?>

<div class="container mt-5 mb-5">
    <div class="card shadow-lg p-4 mx-auto cart-card">
        <h2 class="card-title text-center mb-4">Sepetiniz</h2>

        <?php if ($success_message): ?>
            <div class="alert alert-success text-center"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (empty($cart_items)): ?>
            <div class="text-center p-4">
                <p class="text-muted">Sepetiniz şu anda boş.</p>
                <a href="/home.php" class="btn btn-outline-primary"><i class="fas fa-ticket-alt me-2"></i>Etkinliklere Göz At</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Etkinlik</th>
                            <th>Bilet Türü</th>
                            <th>Adet</th>
                            <th>Fiyat</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total = 0; ?>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['title']); ?></td>
                                <td><?php echo htmlspecialchars($item['ticket_type']); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>
                                    <?php
                                    $price = $item['ticket_type'] === 'VIP' ? $item['ticket_price'] * 1.5 : $item['ticket_price'];
                                    $subtotal = $price * $item['quantity'];
                                    $total += $subtotal;
                                    echo $subtotal . ' TL';
                                    ?>
                                </td>
                                <td>
                                    <a href="/cart.php?remove_cart_id=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Çıkar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="table-light">
                            <td colspan="4" class="text-end"><strong>Toplam</strong></td>
                            <td><strong><?php echo $total; ?> TL</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <form method="POST" class="mt-4" id="checkout-form">
                <div class="mb-3">
                    <label for="payment_method" class="form-label">Ödeme Yöntemi</label>
                    <select name="payment_method" id="payment_method" class="form-select" required>
                        <option value="credit_card">Kredi Kartı</option>
                        <option value="bank_transfer">Havale</option>
                    </select>
                </div>
                <div class="text-center">
                    <button type="submit" name="checkout" class="btn btn-primary" id="checkout-button">
                        <i class="fas fa-credit-card me-2"></i>Ödeme Yap
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>