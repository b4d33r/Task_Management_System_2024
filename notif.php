<?php
include('db.php');
session_start();
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management System - Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/foater_header.css">
    
    <style>
        .stats-container {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            padding: 20px;
        }

        #notificationSection ul li {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        #notificationSection ul li:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .notification-message {
            font-size: 16px;
            color: #333;
            margin-bottom: 8px;
        }

        .notification-time {
            font-size: 14px;
            color: #888;
            font-style: italic;
        }

        .no-notifications {
            color: #e74c3c;
            font-size: 18px;
            font-weight: bold;
        }

        .logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            position: relative;
            display: inline-block;
        }

        .logo-text::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 100%;
            height: 2px;
            background-color: #ffffff;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .logo-text:hover::after {
            transform: scaleX(1);
        }

        .logo-text:hover {
            color: #ffd700;
        }
    </style>
</head>

<body>
<div class="header_section">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand logo-text" href="index.html">
                <i class="fas fa-tasks"></i> Task Management System
            </a>                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" href="index.html">
                <i class="fas fa-home"></i> Home
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="contact.php">
                <i class="fas fa-envelope"></i> Contact
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="profil.php">
                <i class="fas fa-user"></i> Profil
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="notif.php">
                <i class="fas fa-bell"></i> Notifications
            </a>
        </li>
    </ul>
</div>
            </nav>
        </div>
    </div>

    <div class="container">
        <h3>Notifications</h3>
        <div id="notificationSection">
            <?php if (count($notifications) > 0): ?>
                <ul>
                    <?php foreach ($notifications as $notification): ?>
                        <li>
                            <div class="notification-message">
                                <strong>Message:</strong> <?php echo $notification['message']; ?>
                            </div>
                            <div class="notification-time">
                                <strong>Created At:</strong> 
                                <?php echo date('F j, Y, g:i a', strtotime($notification['created_at'])); ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="no-notifications">No notifications available.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer_section">
        <p>&copy; 2024 Gestion des Tâches | <a href="#">Politique de confidentialité</a> | <a href="#">Termes et conditions</a></p>
    </div>
</body>
</html>