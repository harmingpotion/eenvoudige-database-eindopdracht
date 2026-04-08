<?php
    session_start();
    if ((!isset($_COOKIE["email"]) && !isset($_COOKIE["username"])) || !isset($_COOKIE["session_id"])) {
        header("Location: auth.php");
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Dashboard
        </title>
    </head>
    <body>
        <h1>Dashboard</h1>
        <a href="../utils/logout.php">Log out</a>
    </body>
</html>