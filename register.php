<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $section_name = $_POST['section']; // departman adı

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=kiosk;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // İlk olarak, departmanın veritabanında olup olmadığını kontrol edelim
        $stmt = $pdo->prepare("SELECT id FROM department WHERE LOWER(name) = LOWER(?)");
        $stmt->execute([$section_name]);
        $department = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$department) {
            // Eğer departman yoksa, yeni bir departman ekleyelim
            $stmt = $pdo->prepare("INSERT INTO department (name) VALUES (?)");
            $stmt->execute([$section_name]);

            // Yeni eklenen departmanın id'sini alalım
            $department_id = $pdo->lastInsertId();
        } else {
            // Eğer departman varsa, mevcut id'yi kullanıyoruz
            $department_id = $department['id'];
        }

        // Departmanla ilgili personelin daha önce kayıt olup olmadığını kontrol edelim
        $stmt = $pdo->prepare("SELECT * FROM personnel WHERE name = ? AND section_id = ?");
        $stmt->execute([$name, $department_id]);
        $existing_personnel = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_personnel) {
            echo "Bu personel zaten bu departmanda kayıtlı!";
        } else {
            // Personel kaydını yapalım
            $stmt = $pdo->prepare("INSERT INTO personnel (name, section_id) VALUES (?, ?)");
            $stmt->execute([$name, $department_id]);

            // Kayıt başarılı ise kullanıcıyı login sayfasına yönlendirelim
            $_SESSION['success'] = "Kayıt başarılı! Lütfen giriş yapın.";
            header("Location: login.php"); // Giriş sayfasına yönlendirme
            exit();
        }
    } catch (PDOException $e) {
        echo "Hata: " . $e->getMessage();
    }
}
?>

<form method="post">
    <label>İsim: <input type="text" name="name" required></label><br>
    <label>Bölüm:
        <select name="section">
            <option value="hasar">Hasar</option>
            <option value="mekanik">Mekanik</option>
            <option value="onarim">Onarım</option>
        </select>
    </label><br>
    <button type="submit">Kaydet</button>
</form>
