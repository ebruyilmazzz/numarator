<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// Oturum kontrolü
if (!isset($_SESSION['personel']) || !isset($_SESSION['personel']['id'])) {
    echo json_encode(["success" => false, "message" => "Personel oturum bilgisi eksik."]);
    exit();
}

// Masa kontrolü
if (!isset($_SESSION['desk_id'])) {
    echo json_encode(["success" => false, "message" => "Masa bilgisi eksik. Lütfen masa seçiniz."]);
    exit();
}
$desk_id = $_SESSION['desk_id'];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=kiosk;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $personel = $_SESSION['personel'];
    $personel_adi = $personel['personel_adi'];
    $personel_id = $personel['id'];
    $desk_id = $_SESSION['desk_id'];

    // Departman adı alınıyor (istekte varsa, yoksa kendi departmanı)
    $departman_adi = isset($_GET['departman']) ? $_GET['departman'] : $personel['departman_adi'];

    // Departman ID'sini al
    $stmt = $pdo->prepare("SELECT id FROM department WHERE departman_adi = ?");
    $stmt->execute([$departman_adi]);
    $departman = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$departman) {
        echo json_encode(["success" => false, "message" => "Geçersiz departman adı."]);
        exit();
    }

    $departman_id = $departman['id'];

    // Önceki çağırılan müşteriyi bitir
    $pdo->prepare("UPDATE counter SET status = 'finished' WHERE departman_id = ? AND status = 'called'")
        ->execute([$departman_id]);

    // Bekleyen ilk müşteriyi bul
    $stmt = $pdo->prepare("SELECT * FROM counter WHERE departman_id = ? AND status = 'waiting' ORDER BY created_at ASC LIMIT 1");
    $stmt->execute([$departman_id]);
    $musteri = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($musteri) {
        // Çağırılan müşteriyi güncelle (status, personel_id, desk_id)
        $pdo->prepare("UPDATE counter SET status = 'called', personel_id = ?, desk_id = ? WHERE id = ?")
            ->execute([$personel_id, $desk_id, $musteri['id']]);

        echo json_encode([
            "success" => true,
            "number" => $musteri['number'],
            "personel" => $personel_adi,
            "departman" => $departman_adi,
            "message" => "Müşteri çağrıldı: " . $musteri['number']
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Bekleyen müşteri bulunmamaktadır."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Hata: " . $e->getMessage()]);
}
