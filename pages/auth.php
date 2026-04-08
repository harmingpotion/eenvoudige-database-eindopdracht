<?php
    session_start();
    $conn = new mysqli("localhost", "root", "", "eenvoudige_database");

    if (!isset($_GET["type"])) {
        header("Location: auth.php?type=login");
    }

    
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Auth - <?php
                if ($_GET["type"] === "signup") echo "Sign Up";
                else echo "Log In";
            ?>
        </title>
    </head>
    <body>
        <form action="../utils/<?php echo $_GET['type']==="login" ? "login.php" : "signup.php" ?>" method="post">
            <p>
                <?php
                    if ($_GET["type"] === "signup") echo "Sign up";
                    else echo "Log in";
                ?>
            </p>
            <input type="hidden" name="type" value="<?php echo $_GET['type'] ?>">
            <?php
                if ($_GET['type']==="login") {
                    echo '<input type="text" required placeholder="Email or username" name="identifier">';
                } else {
                    echo '<input type="text" required placeholder="Choose a username..." name="username">';
                    echo '<input type="email" required placeholder="Input an email..." name="email">';
                }
            ?>
            <input type="password" required placeholder="Password" name="password">
            <p><?php
                if (isset($_SESSION["login_error"])) echo $_SESSION["login_error"];
                $_SESSION["login_error"] = '';
            ?></p>
            <button type="submit">
                <?php echo $_GET['type']==="login" ? "Log in" : "Sign up"?>
            </button>
            <a href="<?php echo $_GET['type']==="login" ? "auth.php?type=signup" : "auth.php?type=login" ?>">
                <?php
                    echo $_GET["type"] === "login" ? "Sign up" : "Log in";
                ?>
            </a>
        </form>
    </body>
</html>