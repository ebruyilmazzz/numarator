<?php
session_start();

if (!isset($_SESSION['personel'])) {
    http_response_code(403);
    exit(json_encode(["error" => "Oturum geçersiz."]));
}

$departman = $_SESSION['personel']['departman_adi'];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=kiosk;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT number FROM counter WHERE departman = ? AND status = 'waiting' ORDER BY id ASC");
    $stmt->execute([$departman]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($data);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
