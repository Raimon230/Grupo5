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
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
        }

        nav {
            background-color: #333;
            width: 100%;
            padding: 10px 0;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        nav ul li a:hover {
            color: #ccc;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<nav>
    <ul>
        <li><a href="usuarios.php">Usuarios</a></li>
        <li><a href="grupos.php">Grupos</a></li>
        <li><a href="configuracion.php">Configuración</a></li>
        <li><a href="home.php">Salir</a></li>
    </ul>
</nav>

<h1>Panel de Administración</h1>

</body>
</html>
