<?php
session_start();

if (!isset($_SESSION['personel'])) {
    header("Location: login.php");
    exit();
}

$personel = $_SESSION['personel'];
$departman = $personel['departman_adi'];
$personel_id = $personel['id'];

try {
    $conn = new PDO("mysql:host=localhost;dbname=kiosk;charset=utf8", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Bu personelin departman ID'sini al
    $stmt = $conn->prepare("SELECT section_id FROM personnel WHERE id = ?");
    $stmt->execute([$personel_id]);
    $own_dept_id = $stmt->fetchColumn();

    // Kendi departmanını hariç tutarak diğerlerini çek
    $stmt = $conn->prepare("SELECT * FROM department WHERE id != ?");
    $stmt->execute([$own_dept_id]);
    $diger_departmanlar = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
            background-color:rgb(255, 255, 255);
            color: white;
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
            font-size: 24px;
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

        .info-box {
            background-color: #2c2c2c;
            border-left: 5px solid #2ecc71;
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .info-box span {
            font-weight: bold;
            color: #2ecc71;
        }

        ul {
            list-style: none;
            padding-left: 0;
        }

        li {
            background-color: #2c2c2c;
            margin-bottom: 10px;
            padding: 10px 15px;
            border-left: 5px solid #2ecc71;
            border-radius: 5px;
        }

        hr {
            border: 1px solid #333;
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
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 18px;
            color: #2ecc71;
        }

        .sidebar button {
            background-color: #2ecc71;
        }

        .sidebar button:hover {
            background-color: #2ecc71;
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
                <div>Çağrılan Numara: <span id="cagrilan-numara">Henüz yok</span></div>
            </div>

            <hr>

            <h3>Bekleyen Müşteriler (<span id="count">0</span>)</h3>
            <ul id="musteriListesi">
                <li>Yükleniyor...</li>
            </ul>
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
        fetch('cagir.php?departman=' + departman)
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

    setInterval(fetchMusteriler, 3000);
    fetchMusteriler();
    </script>
</body>
</html>