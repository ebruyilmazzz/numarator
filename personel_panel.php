<?php
session_start();

if (!isset($_SESSION['personel'])) {
    header("Location: login.php");
    exit();
}

$personel = $_SESSION['personnel'];
$departman = $personel['departman_adi']; // Örn: 'hasar'
?>

<!DOCTYPE html>
<html>
<head>
    <title>Personel Paneli</title>
    <meta charset="UTF-8">
    <style>body { background: #121212; color: white; font-family: sans-serif; }</style>
</head>
<body>
    <h2>👨‍💼 Hoşgeldiniz, <?= htmlspecialchars($personel['name']) ?> | Departman: <?= htmlspecialchars($departman) ?></h2>

    <button onclick="musteriCagir()">🔔 Müşteri Çağır</button>

    <hr>
    <h3>📋 Bekleyen Müşteriler (<span id="count">0</span>)</h3>
    <ul id="musteriListesi"><li>Yükleniyor...</li></ul>

    <script>
        function fetchMusteriler() {
            fetch('fetch_queue.php')
                .then(response => {
                    if (!response.ok) throw new Error("Sunucu hatası");
                    return response.json();
                })
                .then(data => {
                    const list = document.getElementById("musteriListesi");
                    const count = document.getElementById("count");
                    list.innerHTML = '';
                    count.innerText = data.length;
                    data.forEach(item => {
                        const li = document.createElement("li");
                        li.textContent = "Sıra No: " + item.number;
                        list.appendChild(li);
                    });
                })
                .catch(error => {
                    console.error("Veri alınamadı:", error);
                });
        }

        function musteriCagir() {
            alert("Çağırma fonksiyonu yazılacak!");
        }

        setInterval(fetchMusteriler, 3000);
        fetchMusteriler();
    </script>
</body>
</html>
