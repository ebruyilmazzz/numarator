<?php
ob_clean();
header('Content-Type: application/json');

// DEBUG MODU: true yaparsan log.txt'ye yazar, false yaparsan yazmaz
$DEBUG_MODE = false;

if ($DEBUG_MODE) {
    file_put_contents("log.txt", "Gelen veri: " . json_encode($_POST) . "\n", FILE_APPEND);
}

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

// departman_id'yi al
$stmt = $conn->prepare("SELECT id FROM department WHERE LOWER(departman_adi) = LOWER(?) LIMIT 1");
$stmt->execute([$masa_tipi]);
$departman = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$departman) {
    echo json_encode(['error' => 'Departman bulunamadı.']);
    exit;
}

$departman_id = $departman['id'];

// Başlangıç sayısını belirle
switch ($masa_tipi) {
    case 'hasar':   $baslangic_sayi = 100; break;
    case 'mekanik': $baslangic_sayi = 300; break;
    case 'onarim':  $baslangic_sayi = 500; break;
}

$tarih = date('Y-m-d');
$stmt = $conn->prepare("SELECT MAX(number) as max_number FROM counter WHERE departman_id = ? AND DATE(created_at) = ?");
$stmt->execute([$departman_id, $tarih]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$son_sayi = $row['max_number'];

if ($son_sayi === null || $son_sayi < $baslangic_sayi) {
    $son_sayi = $baslangic_sayi - 1;
}

$yeni_sayi = $son_sayi + 1;
$personel_id = null; // henüz atanmamışsa NULL

$insert = $conn->prepare("INSERT INTO counter (departman_id, number, created_at, personel_id, status) VALUES (?, ?, NOW(), ?, 'waiting')");
$insert->execute([$departman_id, $yeni_sayi, $personel_id]);

if ($DEBUG_MODE) {
    file_put_contents("log.txt", "Insert sonrası satır sayısı: " . $insert->rowCount() . "\n", FILE_APPEND);
}

if ($insert->rowCount() > 0) {
    echo json_encode(['number' => $yeni_sayi]);
} else {
    $errorInfo = $insert->errorInfo();
    if ($DEBUG_MODE) {
        file_put_contents("log.txt", "Hata: " . $errorInfo[2] . "\n", FILE_APPEND);
    }
    echo json_encode(['error' => 'Kayıt başarısız: ' . $errorInfo[2]]);
}
exit;
