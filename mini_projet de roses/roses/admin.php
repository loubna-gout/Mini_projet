<?php
session_start();


if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}


$host = 'localhost';
$dbname = 'rosa_land';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}


if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    if ($_GET['action'] === 'accepter') {
        $statut = 'acceptee';
    } elseif ($_GET['action'] === 'refuser') {
        $statut = 'refusee';
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE commandes SET statut = :statut WHERE id = :id");
        $stmt->execute([':statut' => $statut, ':id' => $id]);
        
        
        header('Location: admin.php');
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de la mise à jour de la commande: " . $e->getMessage());
    }
}


try {
    $stmt = $pdo->query("SELECT * FROM commandes ORDER BY date_commande DESC");
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des commandes: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Coopérative Rosa Land</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        
        h1 {
            color: #d63384;
            text-align: center;
            margin-bottom: 30px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #ffc0cb;
            color: white;
        }
        
        tr:hover {
            background-color: #fff0f5;
        }
        
        .statut-en_attente {
            color: #ffc107;
            font-weight: bold;
        }
        
        .statut-acceptee {
            color: #28a745;
            font-weight: bold;
        }
        
        .statut-refusee {
            color: #dc3545;
            font-weight: bold;
        }
        
        .btn {
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            margin-right: 5px;
        }
        
        .btn-accepter {
            background-color: #28a745;
        }
        
        .btn-refuser {
            background-color: #dc3545;
        }
        
        .btn-logout {
            display: block;
            text-align: center;
            margin-top: 20px;
            background-color: #6c757d;
            padding: 10px;
            width: 100px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <h1>Gestion des Commandes - Coopérative Rosa Land</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($commandes as $commande): ?>
            <tr>
                <td><?= htmlspecialchars($commande['id']) ?></td>
                <td><?= htmlspecialchars($commande['nom']) ?></td>
                <td><?= htmlspecialchars($commande['email']) ?></td>
                <td><?= htmlspecialchars($commande['produit']) ?></td>
                <td><?= htmlspecialchars($commande['quantite']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($commande['date_commande'])) ?></td>
                <td class="statut-<?= htmlspecialchars($commande['statut']) ?>">
                    <?php 
                    switch($commande['statut']) {
                        case 'en_attente': echo 'En attente'; break;
                        case 'acceptee': echo 'Acceptée'; break;
                        case 'refusee': echo 'Refusée'; break;
                    }
                    ?>
                </td>
                <td>
                    <?php if ($commande['statut'] === 'en_attente'): ?>
                    <a href="admin.php?action=accepter&id=<?= $commande['id'] ?>" class="btn btn-accepter">Accepter</a>
                    <a href="admin.php?action=refuser&id=<?= $commande['id'] ?>" class="btn btn-refuser">Refuser</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <a href="logout.php" class="btn btn-logout">Déconnexion</a>
</body>
</html>