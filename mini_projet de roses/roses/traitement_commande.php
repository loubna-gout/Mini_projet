<?php

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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $produit = htmlspecialchars($_POST['produit']);
    $quantite = intval($_POST['quantite']);
    $message = htmlspecialchars($_POST['message']);
    $date_commande = date('Y-m-d H:i:s');
    $statut = 'en_attente';

   
    try {
        $stmt = $pdo->prepare("INSERT INTO commandes (nom, email, produit, quantite, message, date_commande, statut) 
                               VALUES (:nom, :email, :produit, :quantite, :message, :date_commande, :statut)");
        
        $stmt->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':produit' => $produit,
            ':quantite' => $quantite,
            ':message' => $message,
            ':date_commande' => $date_commande,
            ':statut' => $statut
        ]);

        
        header('Location: confirmation_commande.html');
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de l'enregistrement de la commande: " . $e->getMessage());
    }
}
?>