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

// Verificar si se ha proporcionado un ID de archivo
if (!isset($_POST['file_id'])) {
    echo "Error: No se ha proporcionado un ID de archivo.";
    exit();
}

$file_id = $_POST['file_id'];

// Conexión a la base de datos
$servername = "localhost";
$username = "a";  // Actualiza con tu usuario de base de datos
$password = "a";  // Actualiza con tu contraseña de base de datos
$dbname = "a";  // Actualiza con el nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener la ruta del archivo desde la base de datos
$stmt = $conn->prepare("SELECT archivo FROM registros_virustotal WHERE id = ?");
$stmt->bind_param("i", $file_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $file_path = $row['archivo'];

    // Descargar el archivo
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
    readfile($file_path);
} else {
    echo "Archivo no encontrado.";
}

$stmt->close();
$conn->close();
?>
