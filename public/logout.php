<?php
require_once '../includes/auth.php';
logout_user();
header('Location: login.php');
exit;
