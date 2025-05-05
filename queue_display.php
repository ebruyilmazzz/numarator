<?php
// AJAX isteği gelirse JSON dön
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    header('Content-Type: application/json');
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=kiosk;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("
            SELECT c.number, d.departman_adi, c.created_at, k.desk_name, c.status
            FROM counter c
            JOIN department d ON c.departman_id = d.id
            LEFT JOIN desk k ON c.desk_id = k.id
            WHERE c.status = 'waiting'
            ORDER BY c.created_at ASC
        ");
        $stmt->execute();
        $waiting = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($waiting);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kuyruk Ekranı</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: white;
            color: #2ecc71;
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }

        h2, h3 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        table {
            width: 70%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #1e1e1e;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }

        th, td {
            padding: 12px;
            border: 1px solid #444;
        }

        th {
            background-color: #2ecc71;
            color: black;
        }

        td {
            color: white;
        }

        tr:nth-child(even) {
            background-color: #2a2a2a;
        }

        .section-header {
            margin-top: 40px;
            color: #2ecc71;
        }

        .assigned-header {
            color: #3498db;
        }

        .unassigned-header {
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <h2 class="section-header">Bekleyen Müşteriler (Tüm Durumlar)</h2>

    <h3 class="unassigned-header">Atanmamış Müşteriler</h3>
    <table>
        <thead>
            <tr>
                <th>Sıra No</th>
                <th>Departman</th>
                <th>Masa</th>
                <th>Alınma Zamanı</th>
            </tr>
        </thead>
        <tbody id="unassigned-body">
            <tr><td colspan="4">Yükleniyor...</td></tr>
        </tbody>
    </table>

    <h3 class="assigned-header">Atanmış Müşteriler</h3>
    <table>
        <thead>
            <tr>
                <th>Sıra No</th>
                <th>Departman</th>
                <th>Masa</th>
                <th>Alınma Zamanı</th>
            </tr>
        </thead>
        <tbody id="assigned-body">
            <tr><td colspan="4">Yükleniyor...</td></tr>
        </tbody>
    </table>

    <script>
        function loadQueue() {
            $.getJSON('?ajax=1', function(data) {
                const assignedBody = $('#assigned-body');
                const unassignedBody = $('#unassigned-body');

                assignedBody.empty();
                unassignedBody.empty();

                if (data.length > 0) {
                    let assignedFound = false;
                    let unassignedFound = false;

                    data.forEach(function(row) {
                        const html = `
                            <tr>
                                <td>${row.number}</td>
                                <td>${row.departman_adi}</td>
                                <td>${row.desk_name || 'Atanmadı'}</td>
                                <td>${row.created_at}</td>
                            </tr>
                        `;
                        if (row.desk_name) {
                            assignedBody.append(html);
                            assignedFound = true;
                        } else {
                            unassignedBody.append(html);
                            unassignedFound = true;
                        }
                    });

                    if (!assignedFound) {
                        assignedBody.append('<tr><td colspan="4">Atanmış müşteri yok.</td></tr>');
                    }
                    if (!unassignedFound) {
                        unassignedBody.append('<tr><td colspan="4">Atanmamış müşteri yok.</td></tr>');
                    }

                } else {
                    assignedBody.append('<tr><td colspan="4">Veri bulunamadı.</td></tr>');
                    unassignedBody.append('<tr><td colspan="4">Veri bulunamadı.</td></tr>');
                }
            });
        }

        loadQueue(); // İlk yükleme
        setInterval(loadQueue, 1000); // 1 saniyede bir güncelle
    </script>
</body>
</html>
