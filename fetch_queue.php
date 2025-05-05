<?php
session_start();

if (!isset($_SESSION['personel'])) {
    echo json_encode([]);
    exit;
}

$personel_id = $_SESSION['personel']['id'];

try {
    $conn = new PDO("mysql:host=localhost;dbname=kiosk;charset=utf8", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Personelin kendi departmanını alalım
    $stmt = $conn->prepare("SELECT section_id FROM personnel WHERE id = ?");
    $stmt->execute([$personel_id]);
    $departman_id = $stmt->fetchColumn();

    // Bu departmandaki bekleyen müşterileri çek
    $stmt = $conn->prepare("SELECT id, number FROM counter WHERE departman_id = ? AND status = 'waiting' ORDER BY id ASC");
    $stmt->execute([$departman_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($rows);

} catch (PDOException $e) {
    echo json_encode([]);
}
