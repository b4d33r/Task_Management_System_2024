<?php
session_start();
include('db.php');

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Error fetching user data: " . mysqli_error($conn));
}
$user = mysqli_fetch_assoc($result);

if (isset($_POST['update_profile'])) {
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $update_query = "UPDATE users SET 
                    Prenom = '$firstname',
                    Nom = '$lastname',
                    email = '$email'
                    WHERE id = $user_id";

    if (mysqli_query($conn, $update_query)) {
        header("Location: profil.php?success=1");
        exit();
    } else {
        $errorMessage = 'Erreur lors de la mise à jour du profil: ' . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Management System - Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/foater_header.css">
    <style>
        .profile-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            position: relative;
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto 20px;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #6c757d;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin: 30px 0;
        }

        .stat-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-card h3 {
            color: var(--secondary-color);
            margin: 0;
        }

        .name-and-badge-container {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .centered-name {
            margin: 0;
            text-align: center;
        }

        .badge-and-button {
            position: absolute;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .go-premium-button {
            display: inline-block;
            background: linear-gradient(135deg, #007bff, #00ff88);
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .go-premium-button:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }

        .go-premium-button i {
            margin-right: 8px;
        }

        .premium-badge {
            display: inline-block;
            background: linear-gradient(135deg, #ffd700, #ff8c00);
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: glow 1.5s infinite alternate;
        }

        @keyframes glow {
            0% {
                box-shadow: 0 0 10px rgba(255, 215, 0, 0.7);
            }
            100% {
                box-shadow: 0 0 20px rgba(255, 215, 0, 0.9);
            }
        }

        .premium-badge i {
            margin-right: 5px;
        }

        .premium-badge:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
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
        <div class="profile-container">
        <?php if (isset($_GET['subscription']) && $_GET['subscription'] === 'success'): ?>
        <div class="alert alert-success">
            Félicitations ! Votre abonnement premium a été activé avec succès ! 🎉
        </div>
    <?php endif; ?>
    <div class="profile-header">
    <div class="profile-avatar">
        <?php echo strtoupper(substr($user['Prenom'], 0, 1)); ?>
    </div>
    <div class="name-and-badge-container">
        <h2 class="centered-name">
            <?php echo $user['Prenom'] . ' ' . $user['Nom']; ?>
        </h2>
        <div class="badge-and-button">
            <?php if ($user['is_premium'] == 1): ?>
                <span class="premium-badge">
                    <i class="fas fa-crown"></i> Premium
                </span>
            <?php endif; ?>
            <?php if ($user['is_premium'] == 0): ?>
                <a href="premium.php" class="go-premium-button">
                    <i class="fas fa-star"></i> Go Premium
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
            

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">Profile mis à jour avec succès!</div>
            <?php endif; ?>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3><?php echo mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) FROM taches WHERE statut='Terminé'"))[0]; ?></h3>
                    <p>Tâches Terminées</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) FROM taches WHERE statut='En cours'"))[0]; ?></h3>
                    <p>Tâches En Cours</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) FROM taches"))[0]; ?></h3>
                    <p>Total des Tâches</p>
                </div>
            </div>

            <form method="POST" class="mt-4">
                <div class="form-group">
                    <label>Prénom</label>
                    <input type="text" class="form-control" name="firstname" value="<?php echo $user['Prenom']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" class="form-control" name="lastname" value="<?php echo $user['Nom']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $user['email']; ?>" required>
                </div>
                <button type="submit" name="update_profile" class="btn btn-primary">Mettre à jour le profil</button>
            </form>

            <form method="POST">
                <button type="submit" name="logout" class="btn btn-danger mt-4">Logout</button>
            </form>
        </div>
    </div>

    <div class="footer_section">
        <p>&copy; 2024 Gestion des Tâches | <a href="#">Politique de confidentialité</a> | <a href="#">Termes et conditions</a></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
</body>
</html>