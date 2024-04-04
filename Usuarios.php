<?php
ob_start();
session_start();

define('DB_HOST', 'localhost');
define('DB_USER', 'a');
define('DB_PASS', 'a');
define('DB_NAME', 'a');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

function verificarSesion() {
    if (isset($_SESSION['user_id']) && isset($_SESSION['email']) && $_SESSION['email'] === 'a@a.com') {
        header("Location: home.php");
        exit();  // Agregamos exit() después de la redirección
    }
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=unauthorized");
    exit();
}

// Consulta para obtener la lista de usuarios
$sql = "SELECT id, username, email FROM usuarios";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario</title>
    
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

        .welcome {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<nav>
    <ul>
        <li><a href="#profile">Perfil</a></li>
        <li><a href="#orders">Grupos</a></li>
        <li><a href="#settings">Configuración</a></li>
        <li><a href="index.php">Salir</a></li>
    </ul>
</nav>

<h1>Panel de Usuario</h1>

<div class="welcome">
    <p>Bienvenido, <?php echo $_SESSION['usuarios']; ?>!</p>
</div>

<!-- Contenido del perfil del usuario -->
<section id="profile">
    <h2>Perfil</h2>
    <!-- Aquí puedes mostrar la información del perfil del usuario, como nombre, correo electrónico, etc. -->
</section>

<!-- Contenido de las órdenes del usuario -->
<section id="Grupos">
    <h2>Grupos</h2>
    <!-- Aquí puedes mostrar la lista de órdenes del usuario, historial de compras, etc. -->
</section>

<!-- Contenido de la configuración del usuario -->
<section id="settings">
    <h2>Configuración</h2>
    <!-- Aquí puedes mostrar opciones de configuración para el usuario, como cambiar contraseña, ajustes de cuenta, etc. -->
</section>

</body>
</html>
