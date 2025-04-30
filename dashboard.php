<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
require_once 'db.php';

if (isset($_POST['ajouter'])) {
    $nom_tache = mysqli_real_escape_string($conn, $_POST['nom_tache']);
    $echeance = mysqli_real_escape_string($conn, $_POST['echeance']);
    $priorite = mysqli_real_escape_string($conn, $_POST['priorite']);
    $statut = mysqli_real_escape_string($conn, $_POST['statut']);
    $progres = intval($_POST['progres']);

    if ($progres < 0 || $progres > 100) {
        $_SESSION['notif'] = 'Le progrès doit être entre 0 et 100.';
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    $query = "INSERT INTO taches (nom_tache, echeance, priorite, statut, progres, user_id) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssssii', $nom_tache, $echeance, $priorite, $statut, $progres, $userId);

    if (mysqli_stmt_execute($stmt)) {
        $notifMessage = "La tâche '$nom_tache' a été ajoutée.";
        $notifQuery = "INSERT INTO notifications (user_id, message, created_at) 
                       VALUES (?, ?, NOW())";
        $notifStmt = mysqli_prepare($conn, $notifQuery);
        mysqli_stmt_bind_param($notifStmt, 'is', $userId, $notifMessage);
        mysqli_stmt_execute($notifStmt);

        $_SESSION['notif'] = 'Tâche ajoutée avec succès!';
    } else {
        $_SESSION['notif'] = 'Erreur lors de l\'ajout de la tâche.';
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['modifier'])) {
    $id = intval($_POST['id_tache']);
    $nom_tache = mysqli_real_escape_string($conn, $_POST['nom_tache']);
    $echeance = mysqli_real_escape_string($conn, $_POST['echeance']);
    $priorite = mysqli_real_escape_string($conn, $_POST['priorite']);
    $statut = mysqli_real_escape_string($conn, $_POST['statut']);
    $progres = intval($_POST['progres']);

    if ($progres < 0 || $progres > 100) {
        $_SESSION['notif'] = 'Le progrès doit être entre 0 et 100.';
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    $query = "UPDATE taches 
              SET nom_tache=?, echeance=?, priorite=?, statut=?, progres=? 
              WHERE id_tache=? AND user_id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssssiii', $nom_tache, $echeance, $priorite, $statut, $progres, $id, $userId);

    if (mysqli_stmt_execute($stmt)) {
        $notifMessage = "La tâche '$nom_tache' a été modifiée.";
        $notifQuery = "INSERT INTO notifications (user_id, message, created_at) 
                       VALUES (?, ?, NOW())";
        $notifStmt = mysqli_prepare($conn, $notifQuery);
        mysqli_stmt_bind_param($notifStmt, 'is', $userId, $notifMessage);
        mysqli_stmt_execute($notifStmt);

        $_SESSION['notif'] = 'Tâche modifiée avec succès!';
    } else {
        $_SESSION['notif'] = 'Erreur lors de la modification de la tâche.';
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['supprimer'])) {
    $id = intval($_POST['id_tache']);

    $fetchQuery = "SELECT nom_tache FROM taches WHERE id_tache=? AND user_id=?";
    $fetchStmt = mysqli_prepare($conn, $fetchQuery);
    mysqli_stmt_bind_param($fetchStmt, 'ii', $id, $userId);
    mysqli_stmt_execute($fetchStmt);
    mysqli_stmt_bind_result($fetchStmt, $nom_tache);
    mysqli_stmt_fetch($fetchStmt);
    mysqli_stmt_close($fetchStmt);

    $query = "DELETE FROM taches WHERE id_tache=? AND user_id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $id, $userId);

    if (mysqli_stmt_execute($stmt)) {
        $notifMessage = "La tâche '$nom_tache' a été supprimée.";
        $notifQuery = "INSERT INTO notifications (user_id, message, created_at) 
                       VALUES (?, ?, NOW())";
        $notifStmt = mysqli_prepare($conn, $notifQuery);
        mysqli_stmt_bind_param($notifStmt, 'is', $userId, $notifMessage);
        mysqli_stmt_execute($notifStmt);

        $_SESSION['notif'] = 'Tâche supprimée avec succès!';
    } else {
        $_SESSION['notif'] = 'Erreur lors de la suppression de la tâche.';
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$total = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) FROM taches WHERE user_id=$userId"))[0];
$terminees = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) FROM taches WHERE statut='Terminé' AND user_id=$userId"))[0];
$en_attente = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) FROM taches WHERE statut='En attente' AND user_id=$userId"))[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Management System - Dashboard</title>
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
            background: var(--text-light);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-box {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            flex: 1;
            margin: 0 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        .stat-box h3 {
            margin: 0;
            color: var(--secondary-color);
            font-size: 32px;
            font-weight: 700;
        }

        .stat-box p {
            margin: 10px 0 0;
            color: var(--text-dark);
            font-size: 16px;
            font-weight: 500;
        }

        .stat-box i {
            font-size: 40px;
            color: var(--secondary-color);
            margin-bottom: 15px;
        }

        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background-color: var(--primary-color);
            color: var(--text-light);
        }

        .table th, .table td {
            vertical-align: middle;
            padding: 12px;
        }

        .table tbody tr {
            transition: background-color 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: var(--text-light);
        }

        .badge {
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 12px;
            font-weight: 500;
        }

        .badge-primary {
            background-color: var(--secondary-color);
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-warning {
            background-color: #ffc107;
        }

        .badge-danger {
            background-color: var(--accent-color);
        }

        .progress {
            height: 20px;
            border-radius: 10px;
            background-color: #e9ecef;
            overflow: hidden;
        }

        .progress-bar {
            background-color: var(--secondary-color);
            font-size: 12px;
            font-weight: 500;
            line-height: 20px;
            color: transparent;
            text-indent: -9999px;
        }

        .btn-add {
            margin-bottom: 20px;
            background-color: var(--secondary-color);
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-add:hover {
            background-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .btn-edit, .btn-danger {
            font-size: 14px;
            padding: 8px 16px;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-edit:hover, .btn-danger:hover {
            transform: translateY(-2px);
        }

        .btn-edit {
            background-color: var(--secondary-color);
            border: none;
        }

        .btn-danger {
            background-color: var(--accent-color);
            border: none;
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
            margin-left: auto;
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
        <h1 class="mt-4 mb-4">Tableau de Bord: Suivi et Gestion des Tâches</h1>

        <div class="stats-container">
            <div class="stat-box">
                <i class="fas fa-tasks"></i>
                <h3><?php echo $total; ?></h3>
                <p>Total des tâches</p>
            </div>
            <div class="stat-box">
                <i class="fas fa-check-circle"></i>
                <h3><?php echo $terminees; ?></h3>
                <p>Tâches terminées</p>
            </div>
            <div class="stat-box">
                <i class="fas fa-clock"></i>
                <h3><?php echo $en_attente; ?></h3>
                <p>Tâches en attente</p>
            </div>
        </div>

        <div class="add-task">
            <button class="btn btn-primary btn-add" data-toggle="modal" data-target="#taskModal">
                <i class="fas fa-plus"></i> Ajouter une Tâche
            </button>
        </div>

        <div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskModalLabel">Gérer une Tâche</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="taskForm" method="POST">
                            <input type="hidden" name="id_tache" id="task_id">
                            <div class="form-group">
                                <label>Nom de la Tâche</label>
                                <input type="text" name="nom_tache" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Échéance</label>
                                <input type="date" name="echeance" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Priorité</label>
                                <select name="priorite" class="form-control" required>
                                    <option value="Basse">Basse</option>
                                    <option value="Moyenne">Moyenne</option>
                                    <option value="Élevée">Élevée</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Statut</label>
                                <select name="statut" class="form-control" required>
                                    <option value="En cours">En cours</option>
                                    <option value="Terminé">Terminé</option>
                                    <option value="En attente">En attente</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Progrès (%)</label>
                                <input type="number" name="progres" class="form-control" min="0" max="100" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary" id="submitBtn" name="ajouter">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="task-summary">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom de la Tâche</th>
                            <th>Échéance</th>
                            <th>Priorité</th>
                            <th>Statut</th>
                            <th>Progrès (%)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($conn, "SELECT * FROM taches WHERE user_id = $userId");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['id_tache'] . "</td>";
                            echo "<td>" . $row['nom_tache'] . "</td>";
                            echo "<td>" . $row['echeance'] . "</td>";
                            echo "<td><span class='badge badge-primary'>" . $row['priorite'] . "</span></td>";
                            echo "<td><span class='badge badge-success'>" . $row['statut'] . "</span></td>";
                            echo "<td><div class='progress'><div class='progress-bar' role='progressbar' style='width: " . $row['progres'] . "%;' aria-valuenow='" . $row['progres'] . "' aria-valuemin='0' aria-valuemax='100'></div></div></td>";
                            echo "<td>
                                    <button class='btn btn-primary btn-edit' 
                                            data-id='" . $row['id_tache'] . "' 
                                            data-nom_tache='" . $row['nom_tache'] . "' 
                                            data-echeance='" . $row['echeance'] . "' 
                                            data-priorite='" . $row['priorite'] . "' 
                                            data-statut='" . $row['statut'] . "' 
                                            data-progres='" . $row['progres'] . "'><i class='fas fa-edit'></i> Modifier</button>
                                    <form method='POST' style='display:inline;' onsubmit='return confirm(\"Êtes-vous sûr de vouloir supprimer cette tâche?\");'>
                                        <input type='hidden' name='id_tache' value='" . $row['id_tache'] . "'>
                                        <button type='submit' name='supprimer' class='btn btn-danger'><i class='fas fa-trash'></i> Supprimer</button>
                                    </form>
                                </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
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
    $(document).ready(function() {
        $('.btn-edit').click(function() {
            var id = $(this).data('id');
            var nom_tache = $(this).data('nom_tache');
            var echeance = $(this).data('echeance');
            var priorite = $(this).data('priorite');
            var statut = $(this).data('statut');
            var progres = $(this).data('progres');

            $('#task_id').val(id);
            $('input[name="nom_tache"]').val(nom_tache);
            $('input[name="echeance"]').val(echeance);
            $('select[name="priorite"]').val(priorite);
            $('select[name="statut"]').val(statut);
            $('input[name="progres"]').val(progres);

            $('#submitBtn').attr('name', 'modifier');
            $('#taskModal').modal('show');
        });

        $('.btn-add').click(function() {
            $('#taskForm')[0].reset();
            $('#task_id').val('');
            $('#submitBtn').attr('name', 'ajouter');
        });
    });
    </script>
</body>
</html>