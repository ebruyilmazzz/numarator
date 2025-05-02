<?php
// Hataları görmek için:
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Yanıt tipi JSON olacak:
header('Content-Type: application/json');

// Veritabanına bağlan:
$host = 'localhost';
$dbname = 'numarator';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Veritabanına bağlanılamadı: ' . $e->getMessage()]);
    exit;
}

// Masa tipini POST ile al:
$masa_tipi = $_POST['masa_tipi'] ?? '';

if (!in_array($masa_tipi, ['masa1', 'masa2', 'masa3'])) {
    echo json_encode(['error' => 'Geçersiz masa tipi.']);
    exit;
}

// Her masa için ayrı tablo belirle:
$tablo_adi = match ($masa_tipi) {
    'masa1' => 'ticket_masa1',
    'masa2' => 'ticket_masa2',
    'masa3' => 'ticket_masa3',
    default => 'ticket_masa1'
};

// Son sıra numarasını al:
$query = $conn->query("SELECT MAX(number) as max_number FROM $tablo_adi");
$row = $query->fetch(PDO::FETCH_ASSOC);
$son_sayi = $row['max_number'] ?? 0;

// Yeni sıra numarasını oluştur:
$yeni_sayi = $son_sayi + 1;

// Veritabanına ekle:
$insert = $conn->prepare("INSERT INTO $tablo_adi (number, created_at) VALUES (?, NOW())");
$insert->execute([$yeni_sayi]);

// JSON yanıtı gönder:
echo json_encode(['number' => $yeni_sayi]);
?>
