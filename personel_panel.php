<?php
session_start();

if (!isset($_SESSION['personel']) || !isset($_SESSION['personel']['id'])) {
    echo "Personel oturum bilgisi eksik veya bozuk.";
    exit;
}

$personel = $_SESSION['personel'];
$personel_id = $personel['id'];
$departman = isset($personel['departman_adi']) ? $personel['departman_adi'] : "Bilinmiyor";

try {
    $conn = new PDO("mysql:host=localhost;dbname=kiosk;charset=utf8", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Kendi departman id'sini çekiyoruz
    $stmt = $conn->prepare("SELECT section_id FROM personnel WHERE id = ?");
    $stmt->execute([$personel_id]);
    $own_dept_id = $stmt->fetchColumn();

    // Diğer departmanları çekiyoruz
    $stmt = $conn->prepare("SELECT * FROM department WHERE id != ?");
    $stmt->execute([$own_dept_id]);
    $diger_departmanlar = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Aktif masaları çekiyoruz
    $stmt = $conn->prepare("
        SELECT d.departman_adi, desk.desk_name 
        FROM counter k 
        JOIN department d ON d.id = k.departman_id 
        JOIN desk desk ON desk.id = k.desk_id
        WHERE k.is_active = 1
    ");
    $stmt->execute();
    $aktif_masalar = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Tüm masaları masa seçimi için çekiyoruz
    $stmt = $conn->query("SELECT id, desk_name FROM desk");
    $masalar = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Personel Paneli</title>
    <style>
        body {
            background-color: white;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2ecc71;
            padding: 20px;
            text-align: center;
            color: white;
        }

        h2 {
            margin: 0;
        }

        .main-content {
            display: flex;
            max-width: 1200px;
            margin: auto;
            padding: 30px 20px;
            gap: 30px;
        }

        .container {
            flex: 1;
        }

        button {
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
            display: block;
            width: 100%;
        }

        button:hover {
            background-color: #27ae60;
        }

        .info-box, li {
            background-color: #2c2c2c;
            border-left: 5px solid #2ecc71;
            padding: 15px 20px;
            margin-bottom: 15px;
            border-radius: 5px;
            color: white;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        hr {
            border: 1px solid #ddd;
            margin: 30px 0;
        }

        .sidebar {
            width: 220px;
            background-color: #2c2c2c;
            padding: 20px;
            border-radius: 10px;
            height: fit-content;
            position: sticky;
            top: 100px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }

        .sidebar h3 {
            color: #2ecc71;
            margin-top: 0;
        }

        #masaPopup {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        #masaPopup > div {
            background: white;
            padding: 20px;
            border-radius: 10px;
            min-width: 300px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }

        #masaPopup select, #masaPopup button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            font-size: 16px;
        }

        #masaPopup h3 {
            margin-top: 0;
        }
    </style>
</head>
<body>

<header>
    <h2><?= htmlspecialchars($personel['personel_adi']) ?> | Departman: <?= htmlspecialchars($departman) ?></h2>
</header>

<div class="main-content">
    <div class="container">
        <button onclick="musteriCagir()">Müşteri Çağır (Kendi)</button>

        <div class="info-box">
            Çağrılan Numara: <span id="cagrilan-numara">Henüz yok</span>
        </div>

        <hr>

        <h3>Bekleyen Müşteriler (<span id="count">0</span>)</h3>
        <ul id="musteriListesi">
            <li>Yükleniyor...</li>
        </ul>

        <hr>

        <h3>Aktif Masalar</h3>
        <ul>
            <?php foreach ($aktif_masalar as $masa): ?>
                <li><?= htmlspecialchars($masa['departman_adi']) ?> - <?= htmlspecialchars($masa['desk_name']) ?></li>
            <?php endforeach; ?>
        </ul>

        <button onclick="masaDegistir()">Masa Değiştir</button>
    </div>

    <div class="sidebar">
        <h3>Diğer Departmanlardan Çağır</h3>
        <?php foreach ($diger_departmanlar as $dept): ?>
            <button onclick="departmandanCagir('<?= htmlspecialchars($dept['departman_adi']) ?>')">
                <?= ucfirst(htmlspecialchars($dept['departman_adi'])) ?>
            </button>
        <?php endforeach; ?>
    </div>
</div>

<!-- Masa Değiştir Popup -->
<div id="masaPopup">
    <div>
        <h3>Masa Seç</h3>
        <form id="masaForm">
            <select name="desk_id" id="desk_id" required>
                <option value="">-- Masa Seçin --</option>
                <?php foreach ($masalar as $masa): ?>
                    <option value="<?= $masa['id'] ?>"><?= htmlspecialchars($masa['desk_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Değiştir</button>
        </form>
        <button onclick="kapatPopup()" style="background:#ccc; color:black;">İptal</button>
    </div>
</div>

<script>
function fetchMusteriler() {
    fetch('fetch_queue.php')
        .then(response => response.json())
        .then(data => {
            const list = document.getElementById("musteriListesi");
            const count = document.getElementById("count");
            list.innerHTML = '';
            count.innerText = data.length;

            if (data.length === 0) {
                list.innerHTML = "<li>Bekleyen müşteri yok.</li>";
            } else {
                data.forEach(item => {
                    const li = document.createElement("li");
                    li.textContent = "Sıra No: " + item.number;
                    list.appendChild(li);
                });
            }
        })
        .catch(error => {
            console.error("Veri alınamadı:", error);
        });
}

function musteriCagir() {
    fetch('cagir.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('cagrilan-numara').innerText = data.number;
                fetchMusteriler();
            } else {
                alert(data.message || "Müşteri çağırma başarısız.");
            }
        })
        .catch(error => {
            console.error("Çağırma hatası:", error);
            alert("Sunucuya ulaşılamadı.");
        });
}

function departmandanCagir(departman) {
    fetch('cagir.php?departman=' + encodeURIComponent(departman))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('cagrilan-numara').innerText = data.number + " (" + departman + ")";
                fetchMusteriler();
            } else {
                alert(data.message || "Departmandan müşteri çağırma başarısız.");
            }
        })
        .catch(error => {
            console.error("Departman çağırma hatası:", error);
            alert("Sunucuya ulaşılamadı.");
        });
}

function masaDegistir() {
    document.getElementById("masaPopup").style.display = "flex";
}

function kapatPopup() {
    document.getElementById("masaPopup").style.display = "none";
}

document.getElementById("masaForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const deskId = document.getElementById("desk_id").value;

    if (!deskId) return;

    fetch("masa_degistir.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "desk_id=" + encodeURIComponent(deskId)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Masa değiştirildi.");
            kapatPopup();
            location.reload();
        } else {
            alert(data.message || "Masa değiştirilemedi.");
        }
    })
    .catch(err => {
        console.error("Hata:", err);
        alert("Bir hata oluştu.");
    });
});

setInterval(fetchMusteriler, 3000);
fetchMusteriler();
</script>

</body>
</html>
