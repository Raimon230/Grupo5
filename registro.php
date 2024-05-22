<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que se hayan enviado todos los campos necesarios
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['grupo'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $grupo = $_POST['grupo'];

        // Verificar que las contraseñas coincidan
        if ($password !== $confirm_password) {
            echo "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.";
            exit();
        }

        // Realizar cualquier otra validación necesaria

        // Guardar los datos del usuario en la base de datos
        require_once 'database.php'; // Asegúrate de incluir el archivo de conexión a la base de datos
        $conn = conectar(); // Función para conectar a la base de datos

        // Hash de la contraseña antes de guardarla en la base de datos (asegúrate de usar un método seguro como password_hash)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Preparar la consulta SQL para insertar el registro del usuario
        $sql = "INSERT INTO usuarios (username, email, password, grupo) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $grupo);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Registro exitoso, redirigir al usuario a la página de inicio de sesión con un mensaje de éxito
            header("Location: index.php?registro=exitoso");
            exit();
        } else {
            // Si falla la inserción en la base de datos, mostrar un mensaje de error
            echo "Error al registrar el usuario: " . $conn->error;
            exit();
        }
    } else {
        // Si faltan campos en el formulario, mostrar un mensaje de error
        echo "Por favor, completa todos los campos del formulario.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <!-- Estilos CSS u otros recursos -->
</head>
<body>

<h1>Registro de Usuario</h1>

<form action="registro.php" method="post">
    <label for="username">Nombre de Usuario:</label>
    <input type="text" id="username" name="username" required>
    <label for="email">Correo Electrónico:</label>
    <input type="email" id="email" name="email" required>
    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" required>
    <label for="confirm_password">Confirmar Contraseña:</label>
    <input type="password" id="confirm_password" name="confirm_password" required>
    <label for="grupo">Grupo:</label>
    <select id="grupo" name="grupo" required>
        <option value="administracion">Administración</option>
        <option value="produccion">Producción</option>
        <option value="envio">Envío</option>
        <!-- Agrega más opciones según sea necesario -->
    </select>
    <button type="submit">Registrarse</button>
</form>

</body>
</html>
