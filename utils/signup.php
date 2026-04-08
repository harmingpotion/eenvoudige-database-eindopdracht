<?php
    session_start();
    $conn = new mysqli("localhost","root","","eenvoudige_database");
    if ($conn->connect_error) die("". $conn->connect_error);

    $sql_email = "SELECT * FROM `users` WHERE email = '" . $_POST["email"] . "'";
    $email_error = null;
    if (!isset($_POST["email"])) $email_error = "Please input an email.";
    else if ($conn->query($sql_email)->num_rows!=0) $email_error = "This email is already been taken.";

    $sql_user = "SELECT * FROM `users` WHERE username = '" . $_POST["username"] . "'";
    $user_error = null;
    if (!isset($_POST["username"])) $user_error = "Please input a username.";
    else if ($conn->query($sql_user)->num_rows!=0) $user_error = "This username has already been taken.";

    if (!isset($email_error) && !isset($user_error)) {
        include("session.php");
        $session_id = createNewSessionId($_POST["email"]);
        $hashed_pass = hash("sha256", $_POST["password"]);

        $insert_sql = "INSERT INTO `users`(`email`, `username`, `password`, `session_id`) VALUES ('" . $_POST["email"] . "','" . $_POST["username"] . "','" . $hashed_pass . "','" . $session_id . "')";
        $inserting = $conn->query($insert_sql);
        setcookie("username", $_POST["username"], time() + (3600 * 24), "/");
        header("Location: ../pages/dashboard.php");
    } else {
        $_SESSION["email_error"] = $email_error;
        $_SESSION["user_error"] = $user_error;
        header("Location: ../pages/auth.php?type=signup");
    }
?>