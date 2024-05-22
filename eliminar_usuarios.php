<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    $user_id = intval($_POST['user_id']); // Asegurarse de que user_id es un número entero

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // Eliminar registros asociados en registros_virustotal
        $delete_related_sql = "DELETE FROM registros_virustotal WHERE user_id = ?";
        $stmt_delete_related = $conn->prepare($delete_related_sql);
        $stmt_delete_related->bind_param("i", $user_id);
        $stmt_delete_related->execute();

        // Eliminar el usuario de la tabla usuarios
        $delete_user_sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt_delete_user = $conn->prepare($delete_user_sql);
        $stmt_delete_user->bind_param("i", $user_id);
        $stmt_delete_user->execute();

        // Confirmar transacción
        $conn->commit();

        // Éxito en la eliminación
        echo "success";
    } catch (Exception $e) {
        // Deshacer transacción
        $conn->rollback();

        // Error al eliminar el usuario o los registros asociados
        echo "error_delete_user: " . $e->getMessage();
    }

    // Cerrar la conexión
    $stmt_delete_related->close();
    $stmt_delete_user->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
