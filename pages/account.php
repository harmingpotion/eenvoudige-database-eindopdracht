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
            Account
        </title>
        <link rel="stylesheet" href="../static/stylesheets/main.css">
        <link rel="stylesheet" href="../static/stylesheets/account.css">
    </head>
    <body>
        <h1>Account</h1>
        <a href="./dashboard.php">Return to dashboard</a>

        <form action="../utils/update.php" method="post">
            <input type="hidden" name="value" value="email">
            <input type="email" name="email" placeholder="New email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Change</button>
        </form>

        <form action="../utils/update.php" method="post">
            <input type="hidden" name="value" value="username">
            <input type="text" name="username" placeholder="New username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Change</button>
        </form>

        <form action="../utils/update.php" method="post">
            <input type="hidden" name="value" value="password">
            <input type="password" name="new_password" placeholder="New password" required>
            <input type="password" name="old_password" placeholder="Old password" required>
            <button type="submit">Change</button>
        </form>

        <?php
            if (isset($_SESSION["modified"])) echo $_SESSION["modified"];
            if (isset($_SESSION["modify_message"])) echo $_SESSION["modify_message"];
        ?>
    </body>
</html>