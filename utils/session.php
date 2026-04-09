<?php
    function createNewSessionId($user_email) {
        $conn = new mysqli("localhost","root","","eenvoudige_database");
        if ($conn->connect_error) die($conn->connect_error);

        $new_session_id = hash("sha256", random_bytes(16));

        $upmt = $conn->prepare("UPDATE users SET session_id = ? WHERE email = ?");
        $upmt->bind_param("ss", $new_session_id, $user_email);
        $upmt->execute();
        
        setcookie("email", $user_email, time() + (3600 * 24), "/");
        setcookie("session_id", $new_session_id, time() + (3600 * 24), "/");

        return $new_session_id;
    }

    function checkSessionId($user_email, $session_id) {
        $conn = new mysqli("localhost","root","","eenvoudige_database");
        if ($conn->connect_error) die($conn->connect_error);

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND session_id = ?");
        $stmt->bind_param("ss", $user_email, $session_id);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->num_rows == 1;
    }

    function loadSession() {
        if (!checkSessionId($_COOKIE["email"], $_COOKIE["session_id"])) {
            setcookie("email", '', time() - 3600, "/");
            setcookie("username", '', time() - 3600, "/");
            setcookie("session_id", '', time() - 3600, "/");
            session_destroy();
            header("Location: ../pages/auth.php");
            exit();
        }
    }
?>