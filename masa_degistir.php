<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['personel'])) {
    echo json_encode(['success' => false, 'message' => 'Oturum bulunamadı.']);
    exit;
}

$personel_id = $_SESSION['personel']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['desk_id'])) {
    $desk_id = (int)$_POST['desk_id'];

    try {
        $conn = new PDO("mysql:host=localhost;dbname=kiosk;charset=utf8", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Masa ID'yi oturuma kaydet!
        $_SESSION['desk_id'] = $desk_id;

        $stmt = $conn->prepare("UPDATE counter SET desk_id = ?, is_active = 1 WHERE personel_id = ?");
        $stmt->execute([$desk_id, $personel_id]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Geçersiz istek.']);
}
