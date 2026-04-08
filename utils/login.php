<?php
    session_start();
    $conn = new mysqli("localhost","root","","eenvoudige_database");
    if ($conn->connect_error) die("". $conn->connect_error);

    $hashed_pass = hash("sha256", $_POST["password"]);
    $sql = "SELECT * FROM `users` WHERE (email = '" . $_POST["identifier"] . "' OR username = '" . $_POST["identifier"] . "') AND password = '" . $hashed_pass . "'";
    $user = $conn->query($sql);
    if ($user->num_rows==1) {
        include("session.php");

        $username = $user->fetch_assoc()["username"];
        $email = $user->fetch_assoc()["email"];

        createNewSessionId($email);
        setcookie("username", $username, time() + (3600 * 24), "/");

        header("Location: ../pages/dashboard.php");
    } else {
        $_SESSION["login_error"] = "Incorrect details or account does not exist!";
        header("Location: ../pages/auth.php?type=login");
    }
?>