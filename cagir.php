<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

if (!isset($_SESSION['personel']) || 
    !isset($_SESSION['personel']['personel_adi']) || 
    !isset($_SESSION['personel']['departman_adi'])) {
    echo json_encode(["success" => false, "message" => "Personel oturum bilgisi eksik."]);
    exit();
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=kiosk;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $personel = $_SESSION['personel'];
    $personel_adi = $personel['personel_adi'];
    $departman_adi = $personel['departman_adi'];

    // Önceki çağırılan müşteriyi "finished" yap
    $pdo->prepare("UPDATE counter SET status = 'finished' WHERE departman = ? AND status = 'called'")
        ->execute([$departman_adi]);

    // Bekleyen ilk müşteriyi al
    $stmt = $pdo->prepare("SELECT * FROM counter WHERE departman = ? AND status = 'waiting' ORDER BY created_at ASC LIMIT 1");
    $stmt->execute([$departman_adi]);
    $musteri = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($musteri) {
        // Müşteriyi çağrıldı olarak işaretle
        $pdo->prepare("UPDATE counter SET status = 'called', personel = ? WHERE id = ?")
            ->execute([$personel_adi, $musteri['id']]);

        echo json_encode([
            "success" => true,
            "number" => $musteri['number'],
            "personel" => $personel_adi,
            "message" => "Müşteri çağrıldı: " . $musteri['number']
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Bekleyen müşteri yok."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Hata: " . $e->getMessage()]);
}
