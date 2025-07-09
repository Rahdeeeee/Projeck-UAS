<?php
include "koneksi/database.php";
session_start();

$register_message = "";

// Ambil pesan register kalau ada
if (isset($_SESSION['register_message'])) {
    $register_message = $_SESSION['register_message'];
    unset($_SESSION['register_message']);
}

// Proses register
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username == "" || $password == "") {
        $_SESSION['register_message'] = "Username dan password harus diisi!";
        header("Location: register.php");
        exit();
    } else {
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $_SESSION['register_message'] = "Username sudah terdaftar.";
            header("Location: register.php");
            exit();
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users(username, password) VALUES(?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['register_message'] = "Gagal daftar: " . $db->error;
                header("Location: register.php");
                exit();
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="../css/register.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-4">
            <div class="card">
                <div class="card-header text-center">
                    <h3>REGISTER AKUN</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($register_message)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $register_message; ?>
                        </div>
                    <?php endif; ?>

                    <form action="register.php" method="POST">
                        <div class="mb-3">
                            <input type="text" placeholder="Username" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" placeholder="Password" name="password" class="form-control" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-success" type="submit" name="register">Daftar</button>
                        </div>
                        <a href="login.php" class="link">Sudah punya akun? Login</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
