<?php
ob_clean();
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

$masa_tipi = $_POST['masa_tipi'] ?? '';

if (!in_array($masa_tipi, ['hasar', 'mekanik', 'onarim'])) {
    echo json_encode(['error' => 'Geçersiz masa tipi.']);
    exit;
}

switch ($masa_tipi) {
    case 'hasar':
        $tablo_adi = 'hasar';
        $baslangic_sayi = 100;
        break;
    case 'mekanik':
        $tablo_adi = 'mekanik';
        $baslangic_sayi = 200;
        break;
    case 'onarim':
        $tablo_adi = 'onarim';
        $baslangic_sayi = 300;
        break;
    default:
        echo json_encode(['error' => 'Tanımsız masa tipi']);
        exit;
}

// En yüksek mevcut numarayı al
$query = $conn->query("SELECT MAX(number) as max_number FROM $tablo_adi");
$row = $query->fetch(PDO::FETCH_ASSOC);
$son_sayi = $row['max_number'];

// Eğer NULL dönerse veya başlangıçtan küçükse, başlangıçtan başlat
if ($son_sayi === null || $son_sayi < $baslangic_sayi) {
    $son_sayi = $baslangic_sayi - 1;
}

$yeni_sayi = $son_sayi + 1;

$insert = $conn->prepare("INSERT INTO $tablo_adi (number, created_at) VALUES (?, NOW())");
$insert->execute([$yeni_sayi]);

echo json_encode(['number' => $yeni_sayi]);
?>
