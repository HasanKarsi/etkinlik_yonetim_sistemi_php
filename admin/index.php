<?php
require '../includes/db.php';
require '../includes/header.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Paneli</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .admin-panel {
            margin-top: 4rem;
            margin-bottom: 4rem;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(to right, #4e73df, #224abe);
            color: white;
            border-radius: 15px 15px 0 0;
            text-align: center;
            padding: 1.5rem;
        }
        .list-group-item {
            border: none;
            padding: 1rem;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
        .list-group-item:hover {
            transform: translateY(-5px);
            background-color: #f8f9fa;
        }
        .btn-custom {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            padding: 0.75rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .btn-custom i {
            margin-right: 0.5rem;
        }
        .btn-users { border-color: #007bff; color: #007bff; }
        .btn-users:hover { background-color: #007bff; color: white; }
        .btn-events { border-color: #28a745; color: #28a745; }
        .btn-events:hover { background-color: #28a745; color: white; }
        .btn-announcements { border-color: #ffc107; color: #ffc107; }
        .btn-announcements:hover { background-color: #ffc107; color: white; }
        @media (max-width: 576px) {
            .card-header h2 { font-size: 1.5rem; }
            .btn-custom { font-size: 1rem; }
        }
    </style>
</head>
<body>
    <div class="container admin-panel">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0"><i class="fas fa-cog me-2"></i> Yönetici Paneli</h2>
            </div>
            <div class="card-body p-4">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <a href="users.php" class="btn btn-custom btn-users w-100">
                            <i class="fas fa-users"></i> Kullanıcı Yönetimi
                        </a>
                    </li>
                    <li class="list-group-item">
                        <a href="events.php" class="btn btn-custom btn-events w-100">
                            <i class="fas fa-calendar-alt"></i> Etkinlik Yönetimi
                        </a>
                    </li>
                    <li class="list-group-item">
                        <a href="announcements.php" class="btn btn-custom btn-announcements w-100">
                            <i class="fas fa-bullhorn"></i> Duyuru Yönetimi
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS ve Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php require '../includes/footer.php'; ?>