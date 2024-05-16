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

// Consulta para obtener la lista de usuarios con su grupo
$sql = "SELECT id, email, grupo FROM usuarios";

$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}

// Actualizar el grupo de un usuario si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['user_id'], $_POST['grupo'])) {
    $user_id = $_POST['user_id'];
    $grupo = $_POST['grupo'];

    $update_sql = "UPDATE usuarios SET grupo = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $grupo, $user_id);
    
    if ($stmt->execute()) {
        // Redirigir para evitar envíos duplicados del formulario
        header("Location: grupos.php");
        exit();
    } else {
        echo "Error al actualizar el grupo del usuario.";
    }
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupos de Usuarios</title>
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
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            background-color: #d9ead3; /* Verde pastel */
        }

        th {
            background-color: #5f9ea0; /* Azul cadete */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        form select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        form button {
            padding: 8px 12px;
            background-color: #5f9ea0; /* Azul cadete */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #4c7e82; /* Azul oscuro */
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

<table>
    <tr>
        <th>ID</th>
        <th>Email</th>
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
            echo "<form method='post'>";
            echo "<input type='hidden' name='user_id' value='" . $row["id"] . "'>";
            echo "<select name='grupo'>";
            echo "<option value='administracion'>Administracion</option>";
            echo "<option value='produccion'>Produccion</option>";
            echo "<option value='envio'>Envio</option>";
            echo "</select>";
            echo "<button type='submit'>Actualizar Grupo</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No hay usuarios registrados.</td></tr>";
    }
    ?>
</table>

</body>
</html>
