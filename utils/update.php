<?php
    session_start();
    if ((!isset($_COOKIE["email"]) && !isset($_COOKIE["username"])) || !isset($_COOKIE["session_id"])) {
        header("Location: auth.php");
    }
    include("../utils/session.php");
    // loadSession();

    if ($_SERVER['REQUEST_METHOD']!="POST") die("Invalid action.");

    $conn = new mysqli("localhost","root","","eenvoudige_database");
    if ($conn->connect_error) die("". $conn->connect_error);

    if ($_POST["value"]=="email" && isset($_POST["email"])) {
        $_SESSION["modified"] = "email";
        $hashed_pass = hash("sha256", $_POST["password"]);
        $double_check = "SELECT * FROM `users` WHERE `email` = '" . $_POST["email"] . "'";
        if ($conn->query($double_check)->num_rows==0) {
            $sql_check = "SELECT * FROM `users` WHERE `email` = '" . $_COOKIE["email"] . "' AND `session_id` = '" . $_COOKIE["session_id"] . "' AND `password` = '" . $hashed_pass . "'";
            $resp = $conn->query($sql_check);
            if ($resp->num_rows==1) {
                $sql_modify = "UPDATE `users` SET `email`='" . $_POST["email"] . "' WHERE `email`='" . $_COOKIE["email"] . "' AND `session_id`='" . $_COOKIE["session_id"] . "' AND `password` = '" . $hashed_pass . "'";
                $resp_modify = $conn->query($sql_modify);
                $_SESSION["modify_message"] = "Successfully changed your email.";
                setcookie("email", $_POST["email"], time() + (3600 * 24), "/");
            } else {
                $_SESSION["modify_message"] = "Could not change your email.";
            }
        } else {
            $_SESSION["modify_message"] = "Email is already in use.";
        }
    } else if ($_POST["value"]=="username" && isset($_POST["email"])) {
        $_SESSION["modified"] = "username";
        $hashed_pass = hash("sha256", $_POST["password"]);
        $double_check = "SELECT * FROM `users` WHERE `username` = " . $_POST["username"] . "'";
        if ($conn->query($double_check)->num_rows==0) {
            $sql_check = "SELECT * FROM `users` WHERE `email` = '" . $_COOKIE["email"] . "' AND `session_id` = '" . $_COOKIE["session_id"] . "' AND `password` = '" . $hashed_pass . "'";
            $resp = $conn->query($sql_check);
            if ($resp->num_rows==1) {
                $sql_modify = "UPDATE `users` SET `username`='" . $_POST["username"] . "' WHERE `email`='" . $_COOKIE["email"] . "' AND `session_id`='" . $_COOKIE["session_id"] . "' AND `password` = '" . $hashed_pass . "'";
                $resp_modify = $conn->query($sql_modify);
                $_SESSION["modify_message"] = "Successfully changed your username.";
                setcookie("username", $_POST["username"], time() + (3600 * 24), "/");
            } else {
                $_SESSION["modify_message"] = "Could not change your username.";
            }
        } else {
            $_SESSION["modify_message"] = "Username is already in use.";
        }
    } else if ($_POST["value"]=="password") {
        $_SESSION["modified"] = "password";
        $hashed_new_pass = hash("sha256", $_POST["new_password"]);
        $hashed_old_pass = hash("sha256", $_POST["old_password"]);
        $sql_check = "SELECT * FROM `users` WHERE `email` = '" . $_COOKIE["email"] . "' AND `session_id` = '" . $_COOKIE["session_id"] . "' AND `password` = '" . $hashed_old_pass . "'";
        $resp = $conn->query($sql_check);
        if ($resp->num_rows==1) {
            $sql_modify = "UPDATE `users` SET `password`='" . $hashed_new_pass . "' WHERE `email`='" . $_COOKIE["email"] . "' AND `session_id`='" . $_COOKIE["session_id"] . "' AND `password` = '" . $hashed_old_pass . "'";
            $resp_modify = $conn->query($sql_modify);
            $_SESSION["modify_message"] = "Successfully changed your password.";
        } else {
            $_SESSION["modify_message"] = "Could not change your password.";
        }
    }

    header("Location: ../pages/account.php");
?>