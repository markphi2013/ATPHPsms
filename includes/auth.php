<?php
session_start();

function register_user($username, $password) {
    global $pdo;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $hashed_password]);
}

function login_user($username, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        return true;
    }
    return false;
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function logout_user() {
    unset($_SESSION['user_id']);
    session_destroy();
}