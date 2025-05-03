<?php
//bekleyen müşteri listesini döner
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['personel'])) {
    echo json_encode([]);
    exit;
}

$departman = $_SESSION['personel']['departman_adi'];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=kiosk;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT id, number FROM counter WHERE departman = ? AND status = 'waiting' ORDER BY created_at ASC");
    $stmt->execute([$departman]);

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
