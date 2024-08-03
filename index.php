<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

// Start the session
session_start();

// Check if the user is logged in
if (is_logged_in()) {
    // If logged in, redirect to the dashboard
    header("Location: public/dashboard.php");
    exit();
} else {
    // If not logged in, redirect to the login page
    header("Location: public/login.php");
    exit();
}