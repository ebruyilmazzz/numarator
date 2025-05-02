<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=kiosk;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare(" SELECT p.id, p.name, p.section_id, d.name AS name
    FROM personnel p
    JOIN department d ON p.section_id = d.id
    WHERE p.name = ?");
        $stmt->execute([$name]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['personel'] = $user; // id, name, section_id, departman_adi
            header("Location: personel_panel.php");
            exit();
        } else {
            echo "❌ Personel bulunamadı.";
        }
    } catch (PDOException $e) {
        echo " Hata: " . $e->getMessage();
    }
}
?>

<form method="post">
    <label>Ad Soyad: <input type="text" name="name" required></label><br>
    <button type="submit">Giriş Yap</button>
</form>
