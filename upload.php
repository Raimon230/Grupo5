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

// Verificar si se ha enviado un archivo
if (!isset($_FILES['file'])) {
    echo "Error: No se ha proporcionado ningún archivo.";
    exit();
}

// Verificar si se ha seleccionado un usuario
if (!isset($_POST['user'])) {
    echo "Error: No se ha seleccionado ningún usuario.";
    exit();
}

// Datos del usuario seleccionado
$user_id = $_POST['user'];

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

// Leer el contenido del archivo
$file_name = $_FILES['file']['name'];
$file_tmp_name = $_FILES['file']['tmp_name'];
$upload_dir = 'uploads/';
$file_path = $upload_dir . basename($file_name);

// Crear el directorio si no existe
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Mover el archivo al directorio de destino
if (move_uploaded_file($file_tmp_name, $file_path)) {
    $resultado = ''; // Supongo que el resultado estará vacío inicialmente

    // Insertar la ruta del archivo en la base de datos
    $stmt = $conn->prepare("INSERT INTO registros_virustotal (user_id, archivo, resultado) VALUES (?, ?, ?)");
    if ($stmt === false) {
        die("Error al preparar la declaración: " . $conn->error);
    }

    $stmt->bind_param("iss", $user_id, $file_path, $resultado);

    if ($stmt->execute()) {
        $_SESSION['upload_success'] = "El archivo se ha cargado y guardado correctamente en la base de datos.";
    } else {
        $_SESSION['upload_success'] = "Error al guardar el archivo en la base de datos: " . $stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['upload_success'] = "Error al mover el archivo.";
}

$conn->close();

header("Location: home.php");
exit();
?>
