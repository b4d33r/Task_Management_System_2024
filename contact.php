<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $phone = $_POST['Phone'];
    $message = $_POST['text'];

    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, message, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $name, $email, $phone, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Message sent successfully.'); window.location.href='contact.php';</script>";
    } else {
        echo "<script>alert('Error sending message. Please try again.'); window.location.href='contact.html';</script>";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Task Management System - Contact</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">    
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/foater_header.css">

    <style>
        .contact_section {
            padding: 80px 0;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        }

        .contact_text {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #2c3e50;
            font-family: 'Playfair Display', serif;
            text-align: center;
            position: relative;
        }

        .contact_text::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background-color: #007bff;
            margin: 10px auto 0;
            border-radius: 2px;
        }

        .contact_main {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .mail_text,
        .massage_text,
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            transition: border-color 0.3s, box-shadow 0.3s;
            font-size: 16px;
            font-family: 'Montserrat', sans-serif;
        }

        .mail_text:focus,
        .massage_text:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
            outline: none;
        }

        .send_bt {
            text-align: center;
            margin-top: 20px;
        }

        .send_button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .send_button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .send_button:focus {
            outline: none;
        }

        .contact_bg img {
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 100%;
            height: auto;
            transition: transform 0.3s;
        }

        .contact_bg img:hover {
            transform: scale(1.02);
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
            <a class="nav-link" href="contact.php">
                <i class="fas fa-envelope"></i> Contact
            </a>
        </li>
    </ul>
</div>
            </nav>
        </div>
    </div>

    <div class="contact_section layout_padding">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1 class="contact_text">Contactez-nous</h1>
                <div class="contact_main">
                    <form method="POST">
                        <input type="text" class="mail_text" placeholder="Nom" name="Name" required>
                        <input type="email" class="mail_text" placeholder="Email" name="Email" required>
                        <input type="tel" class="mail_text" placeholder="Téléphone" name="Phone" required>
                        <textarea class="massage_text" placeholder="Message" rows="5" name="text" required></textarea>
                        <div class="send_bt">
                            <button type="submit" class="send_button">ENVOYER</button>
                        </div>
                    </form>                        
                </div>
            </div>
            <div class="col-md-6">
                <div class="contact_bg"><img src="images/contact-bg.png" alt="Fond de contact"></div>
            </div>
        </div>
    </div>
</div>

    <div class="footer_section">
        <p>&copy; 2024 Gestion des Tâches | <a href="#">Politique de confidentialité</a> | <a href="#">Conditions générales</a></p>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/owl.carousel.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
    <script>
        $(document).ready(function(){
            $(".fancybox").fancybox({
                openEffect: "none",
                closeEffect: "none"
            });
        });
    </script>
</body>
</html>