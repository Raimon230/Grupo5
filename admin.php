<?php
session_start();

// Verificar si el usuario está autenticado y es admin
if (!isset($_SESSION['user_id']) || $_SESSION['email'] !== 'a@a.com') {
    header("Location: index.php?error=unauthorized");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: white;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        h1 {
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Contenido HTML aquí -->

<h1>Página de Administrador</h1>

<form action="home.php" method="post">
    <button type="submit" name="back">Back</button>
</form>

<!-- Más contenido HTML aquí -->

</body>
</html>
