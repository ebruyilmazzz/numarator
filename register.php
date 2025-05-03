<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $personel_adi = $_POST['personel_adi'];
    $departman_adi = $_POST['departman_adi'];

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=kiosk;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Departman var mı kontrol et
        $stmt = $pdo->prepare("SELECT id FROM department WHERE LOWER(departman_adi) = LOWER(?)");
        $stmt->execute([$departman_adi]);
        $department = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$department) {
            // Departman yoksa oluştur
            $stmt = $pdo->prepare("INSERT INTO department (departman_adi) VALUES (?)");
            $stmt->execute([$departman_adi]);
            $department_id = $pdo->lastInsertId();
        } else {
            $department_id = $department['id'];
        }

        // Aynı personel bu departmanda zaten var mı?
        $stmt = $pdo->prepare("SELECT * FROM personnel WHERE personel_adi = ? AND section_id = ?");
        $stmt->execute([$personel_adi, $department_id]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $error = "❗ Bu personel zaten bu departmanda kayıtlı!";
        } else {
            // Kayıt işlemi
            $stmt = $pdo->prepare("INSERT INTO personnel (personel_adi, section_id) VALUES (?, ?)");
            $stmt->execute([$personel_adi, $department_id]);

            $_SESSION['success'] = "✅ Kayıt başarılı! Lütfen giriş yapın.";
            header("Location: login.php");
            exit();
        }

    } catch (PDOException $e) {
        $error = "Hata: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Personel Kayıt</title>
    <style>
        body {
            background-color: #f0fdf4;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0, 128, 0, 0.15);
            width: 350px;
            text-align: center;
        }

        .register-container h2 {
            color: #0f5132;
            margin-bottom: 24px;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: 2px solid #198754;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #198754;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #146c43;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            color: #198754;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Personel Kayıt</h2>
        <?php if (!empty($error)) echo "<div class='error'>{$error}</div>"; ?>
        <form method="post">
            <input type="text" name="personel_adi" placeholder="İsim" required>
            <select name="departman_adi" required>
                <option value="hasar">Hasar</option>
                <option value="mekanik">Mekanik</option>
                <option value="onarim">Onarım</option>
            </select>
            <button type="submit">Kaydol</button>
        </form>
        <a href="login.php">Zaten hesabın var mı? Giriş Yap</a>
    </div>
</body>
</html>
