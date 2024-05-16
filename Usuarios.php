<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=unauthorized");
    exit();
}

// Conexión a la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'a');
define('DB_PASS', 'a');
define('DB_NAME', 'a');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Consulta para obtener la lista de usuarios con sus grupos
$sql = "SELECT id, email, grupo FROM usuarios";

$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}

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

        table {
            width: 90%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            background-color: #d9ead3;
        }

        th {
            background-color: #5f9ea0;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .cancel-button {
            background-color: #f44336;
            border: none;
            color: white;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }

        .cancel-button:hover {
            background-color: #d32f2f;
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

<h1>Panel de Usuario</h1>

<div class="welcome">
    <p>Bienvenido, Admin</p>
</div>

<!-- Lista de usuarios -->
<section>
    <h2>Lista de Usuarios</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Grupo</th>
            <th>Acción</th>
        </tr>
        <?php
        // Mostrar los usuarios obtenidos de la base de datos
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["grupo"] . "</td>";
                echo "<td>";
                echo "<button class='cancel-button' data-user-id='" . $row["id"] . "'>Eliminar</button>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No hay usuarios registrados.</td></tr>";
        }
        ?>
    </table>
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Obtener todos los botones "Cancelar"
    var cancelButtons = document.querySelectorAll(".cancel-button");

    // Agregar un evento de clic a cada botón
    cancelButtons.forEach(function(button) {
        button.addEventListener("click", function() {
            // Obtener el ID del usuario asociado con el botón
            var userId = button.getAttribute("data-user-id");

            // Mostrar la ventana de confirmación
            var confirmDelete = confirm("¿Seguro que quieres eliminar este usuario?");

            // Si el usuario confirma la eliminación, enviar una solicitud al servidor
            if (confirmDelete) {
                // Enviar una solicitud POST a eliminar_usuarios.php
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "eliminar_usuarios.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        console.log(xhr.responseText); // Mostrar la respuesta del servidor en la consola
                        if (xhr.responseText === "success") {
                            // Éxito en la eliminación
                            // Eliminar la fila de la tabla correspondiente al usuario eliminado
                            button.closest("tr").remove();
                        } else {
                            // Error en la eliminación
                            alert("Error al eliminar el usuario.");
                        }
                    }
                };
                xhr.send("user_id=" + userId);

            }
        });
    });
});
</script>
</body>
</html>
