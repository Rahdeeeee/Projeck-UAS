<?php
include "koneksi/database.php";
session_start();

$login_message = "";
$login_message_type = "danger"; 

if (isset($_SESSION['login_message'])) {
    $login_message = $_SESSION['login_message'];
    unset($_SESSION['login_message']);

    if (isset($_SESSION['login_message_type'])) {
        $login_message_type = $_SESSION['login_message_type'];
        unset($_SESSION['login_message_type']);
    }
}

// Proses login kalau form disubmit
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            session_regenerate_id();
            $_SESSION['username'] = $username;
            header("Location: menu/dashboard.php");
            exit();
        } else {
            $_SESSION['login_message'] = "Password salah";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['login_message'] = "Username tidak ditemukan";
        header("Location: login.php");
        exit();
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-4">
            <div class="card">
                <div class="card-header text-center">
                    <h3>LOGIN AKUN</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($login_message)): ?>
                    <div class="alert alert-<?php echo $login_message_type; ?>" role="alert">
                    <?php echo $login_message; ?>
                    </div>
                    <?php endif; ?>
                    <form action="login.php" method="POST">
                        <div class="mb-3">
                            <input type="text" placeholder="Username" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" placeholder="Password" name="password" class="form-control" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" type="submit" name="login">Login</button>
                        </div>
                        <a href="register.php" class="link">Register</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
