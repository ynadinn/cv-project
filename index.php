<?php
session_start();

class UserSystem {
    public function handleRequest() {
        $page = $_GET['page'] ?? 'login';

        if ($page === 'logout') {
            $this->logout();
        } elseif ($page === 'form' && !$this->isAuthenticated()) {
            header("Location: index.php?page=login");
            exit();
        } elseif ($page === 'cv' && (!$this->isAuthenticated() || !$this->hasUserData())) {
            header("Location: index.php?page=form");
            exit();
        }

        $this->renderPage($page);
    }

    private function renderPage($page) {
        switch ($page) {
            case 'login':
                $this->showLoginPage();
                break;
            case 'form':
                $this->showFormPage();
                break;
            case 'cv':
                $this->showCVPage();
                break;
            default:
                echo "Halaman tidak ditemukan.";
        }
    }

    private function showLoginPage() {
        if ($this->isAuthenticated()) {
            header("Location: index.php?page=form");
            exit();
        }

        $error = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $loginResult = $this->login($email, $password);

            if ($loginResult === true) {
                header("Location: index.php?page=form");
                exit();
            } else {
                $error = $loginResult;
            }
        }

        echo "<h2>Login</h2>";
        if ($error) echo "<p style='color:red;'>$error</p>";
        echo "<form method='POST'>
                <input type='email' name='email' placeholder='Masukkan Email' required>
                <input type='password' name='password' placeholder='Masukkan Password' required>
                <button type='submit'>Login</button>
              </form>";
    }

    private function showFormPage() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $_SESSION['nama'] = $_POST['nama'];
            $_SESSION['ttl'] = $_POST['ttl'];
            $_SESSION['pendidikan'] = $_POST['pendidikan'];

            // Proses upload foto
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = "uploads/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileTmpPath = $_FILES['foto']['tmp_name'];
                $fileName = basename($_FILES['foto']['name']);
                $fileSize = $_FILES['foto']['size'];
                $fileType = mime_content_type($fileTmpPath);
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

                if (in_array($fileType, $allowedTypes) && $fileSize <= 3 * 1024 * 1024) { // Maks 3MB
                    $newFileName = uniqid() . "_" . $fileName;
                    $destPath = $uploadDir . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $destPath)) {
                        $_SESSION['foto'] = $destPath;
                    } else {
                        echo "<p style='color:red;'>Gagal mengunggah foto!</p>";
                    }
                } else {
                    echo "<p style='color:red;'>Format foto tidak didukung atau ukuran terlalu besar! (Maks 3MB)</p>";
                }
            }

            header("Location: index.php?page=cv");
            exit();
        }

        echo "<h2>Form Data Diri</h2>
              <form method='POST' enctype='multipart/form-data'>
                <input type='text' name='nama' placeholder='Nama Lengkap' required>
                <input type='text' name='ttl' placeholder='Tempat, Tanggal Lahir' required>
                <input type='text' name='pendidikan' placeholder='Riwayat Pendidikan' required>
                <input type='file' name='foto' accept='image/*' required>
                <button type='submit'>Simpan & Lihat CV</button>
              </form>
              <br>
              <a href='index.php?page=logout'>Logout</a>";
    }

    private function showCVPage() {
        echo "<h2>Curriculum Vitae</h2>
              <p><strong>Nama:</strong> " . htmlspecialchars($_SESSION['nama']) . "</p>
              <p><strong>Tempat, Tanggal Lahir:</strong> " . htmlspecialchars($_SESSION['ttl']) . "</p>
              <p><strong>Riwayat Pendidikan:</strong> " . htmlspecialchars($_SESSION['pendidikan']) . "</p>
              <p><strong>Email:</strong> " . htmlspecialchars($_SESSION['userEmail']) . "</p>";

        if (!empty($_SESSION['foto'])) {
            echo "<p><strong>Foto:</strong><br> <img src='" . htmlspecialchars($_SESSION['foto']) . "' width='150' height='150' style='border-radius: 50%;'></p>";
        }

        echo "<br><a href='index.php?page=logout'>Logout</a>";
    }

    private function login($email, $password) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Email tidak valid!";
        }

        $emailParts = explode("@", $email);
        if (!isset($emailParts[1])) {
            return "Format email salah!";
        }

        $emailDomain = $emailParts[1];

        if ($password === $emailDomain) {
            $_SESSION['userEmail'] = $email;
            return true;
        } else {
            return "Password salah! Gunakan domain email sebagai password.";
        }
    }

    private function isAuthenticated() {
        return isset($_SESSION['userEmail']);
    }

    private function hasUserData() {
        return isset($_SESSION['nama']);
    }

    private function logout() {
        session_destroy();
        header("Location: index.php?page=login");
        exit();
    }
}

$app = new UserSystem();
$app->handleRequest();
?>
