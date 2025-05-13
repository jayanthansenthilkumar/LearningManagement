<?php
require_once 'session.php';
checkUserAccess();

// Get user role from session
$userRole = $_SESSION['role'] ?? '';

// Redirect based on role
switch ($userRole) {
    case 'HOD':
        include 'hodDashboard.php';
        break;
    case 'student':
        include 'studentDashboard.php';
        break;
    case 'Staff':
        include 'staffDashboard.php';
        break;
    default:
        // Handle invalid role
        session_destroy();
        header('Location: login.php?error=invalid_role');
        exit();
}
