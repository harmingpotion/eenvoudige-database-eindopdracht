<?php
    session_start();
    if ((!isset($_COOKIE["email"]) && !isset($_COOKIE["username"])) || !isset($_COOKIE["session_id"])) {
        header("Location: auth.php");
    }
    include("../utils/session.php");
    loadSession();

    $conn = new mysqli("localhost","root","","eenvoudige_database");
    if ($conn->connect_error) die($conn->connect_error);

    $sql = "SELECT firstname, lastname, email, province FROM `userdata` WHERE email_owner = '" . $_COOKIE["email"] . "'";
    $result = $conn->query($sql);
?>


<!DOCTYPE html>
<html>
    <head>
        <title>
            Account
        </title>
        <link rel="stylesheet" href="../static/stylesheets/main.css">
        <link rel="stylesheet" href="../static/stylesheets/database.css">
    </head>
    <body>
        <h1>Database</h1>
        <hr>
        <a class="return" href="./dashboard.php">&lt; Return to dashboard</a>

        <section id="edit_user">
            <form action="../utils/update_database.php" method="post" id="input_form">
                <input type="hidden" name="type" value="edit_user">
                <input type="text" id="firstname" name="firstname" placeholder="First name">
                <input type="hidden" id="firstname_old" name="firstname_old">
                <input type="text" id="lastname" name="lastname" placeholder="Last name">
                <input type="hidden" id="lastname_old" name="lastname_old">
                <input type="text" id="email" name="email" placeholder="Email">
                <input type="hidden" id="email_old" name="email_old">
                <input type="text" id="province" name="province" placeholder="Province">
                <input type="hidden" id="province_old" name="province_old">
                <button type="submit">Edit</button>
            </form>
            <form action="../utils/update_database.php" method="post">
                <input type="hidden" name="type" value="delete_user">
                <input type="hidden" id="firstname_old_2" name="firstname_old">
                <input type="hidden" id="lastname_old_2" name="lastname_old">
                <input type="hidden" id="email_old_2" name="email_old">
                <input type="hidden" id="province_old_2" name="province_old">
                <button type="submit">Delete</button>
            </form>
            <button onclick="closeEdit()">Close</button>
        </section>
        
        <table>
            <tr>
                <th>First name</th>
                <th>Last name</th>
                <th>Email</th>
                <th>Province</th>
                <th>Options</th>
            </tr>

            <?php
            $r = 0;
                if ($result->num_rows>0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr id='row_" . $r . "'>";
                        echo "<td class='firstname'>" . $row["firstname"] . "</td>";
                        echo "<td class='lastname'>" . $row["lastname"] . "</td>";
                        echo "<td class='email'>" . $row["email"] . "</td>";
                        echo "<td class='province'>" . $row["province"] . "</td>";
                        echo "<td><button onclick='openEdit(" . $r . ");'>Edit</button></td>";
                        echo "</tr>";
                        $r++;
                    }
                }
            ?>

            <form action="../utils/update_database.php" method="post">
                <input type="hidden" name="type" value="add_user">
                <tr>
                    <th><input name="firstname" placeholder="First name"></th>
                    <th><input name="lastname" placeholder="Last name"></th>
                    <th><input name="email" placeholder="Email"></th>
                    <th><input name="province" placeholder="Province"></th>
                    <th><button type="submit">Add user</button></th>
                </tr>
            </form>
            <?php
                
            ?>
        </table>
    </body>
    <script src="../static/scripts/database.js" defer></script>
</html>