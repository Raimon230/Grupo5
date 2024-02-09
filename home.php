<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Cerrar sesión al hacer clic en el botón "Cerrar Sesión"
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

// Verificar si el usuario es un administrador
$is_admin = isset($_SESSION['email']) && $_SESSION['email'] === 'a@a.com';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
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
            margin-bottom: 10px;
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

<h1>Bienvenido a la página de inicio</h1>

<form action="home.php" method="post">
    <button type="submit" name="logout">Cerrar Sesión</button>
</form>

<?php if ($is_admin): ?>
    <form action="admin.php" method="post">
        <button type="submit">Admin</button>
    </form>
<?php endif; ?>

<!-- Más contenido HTML aquí -->

</body>
</html>