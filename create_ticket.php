<?php
ob_clean();
header('Content-Type: application/json');

ini_set('display_errors', 0);
error_reporting(0);

$host = 'localhost';
$dbname = 'kiosk';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Veritabanı bağlantı hatası: ' . $e->getMessage()]);
    exit;
}

$masa_tipi = $_POST['masa_tipi'] ?? '';
$gecerli_tipler = ['hasar', 'mekanik', 'onarim'];
if (!in_array($masa_tipi, $gecerli_tipler)) {
    echo json_encode(['error' => 'Geçersiz masa tipi.']);
    exit;
}

// SAAT KONTROLÜ: sadece 09:00 - 18:00 arası
/*$saat = (int)date('H');
if ($saat < 9 || $saat >= 18) {
    echo json_encode(['error' => 'Numaratör sadece 09:00 - 18:00 arasında çalışır.']);
    exit;
}*/

// Başlangıç sayısı belirleme
switch ($masa_tipi) {
    case 'hasar':   $baslangic_sayi = 100; break;
    case 'mekanik': $baslangic_sayi = 300; break;
    case 'onarim':  $baslangic_sayi = 500; break;
}

// BUGÜN oluşturulmuş en büyük numarayı al
$tarih = date('Y-m-d');
$stmt = $conn->prepare("SELECT MAX(number) as max_number FROM counter WHERE departman = ? AND DATE(created_at) = ?");
$stmt->execute([$masa_tipi, $tarih]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$son_sayi = $row['max_number'];

if ($son_sayi === null || $son_sayi < $baslangic_sayi) {
    $son_sayi = $baslangic_sayi - 1;
}

$yeni_sayi = $son_sayi + 1;

// personel alanı istenirse buraya $_POST['personel'] eklenebilir
$personel = '';

$insert = $conn->prepare("INSERT INTO counter (departman, number, created_at, personel) VALUES (?, ?, NOW(), ?)");
$insert->execute([$masa_tipi, $yeni_sayi, $personel]);

echo json_encode(['number' => $yeni_sayi]);
exit;
