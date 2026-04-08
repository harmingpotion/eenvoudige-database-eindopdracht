<?php
    session_start();

    $_SESSION = [];
    session_destroy();

    setcookie("email", "", time() - 3600, "/");
    setcookie("username", "", time() - 3600, "/");
    setcookie("session_id", "", time() - 3600, "/");

    header("Location: ../pages/auth.php");
    exit;
?>