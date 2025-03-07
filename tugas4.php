<?php
session_start();

// Jika sudah login, langsung redirect ke form.php
if (isset($_SESSION['userEmail'])) {
    header("Location: form.php");
    exit();
}

// Proses login ketika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validasi apakah email benar
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailParts = explode("@", $email);
        if (isset($emailParts[1])) { // Pastikan domain ada
            $emailDomain = $emailParts[1];

            // Cek apakah password sesuai dengan domain email
            if ($password === $emailDomain) {
                $_SESSION['userEmail'] = $email;
                header("Location: form.php");
                exit();
            } else {
                $error = "Password salah! Gunakan domain email sebagai password.";
            }
        } else {
            $error = "Format email tidak valid.";
        }
    } else {
        $error = "Masukkan email yang valid!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div>
        <h2>Login</h2>
        <?php 
        if (isset($error)) {
            echo "<p style='color:red;'>" . htmlspecialchars($error) . "</p>";
        }
        ?>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Masukkan Email" required>
            <input type="password" name="password" placeholder="Masukkan Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
