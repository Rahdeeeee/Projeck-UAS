<?php
session_start();
unset($_SESSION['username']);
$_SESSION['login_message'] = "Anda berhasil logout.";
$_SESSION['login_message_type'] = "success";
header("Location: login.php");
exit();
?>
