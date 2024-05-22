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

// Consulta para obtener la lista de usuarios
$sql = "SELECT id, email FROM usuarios";

$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}

// Obtener el ID del usuario seleccionado
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

// Consulta para obtener los registros de VirusTotal del usuario seleccionado
$registros_sql = "SELECT id, archivo, resultado FROM registros_virustotal WHERE user_id = ?";
$stmt = $conn->prepare($registros_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$registros_result = $stmt->get_result();

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración</title>
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

        select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }

        table {
            width: 80%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #333;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
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

<h1>Configuración</h1>

<form method="get">
    <label for="user_id">Seleccionar Usuario:</label>
    <select name="user_id" id="user_id">
        <option value="">Seleccionar Usuario</option>
        <?php
        // Mostrar la lista de usuarios obtenida de la base de datos
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<option value='" . $row["id"] . "'";
                if ($row["id"] == $user_id) {
                    echo " selected";
                }
                echo ">" . $row["email"] . "</option>";
            }
        }
        ?>
    </select>
    <button type="submit">Mostrar Registros</button>
</form>

<?php if ($user_id): ?>
    <h2>Registros de VirusTotal para <?php echo $user_id; ?></h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Archivo</th>
            <th>Resultado</th>
        </tr>
        <?php
        // Mostrar los registros de VirusTotal obtenidos de la base de datos para el usuario seleccionado
        if ($registros_result->num_rows > 0) {
            while($row = $registros_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["archivo"] . "</td>";
                echo "<td>" . $row["resultado"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No hay registros para este usuario.</td></tr>";
        }
        ?>
    </table>
<?php endif; ?>

</body>
</html>
