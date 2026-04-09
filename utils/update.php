<?php
session_start();

if ((!isset($_COOKIE["email"]) && !isset($_COOKIE["username"])) || !isset($_COOKIE["session_id"])) {
    header("Location: auth.php");
    exit();
}

include("../utils/session.php");

if ($_SERVER['REQUEST_METHOD'] != "POST") die("Invalid action.");

$conn = new mysqli("localhost","root","","eenvoudige_database");
if ($conn->connect_error) die($conn->connect_error);

// EMAIL CHANGE
if ($_POST["value"] == "email" && isset($_POST["email"])) {
    $_SESSION["modified"] = "email";
    $hashed_pass = hash("sha256", $_POST["password"]);

    // check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $_POST["email"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {

        // verify user
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND session_id = ? AND password = ?");
        $stmt->bind_param("sss", $_COOKIE["email"], $_COOKIE["session_id"], $hashed_pass);
        $stmt->execute();
        $resp = $stmt->get_result();

        if ($resp->num_rows == 1) {
            // update email
            $stmt = $conn->prepare("UPDATE users SET email = ? WHERE email = ? AND session_id = ? AND password = ?");
            $stmt->bind_param("ssss", $_POST["email"], $_COOKIE["email"], $_COOKIE["session_id"], $hashed_pass);
            $stmt->execute();

            $_SESSION["modify_message"] = "Successfully changed your email.";
            setcookie("email", $_POST["email"], time() + (3600 * 24), "/");
        } else {
            $_SESSION["modify_message"] = "Could not change your email.";
        }

    } else {
        $_SESSION["modify_message"] = "Email is already in use.";
    }

// USERNAME CHANGE
} else if ($_POST["value"] == "username" && isset($_POST["username"])) {
    $_SESSION["modified"] = "username";
    $hashed_pass = hash("sha256", $_POST["password"]);

    // check if username exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $_POST["username"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {

        // verify user
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND session_id = ? AND password = ?");
        $stmt->bind_param("sss", $_COOKIE["email"], $_COOKIE["session_id"], $hashed_pass);
        $stmt->execute();
        $resp = $stmt->get_result();

        if ($resp->num_rows == 1) {
            // update username
            $stmt = $conn->prepare("UPDATE users SET username = ? WHERE email = ? AND session_id = ? AND password = ?");
            $stmt->bind_param("ssss", $_POST["username"], $_COOKIE["email"], $_COOKIE["session_id"], $hashed_pass);
            $stmt->execute();

            $_SESSION["modify_message"] = "Successfully changed your username.";
            setcookie("username", $_POST["username"], time() + (3600 * 24), "/");
        } else {
            $_SESSION["modify_message"] = "Could not change your username.";
        }

    } else {
        $_SESSION["modify_message"] = "Username is already in use.";
    }

// PASSWORD CHANGE
} else if ($_POST["value"] == "password") {
    $_SESSION["modified"] = "password";

    $hashed_new_pass = hash("sha256", $_POST["new_password"]);
    $hashed_old_pass = hash("sha256", $_POST["old_password"]);

    // verify user
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND session_id = ? AND password = ?");
    $stmt->bind_param("sss", $_COOKIE["email"], $_COOKIE["session_id"], $hashed_old_pass);
    $stmt->execute();
    $resp = $stmt->get_result();

    if ($resp->num_rows == 1) {
        // update password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ? AND session_id = ? AND password = ?");
        $stmt->bind_param("ssss", $hashed_new_pass, $_COOKIE["email"], $_COOKIE["session_id"], $hashed_old_pass);
        $stmt->execute();

        $_SESSION["modify_message"] = "Successfully changed your password.";
    } else {
        $_SESSION["modify_message"] = "Could not change your password.";
    }
}

header("Location: ../pages/account.php");
exit();
?>