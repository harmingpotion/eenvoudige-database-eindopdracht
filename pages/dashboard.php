<?php
    session_start();
    if ((!isset($_COOKIE["email"]) && !isset($_COOKIE["username"])) || !isset($_COOKIE["session_id"])) {
        header("Location: auth.php");
    }
    include("../utils/session.php");
    loadSession();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Dashboard
        </title>
        <link rel="stylesheet" href="../static/stylesheets/main.css">
        <link rel="stylesheet" href="../static/stylesheets/dashboard.css">
    </head>
    <body>
        <h1>Dashboard</h1>
        <hr>
        
        <section id="pages">
            <a href="./account.php">Account</a>
            <a href="../utils/logout.php">Log out</a>
        </section>
    </body>
</html>