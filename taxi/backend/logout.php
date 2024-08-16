<?php
session_start();

if (isset($_POST['logout'])) {
    setcookie('username', '', time() - 3600, '/');
    setcookie('password', '', time() - 3600, '/');
    session_destroy();

    header('Location: ../pages/login.php');
    exit;
}
