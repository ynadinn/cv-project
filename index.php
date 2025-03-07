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
            header("Location: index.php?page=cv");
            exit();
        }

        echo "<h2>Form Data Diri</h2>
              <form method='POST'>
                <input type='text' name='nama' placeholder='Nama Lengkap' required>
                <input type='text' name='ttl' placeholder='Tempat, Tanggal Lahir' required>
                <input type='text' name='pendidikan' placeholder='Riwayat Pendidikan' required>
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
              <p><strong>Email:</strong> " . htmlspecialchars($_SESSION['userEmail']) . "</p>
              <br>
              <a href='index.php?page=logout'>Logout</a>";
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
