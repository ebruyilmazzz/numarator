<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=kiosk;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Personeli veritabanından sorgula
        $stmt = $pdo->prepare("
            SELECT 
                p.id, 
                p.personel_adi, 
                p.section_id, 
                d.departman_adi 
            FROM personnel p
            JOIN department d ON p.section_id = d.id
            WHERE p.personel_adi = ?
        ");
        $stmt->execute([$name]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Oturum oluştur ve kullanıcıyı yönlendir
            $_SESSION['personel'] = [
                'id' => $user['id'],
                'personel_adi' => $user['personel_adi'],
                'departman_adi' => $user['departman_adi']
            ];
            header("Location: personel_panel.php");
            exit();
        } else {
            $error = "Personel bulunamadı.";
        }
    } catch (PDOException $e) {
        $error = "Veritabanı hatası: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Personel Giriş</title>
    <style>
        body {
            background-color: #f0fdf4;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0, 128, 0, 0.15);
            width: 350px;
            text-align: center;
        }

        .login-container h2 {
            color: #0f5132;
            margin-bottom: 24px;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: 2px solid #198754;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
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
    <div class="login-container">
        <h2>Personel Giriş</h2>
        
        <!-- Hata mesajı gösterimi -->
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <label for="name">
                <input type="text" id="name" name="name" placeholder="Ad Soyad" required>
            </label>
            <button type="submit">Giriş Yap</button>
        </form>
        <a href="register.php">Kayıt Ol</a>
    </div>
</body>
</html>
