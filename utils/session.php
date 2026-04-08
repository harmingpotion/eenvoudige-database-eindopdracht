<?php
    function createNewSessionId($user_email) {
        $conn = new mysqli("localhost","root","","eenvoudige_database");
        if ($conn->connect_error) die("". $conn->connect_error);

        $new_session_id = "abc123def456";

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
?>