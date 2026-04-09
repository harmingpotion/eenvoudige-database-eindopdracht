<?php
    session_start();
    if ((isset($_COOKIE["email"]) || isset($_COOKIE["username"])) && isset($_COOKIE["session_id"])) {
        header("Location: dashboard.php");
    }

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
        <link rel="stylesheet" href="../static//stylesheets/main.css">
        <link rel="stylesheet" href="../static/stylesheets/auth.css">
    </head>
    <body>
        <form action="../utils/<?php echo $_GET['type']==="login" ? "login.php" : "signup.php" ?>" method="post">
            <h1>
                <?php
                    if ($_GET["type"] === "signup") echo "Sign up";
                    else echo "Log in";
                ?>
            </h1>
            <?php
                if ($_GET['type']==="login") {
                    echo '<input type="text" required placeholder="Email or username" name="identifier">';
                } else {
                    echo '<input type="email" required placeholder="Input an email..." name="email" value="' . (isset($_SESSION["temp_email"]) ? $_SESSION["temp_email"] : '') . '">';
                    if (isset($_SESSION["email_error"])) echo "<p class='error'>" . $_SESSION["email_error"] . "</p>";
                    echo '<input type="text" required placeholder="Choose a username..." name="username" value="' . (isset($_SESSION["temp_username"]) ? $_SESSION["temp_username"] : '') . '">';
                    if (isset($_SESSION["user_error"])) echo "<p class='error'>" . $_SESSION["user_error"] . "</p>";
                }
            ?>
            <input type="password" required placeholder="Password" name="password">
            <?php
                if (isset($_SESSION["password_error"])) echo "<p class='error'>" . $_SESSION["password_error"] . "</p>"; 
            ?>
            <?php
                if (isset($_SESSION["login_error"])) echo "<p class='error'>" . $_SESSION["login_error"] . "</p>"; 
            ?>
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

<?php
    if (isset($_SESSION["temp_email"])) $_SESSION["temp_email"] = '';
    if (isset($_SESSION["temp_username"])) $_SESSION["temp_username"] = '';
    if (isset($_SESSION["email_error"])) $_SESSION["email_error"] = '';
    if (isset($_SESSION["user_error"])) $_SESSION["user_error"] = '';
    if (isset($_SESSION["password_error"])) $_SESSION["password_error"] = '';
    if (isset($_SESSION["login_error"])) $_SESSION["login_error"] = '';
?>