<?php
// Veritabanı bağlantısı
$baglanti = new mysqli("localhost", "root", "", "numarator");

if ($baglanti->connect_error) {
    die(json_encode(['error' => 'Veritabanı bağlantısı başarısız']));
}

$tarih = date("Y-m-d");
$masa_tipi = $_POST['masa_tipi'] ?? '';

if (!$masa_tipi) {
    echo json_encode(['error' => 'Masa tipi belirtilmedi']);
    exit;
}

// Hangi tablo, hangi başlangıç numarası?
$ayarlar = [
    'hasar' => ['tablo' => 'hasar', 'baslangic' => 100],
    'mekanik' => ['tablo' => 'mekanik', 'baslangic' => 200],
    'onarım' => ['tablo' => 'onarim', 'baslangic' => 300]
];

// Geçerli masa değilse
if (!array_key_exists($masa_tipi, $ayarlar)) {
    echo json_encode(['error' => 'Geçersiz masa tipi']);
    exit;
}

$tablo = $ayarlar[$masa_tipi]['tablo'];
$baslangic = $ayarlar[$masa_tipi]['baslangic'];

// Maksimum sıra numarasını al
$sorgu = $baglanti->prepare("SELECT MAX(sira_no) AS max_no FROM $tablo WHERE tarih = ?");
$sorgu->bind_param("s", $tarih);
$sorgu->execute();
$sonuc = $sorgu->get_result();
$row = $sonuc->fetch_assoc();

// Yeni sıra numarası hesapla
$yeni_no = $row['max_no'] ? $row['max_no'] + 1 : $baslangic;

// Veriyi ekle
$ekle = $baglanti->prepare("INSERT INTO $tablo (tarih, sira_no) VALUES (?, ?)");
$ekle->bind_param("si", $tarih, $yeni_no);
$ekle->execute();

// JSON cevap döndür
echo json_encode(['number' => $yeni_no]);
?>
