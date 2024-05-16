<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['user_id'])) {
    // Conexión a la base de datos
    define('DB_HOST', 'localhost');
    define('DB_USER', 'a');
    define('DB_PASS', 'a');
    define('DB_NAME', 'a');

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Error de conexión a la base de datos: " . $conn->connect_error);
    }

    // Obtener el ID del usuario a eliminar
    $user_id = $_POST['user_id'];

    // Consulta SQL para eliminar el usuario de la tabla usuarios
    $delete_user_sql = "DELETE FROM usuarios WHERE ID = ?";
    $stmt_delete_user = $conn->prepare($delete_user_sql);
    $stmt_delete_user->bind_param("i", $user_id);

    // Ejecutar la consulta para eliminar el usuario de la tabla usuarios
    if ($stmt_delete_user->execute()) {
        // Éxito en la eliminación
        echo "success";
    } else {
        // Error al eliminar el usuario
        echo "error_delete_user: " . $stmt_delete_user->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    // Si no se reciben los datos del usuario, redirigir a la página de inicio
    header("Location: index.php");
    exit();
}
?>
