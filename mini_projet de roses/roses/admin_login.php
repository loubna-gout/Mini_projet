<?php
session_start();


if (isset($_SESSION['admin_logged_in'])) {
    header('Location: admin.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    
    if ($username === 'admin' && $password === 'motdepasseadmin') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit();
    } else {
        $error = "Identifiants incorrects";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - Rosa Land</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff0f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(255, 105, 180, 0.2);
            width: 300px;
            text-align: center;
        }
        
        h1 {
            color: #d63384;
            margin-bottom: 30px;
        }
        
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 2px solid #ffc0cb;
            border-radius: 10px;
            box-sizing: border-box;
        }
        
        button {
            background-color: #ff69b4;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
        }
        
        button:hover {
            background-color: #ff1493;
        }
        
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Connexion Admin</h1>
        
        <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>