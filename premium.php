<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

if (isset($_POST['subscribe'])) {
    $card_number = mysqli_real_escape_string($conn, $_POST['card_number']);
    $expiration_date = mysqli_real_escape_string($conn, $_POST['expiration_date']);
    $cvv = mysqli_real_escape_string($conn, $_POST['cvv']);
    $rib = mysqli_real_escape_string($conn, $_POST['rib']);

    if (!preg_match('/^\d{16}$/', $card_number)) {
        $error_message = "Numéro de carte invalide (16 chiffres requis).";
    }
    elseif (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiration_date)) {
        $error_message = "Date d'expiration invalide (format MM/YY requis).";
    }
    elseif (!preg_match('/^\d{3,4}$/', $cvv)) {
        $error_message = "CVV invalide (3 ou 4 chiffres requis).";
    }
    elseif (empty($rib)) {
        $error_message = "RIB requis.";
    }
    else {
        $key ='d2f1e5a8b7c4d3e6f9a8b7c4d3e6f9a8';
        $encrypted_card = openssl_encrypt($card_number, 'AES-128-ECB', $key);
        $encrypted_cvv = openssl_encrypt($cvv, 'AES-128-ECB', $key);
        $encrypted_rib = openssl_encrypt($rib, 'AES-128-ECB', $key);

        $query = "INSERT INTO premium_subscriptions (user_id, card_number, expiration_date, cvv, rib) 
                  VALUES ($user_id, '$encrypted_card', '$expiration_date', '$encrypted_cvv', '$encrypted_rib')";

        if (mysqli_query($conn, $query)) {
            mysqli_query($conn, "UPDATE users SET is_premium = TRUE WHERE id = $user_id");
            $success_message = "Félicitations ! Votre abonnement premium a été activé avec succès !";
            header("Location: profil.php?subscription=success");
            exit();
        } else {
            $error_message = "Erreur lors de l'inscription: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Management System - Premium Subscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/foater_header.css">
    <style>
        .premium-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
        }

        .premium-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 40px 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .price-tag {
            font-size: 3rem;
            color: #ffd700;
            font-weight: bold;
            margin: 15px 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .premium-features {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 1.1rem;
            color: #333;
            padding: 10px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .feature-item:before {
            content: "\2713";
            color: #28a745;
            font-weight: bold;
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .payment-form {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .payment-form h3 {
            margin-bottom: 20px;
            color: #333;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.5);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            padding: 12px 20px;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 8px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .alert {
            animation: slideIn 0.5s ease-out;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .invalid-feedback {
            display: none;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .was-validated .form-control:invalid ~ .invalid-feedback {
            display: block;
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
        <div class="premium-container">
            <?php if ($success_message): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $success_message; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error_message; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="premium-header">
                <h2>Passez à Premium</h2>
                <p class="price-tag">29.99€ / mois</p>
                <p>Débloquez toutes les fonctionnalités premium dès aujourd'hui !</p>
            </div>

            <div class="premium-features">
                <h3>Fonctionnalités Premium</h3>
                <div class="feature-item">Nombre illimité de tâches</div>
                <div class="feature-item">Accès aux statistiques avancées</div>
                <div class="feature-item">Exportation des rapports</div>
                <div class="feature-item">Support prioritaire 24/7</div>
                <div class="feature-item">Collaborateurs illimités</div>
            </div>

            <div class="payment-form">
                <h3>Informations de paiement</h3>
                <form method="POST" class="mt-4 needs-validation" novalidate>
                    <div class="form-group">
                        <label>Numéro de carte</label>
                        <input type="text" class="form-control" name="card_number" required 
                               pattern="[0-9]{16}" maxlength="16" placeholder="1234 5678 9012 3456">
                        <div class="invalid-feedback">
                            Veuillez entrer un numéro de carte valide (16 chiffres)
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date d'expiration</label>
                                <input type="text" class="form-control" name="expiration_date" required 
                                       pattern="(0[1-9]|1[0-2])\/[0-9]{2}" placeholder="MM/YY">
                                <div class="invalid-feedback">
                                    Format requis : MM/YY
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>CVV</label>
                                <input type="text" class="form-control" name="cvv" required 
                                       pattern="[0-9]{3,4}" maxlength="4" placeholder="123">
                                <div class="invalid-feedback">
                                    CVV invalide (3 ou 4 chiffres)
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>RIB</label>
                        <input type="text" class="form-control" name="rib" required 
                               placeholder="FR76 1234 5678 9012 3456 7890 123">
                        <div class="invalid-feedback">
                            Veuillez entrer un RIB valide
                        </div>
                    </div>
                    <button type="submit" name="subscribe" class="btn btn-primary btn-block btn-lg">
                        S'abonner maintenant
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="footer_section">
        <p>&copy; 2024 Gestion des Tâches | <a href="#">Politique de confidentialité</a> | <a href="#">Termes et conditions</a></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    
    <script>
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
    </script>
</body>
</html>