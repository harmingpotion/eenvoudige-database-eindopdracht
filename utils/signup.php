<?php
    session_start();

    $conn = new mysqli("localhost","root","","eenvoudige_database");
    if ($conn->connect_error) die($conn->connect_error);

    $email_error = null;
    $user_error = null;
    $password_error = null;

    if (!isset($_POST["email"])) {
        $email_error = "Please input an email.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $_POST["email"]);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows != 0) {
            $email_error = "This email is already been taken.";
        }
    }

    if (!isset($_POST["username"])) {
        $user_error = "Please input a username.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $_POST["username"]);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows != 0) {
            $user_error = "This username has already been taken.";
        }
    }

    if (!isset($_POST["password"])) {
        $password_error = "Please input a password of atleast 8 characters.";
    } else if (strlen($_POST["password"]) < 8) {
        $password_error = "Password must be atleast 8 characters.";
    }

    if (!isset($email_error) && !isset($user_error) && !isset($password_error)) {
        include("session.php");
        $session_id = createNewSessionId($_POST["email"]);
        $hashed_pass = hash("sha256", $_POST["password"]);

        $stmt = $conn->prepare("INSERT INTO users (email, username, password, session_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $_POST["email"], $_POST["username"], $hashed_pass, $session_id);
        $stmt->execute();

        setcookie("username", $_POST["username"], time() + (3600 * 24), "/");
        header("Location: ../pages/dashboard.php");
        exit();
    } else {
        $_SESSION["email_error"] = $email_error;
        $_SESSION["user_error"] = $user_error;
        $_SESSION["password_error"] = $password_error;
        $_SESSION["temp_email"] = $_POST["email"];
        $_SESSION["temp_username"] = $_POST["username"];
        header("Location: ../pages/auth.php?type=signup");
        exit();
    }
?>