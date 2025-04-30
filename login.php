<?php
include 'db.php';

session_start();

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if (password_verify($password, $user['pass'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['Nom'] = $user['Nom'];
            $_SESSION['Prenom'] = $user['Prenom'];
            $_SESSION['email'] = $user['email'];

            header("Location: dashboard.php");
            exit;
        } else {
            $error_message = "Mot de passe incorrect.";
        }
    } else {
        $error_message = "Aucun utilisateur trouvé avec cet email.";
    }
}
?>
<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Management System - Connexion</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/foater_header.css">
    <link rel="icon" href="images/fevicon.png" type="image/gif" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .login_section {
            flex: 1;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: auto;
        }

        .form-container {
            width: 100%;
            max-width: 450px;
            background: rgba(255, 255, 255, 0.95);
            padding: 35px 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            backdrop-filter: blur(10px);
            margin: 0 auto;
        }

        .login-title {
            font-size: 2.2rem;
            color: var(--text-dark);
            margin-bottom: 15px;
            font-weight: 600;
            position: relative;
        }

        .login-title:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: var(--secondary-color);
            border-radius: 3px;
        }

        .login-subtitle {
            color: #6c757d;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            color: var(--text-dark);
            font-size: 0.95rem;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            height: 48px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            padding: 10px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.15);
        }

        .form-check {
            padding-left: 1.8rem;
        }

        .form-check-input {
            margin-top: 0.3rem;
            margin-left: -1.8rem;
        }

        .form-check-label {
            color: #6c757d;
        }

        .btn-primary {
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(45deg, var(--secondary-color), #2980b9);
            border: none;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(52, 152, 219, 0.2);
            background: linear-gradient(45deg, #2980b9, var(--secondary-color));
        }

        .links {
            margin-top: 20px;
        }

        .links a {
            color: var(--secondary-color);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .links a:hover {
            color: #2980b9;
        }

        .alert {
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 25px;
            border: none;
        }

        .alert-danger {
            background-color: #fff5f5;
            color: var(--accent-color);
            border-left: 4px solid var(--accent-color);
        }

        .modal-content {
            border-radius: 20px;
            border: none;
        }

        .modal-header {
            border-bottom: none;
            padding: 25px 30px 10px;
        }

        .modal-body {
            padding: 20px 30px 30px;
        }

        .modal-title {
            font-weight: 600;
            color: var(--text-dark);
        }

        @media (max-width: 768px) {
            .login_section {
                padding: 20px;
            }

            .form-container {
                padding: 25px 20px;
            }

            .login-title {
                font-size: 1.8rem;
            }
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
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
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
            <a class="nav-link" href="register.php">
                <i class="fas fa-user-plus"></i> Inscription
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="contact.php">
                <i class="fas fa-envelope"></i> Contact
            </a>
        </li>
    </ul>
</div>
            </nav>
        </div>
    </div>

    <div class="login_section">
        <div class="container d-flex justify-content-center align-items-center">
            <div class="form-container">
                <div class="text-center">
                    <h2 class="login-title">Connectez-vous</h2>
                    <p class="login-subtitle">Entrez vos identifiants pour commencer à gérer vos tâches.</p>
                </div>
                <form method="POST" action="login.php">
                    <div class="form-group">
                        <label for="email">Email/Username</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="Entrez votre email ou nom d'utilisateur" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Entrez votre mot de passe" required>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" id="remember" name="remember" class="form-check-input">
                        <label for="remember" class="form-check-label">Se souvenir de moi</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
                    <div class="links mt-3 text-center">
                        <a href="#" data-toggle="modal" data-target="#forgotPasswordModal">Mot de passe oublié ?</a>
                        <span> | </span>
                        <a href="register.php">Créer un compte</a>
                    </div>
                </form>

                <?php if (!empty($error_message)) { ?>
                    <div class="alert alert-danger mt-3"><?php echo $error_message; ?></div>
                <?php } ?>

            </div>
        </div>
    </div>

    <div class="footer_section">
        <p>&copy; 2024 Gestion des Tâches | <a href="#">Politique de confidentialité</a> | <a href="#">Termes et conditions</a></p>
    </div>

    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Mot de passe oublié</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="resetEmail">Entrez votre email pour réinitialiser votre mot de passe</label>
                            <input type="email" class="form-control" id="resetEmail" placeholder="Entrez votre email" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Réinitialiser le mot de passe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>