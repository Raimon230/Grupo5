<?php
ob_start();
session_start();

define('DB_HOST', 'localhost');
define('DB_USER', 'a');
define('DB_PASS', 'a');
define('DB_NAME', 'a');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

function verificarSesion() {
    if (isset($_SESSION['user_id']) && isset($_SESSION['email']) && $_SESSION['email'] === 'a@a.com') {
        header("Location: home.php");
        exit();  // Agregamos exit() después de la redirección
    }
}

function registrarUsuario($email, $password, $grupo) {
    global $conn;

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $email = mysqli_real_escape_string($conn, $email);

    $query = "INSERT INTO usuarios (email, password, grupo) VALUES ('$email', '$hashedPassword', '$grupo')";
    return $conn->query($query);
}

function iniciarSesion($email, $password) {
    global $conn;

    $email = mysqli_real_escape_string($conn, $email);

    $query = "SELECT * FROM usuarios WHERE email='$email'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['ID'];
            $_SESSION['email'] = $row['email'];
            return true;
        } else {
            echo "La contraseña no coincide"; // Mensaje de depuración
            $conn->close();
            ob_end_flush();
        }
    } else {
        echo "El usuario no existe"; // Mensaje de depuración
        $conn->close();
        ob_end_flush();
    }

    return false;
}

verificarSesion();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $grupo = $_POST['grupo']; // Nuevo campo para el grupo

    if (isset($_POST['registro'])) {
        // Procesar registro
        if (registrarUsuario($email, $password, $grupo)) {
            // Registro exitoso, redirigir a home.php
            header("Location: home.php");
            exit();
        } else {
            // Error en el registro, mostrar mensaje de error
            echo "Error al registrar el usuario: " . $conn->error;
            $conn->close();
            ob_end_flush();
        }
    } elseif (isset($_POST['login'])) {
        // Procesar inicio de sesión
        if (iniciarSesion($email, $password)) {
            // Inicio de sesión exitoso, redirigir a home.php
            header("Location: home.php");
            exit();
        } else {
            // Error en el inicio de sesión, mostrar mensaje de error
            echo "Inicio de sesión fallido. Verifica tu correo y contraseña.";
            $conn->close();
            ob_end_flush();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión y Registro</title>
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
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #ff6f61; /* Color fuerte */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #e96c5f; /* Color fuerte más oscuro en hover */
        }

        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #ddd;
        }

        h2 {
            margin-top: 0;
            color: #333;
        }
    </style>
</head>
<body>

<!-- Contenido HTML aquí -->

<form action="index.php" method="post">
    <h2>Registro</h2>
    <input type="email" name="email" placeholder="Correo Electrónico" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <select name="grupo" required> <!-- Nuevo campo para el grupo -->
        <option value="administracion">Administración</option>
        <option value="produccion">Producción</option>
        <option value="envio">Envío</option>
        <!-- Agrega más opciones según sea necesario -->
    </select>
    <button type="submit" name="registro">Registrarse</button>
</form>

<hr>

<form action="index.php" method="post">
    <h2>Iniciar Sesión</h2>
    <input type="email" name="email" placeholder="Correo Electrónico" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit" name="login">Iniciar Sesión</button>
</form>

<!-- Más contenido HTML aquí -->

</body>
</html>

