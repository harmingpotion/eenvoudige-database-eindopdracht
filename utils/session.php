<?php
    function createNewSessionId($user_email) {
        $conn = new mysqli("localhost","root","","eenvoudige_database");
        if ($conn->connect_error) die("". $conn->connect_error);

        $new_session_id = hash("sha256", random_bytes(16));

        $sql = "UPDATE `users` SET `session_id`='" . $new_session_id . "' WHERE email = '" . $user_email . "'";
        $conn->query($sql);
        
        setcookie("email", $user_email, time() + (3600 * 24), "/");
        setcookie("session_id", $new_session_id, time() + (3600 * 24), "/");
        return $new_session_id;
    }

    function checkSessionId($user_email, $session_id) {
        $conn = new mysqli("localhost","root","","eenvoudige_database");
        if ($conn->connect_error) die("". $conn->connect_error);
        $sql = "SELECT * FROM `users` WHERE email = '" . $user_email . "' AND session_id = '" . $session_id . "'";
        if ($conn->query($sql)->num_rows==1) return true;
        
        return false;
    }

    function loadSession() {
        if (!checkSessionId($_COOKIE["email"], $_COOKIE["session_id"])) {
            setcookie("email", '', time() - 3600, "/");
            setcookie("username", '', time() - 3600, "/");
            setcookie("session_id", '', time() - 3600, "/");
            session_destroy();
            header("Location: ../pages/auth.php");
        }
    }
?>