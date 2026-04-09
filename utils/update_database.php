<?php
    session_start();

    if ((!isset($_COOKIE["email"]) && !isset($_COOKIE["username"])) || !isset($_COOKIE["session_id"])) {
        header("Location: auth.php");
        exit();
    }

    include("../utils/session.php");
    checkSessionId($_COOKIE["email"], $_COOKIE["session_id"]);

    if ($_SERVER['REQUEST_METHOD'] != "POST") die("Invalid action.");

    $conn = new mysqli("localhost","root","","eenvoudige_database");
    if ($conn->connect_error) die($conn->connect_error);
    if ($_POST["type"]==="add_user") {
        if (isset($_POST["firstname"]) && $_POST["firstname"]!=='' && 
            isset($_POST["lastname"]) && $_POST["lastname"]!=='' &&
            isset($_POST["email"]) && $_POST["email"]!=='' &&
            isset($_POST["province"]) && $_POST["province"]!=='') {
            $stmt = $conn->prepare("INSERT INTO userdata (firstname, lastname, email, province, email_owner) VALUES (?, ?, ?, ?, ?)");
    
            $stmt->bind_param(
                "sssss",
                $_POST["firstname"],
                $_POST["lastname"],
                $_POST["email"],
                $_POST["province"],
                $_COOKIE["email"]
            );

            $stmt->execute();
        } else {
            $_SESSION["update_db_error"] = "Please input all fields";
        }
        header("Location: ../pages/database.php");
    } else if ($_POST["type"]==="edit_user") {
        if (isset($_POST["firstname"], $_POST["firstname_old"], 
                $_POST["lastname"], $_POST["lastname_old"],
                $_POST["email"], $_POST["email_old"],
                $_POST["province"], $_POST["province_old"]) 
            && $_POST["firstname"] !== '' && $_POST["lastname"] !== '' &&
            $_POST["email"] !== '' && $_POST["province"] !== '') {

            $stmt = $conn->prepare(
                "UPDATE userdata
                SET firstname = ?, lastname = ?, email = ?, province = ?
                WHERE email_owner = ? AND firstname = ? AND lastname = ? AND email = ? AND province = ?"
            );

            $stmt->bind_param(
                "sssssssss",
                $_POST["firstname"], // new firstname
                $_POST["lastname"], // new lastname
                $_POST["email"], // new email
                $_POST["province"], // new province
                $_COOKIE["email"], // email_owner
                $_POST["firstname_old"], // old firstname
                $_POST["lastname_old"], // old lastname
                $_POST["email_old"], // old email
                $_POST["province_old"] // old province
            );

            $stmt->execute();
            header("Location: ../pages/database.php");
        }
    } else if ($_POST["type"]==="delete_user") {
        if (isset($_POST["firstname_old"]) && $_POST["firstname_old"]!=='' && 
            isset($_POST["lastname_old"]) && $_POST["lastname_old"]!=='' &&
            isset($_POST["email_old"]) && $_POST["email_old"]!=='' &&
            isset($_POST["province_old"]) && $_POST["province_old"]!=='' ) {
            $stmt = $conn->prepare(
                "DELETE FROM `userdata` WHERE email_owner = ? AND firstname = ? AND lastname = ? AND email = ? AND province = ?"
            );
    
            $stmt->bind_param(
                "sssss",
                $_COOKIE["email"],
                $_POST["firstname_old"],
                $_POST["lastname_old"],
                $_POST["email_old"],
                $_POST["province_old"]
            );

            $stmt->execute();
            header("Location: ../pages/database.php");
        } else {
            echo "Error: Missing fields.";
            die("Invalid action.");
        }
    } else {
        die("Action not allowed.");
    }
?>