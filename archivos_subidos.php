<?php
// Habilitar la visualización de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
$username = "a";
$password = "a";
$dbname = "a";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener archivos subidos por el usuario actual
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM registros_virustotal WHERE user_id = $user_id";
$result = $conn->query($sql);

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archivos Subidos</title>
    <style>
        /* Estilos CSS aquí */
    </style>
</head>
<body>
    <h1>Archivos Subidos</h1>

    <!-- Tabla para mostrar archivos subidos -->
    <table>
        <tr>
            <th>Archivo</th>
            <th>Descargar</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['archivo']; ?></td>
                    <td>
                        <form action="download.php" method="post">
                            <input type="hidden" name="file_id" value="<?php echo $row['id']; ?>">
                            <button type="submit">Descargar</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="2">No hay archivos subidos.</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- Botón para volver a la página principal -->
    <form action="home.php" method="get">
        <button type="submit">Volver</button>
    </form>

    <!-- Más contenido HTML aquí -->

</body>
</html>
