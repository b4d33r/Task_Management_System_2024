<?php
session_start();
include('db.php');
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname'] ?? '');
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = '<div class="alert alert-danger">Format d\'email invalide.</div>';
    }
    else if ($password !== $confirm_password) {
        $message = '<div class="alert alert-danger">Les mots de passe ne correspondent pas.</div>';
    }
    else if (strlen($password) < 6) {
        $message = '<div class="alert alert-danger">Le mot de passe doit contenir au moins 6 caractères.</div>';
    }
    else {
        $check_email = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($check_email);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $message = '<div class="alert alert-danger">Cette adresse email est déjà utilisée.</div>';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (Nom, Prenom, email, pass) VALUES (?, ?, ?, ?)";
            
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssss", $lastname, $firstname, $email, $hashed_password);
                
                if ($stmt->execute()) {
                    $message = '<div class="alert alert-success">Compte créé avec succès ! Vous pouvez maintenant vous connecter.</div>';
                    $firstname = $lastname = $email = '';
                } else {
                    $message = '<div class="alert alert-danger">Erreur lors de la création du compte : ' . $stmt->error . '</div>';
                }
                $stmt->close();
            } else {
                $message = '<div class="alert alert-danger">Erreur de préparation : ' . $conn->error . '</div>';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Management System - Inscription</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="icon" href="images/fevicon.png" type="image/gif" />
    <link rel="stylesheet" href="css/foater_header.css">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .account_section {
            flex: 1;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: auto;
        }

        .account_form {
            width: 100%;
            max-width: 550px;
            background: rgba(255, 255, 255, 0.95);
            padding: 35px 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            backdrop-filter: blur(10px);
            margin: 0 auto;
        }

        .account_form h2 {
            font-size: 2.2rem;
            color: var(--text-dark);
            margin-bottom: 30px;
            font-weight: 600;
            position: relative;
            text-align: center;
        }

        .account_form h2:after {
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

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            color: var(--text-dark);
            font-size: 0.95rem;
            margin-bottom: 8px;
            display: block;
        }

        .input-group-text {
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            border: 2px solid #e2e8f0;
            border-right: none;
            background-color: #f8f9fa;
        }

        .form-control {
            height: 48px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            padding: 10px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .input-group .form-control {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.15);
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

        .alert-success {
            background-color: #f0fff4;
            color: #38a169;
            border-left: 4px solid #38a169;
        }

        .text-primary {
            color: var(--secondary-color) !important;
        }

        .text-primary:hover {
            color: #2980b9 !important;
            text-decoration: none;
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

        @media (max-width: 768px) {
            .account_section {
                padding: 20px;
            }
            
            .account_form {
                padding: 25px 20px;
            }
            
            .account_form h2 {
                font-size: 1.8rem;
            }
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
            <a class="nav-link" href="login.php">
                <i class="fas fa-sign-in-alt"></i> Connexion
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

    <div class="account_section">
        <div class="account_form">
            <h2 class="text-center">Créer un compte</h2>
            <?php if ($message) echo $message; ?>
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="firstname">Prénom</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder=" " value="<?php echo htmlspecialchars($firstname ?? ''); ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="lastname">Nom</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder=" " value="<?php echo htmlspecialchars($lastname ?? ''); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Adresse Email</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                                </svg>
                            </span>
                        </div>
                        <input type="email" class="form-control" id="email" name="email" placeholder=" " value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Mot de Passe</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                                </svg>
                            </span>
                        </div>
                        <input type="password" class="form-control" id="password" name="password" placeholder=" " minlength="6" required>
                    </div>
                    <small class="form-text text-muted">Le mot de passe doit contenir au moins 6 caractères</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmez le Mot de Passe</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                                </svg>
                            </span>
                        </div>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder=" " minlength="6" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block mb-3">Créer un compte</button>
                <div class="text-center mt-3">
                    <p class="text-muted">
                        Vous avez déjà un compte? 
                        <a href="login.php" class="text-primary">Connectez-vous ici</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <footer class="footer_section">
        <p>&copy; 2024 Gestion des Tâches | <a href="#">Politique de confidentialité</a> | <a href="#">Termes et conditions</a></p>
    </footer>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script>
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            event.preventDefault();
            document.getElementById('confirmationMessage').style.display = 'block';
            this.reset();
        });
    </script>
</body>
</html>