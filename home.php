<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Cerrar sesión al hacer clic en el botón "Cerrar Sesión"
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

// Verificar si el usuario es un administrador
$is_admin = isset($_SESSION['email']) && $_SESSION['email'] === 'a@a.com';

// Verificar si el usuario es un usuario registrado
$is_registered_user = isset($_SESSION['email']);

// Conexión a la base de datos
$servername = "localhost";
$username = "a";  // Actualiza con tu usuario de base de datos
$password = "a";  // Actualiza con tu contraseña de base de datos
$dbname = "a";  // Actualiza con el nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die(" fallida: " . $conn->connect_error);
}

// Obtener el grupo del usuario autenticado
$user_id = $_SESSION['user_id'];
$sql_group = "SELECT grupo FROM usuarios WHERE id = ?";
$stmt_group = $conn->prepare($sql_group);
$stmt_group->bind_param("i", $user_id);
$stmt_group->execute();
$result_group = $stmt_group->get_result();
$group_row = $result_group->fetch_assoc();
$user_group = $group_row['grupo'];

$stmt_group->close();

// Obtener los usuarios del mismo grupo
$sql = "SELECT id, email FROM usuarios WHERE grupo = ? AND id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $user_group, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
$user_options = '';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
        $user_options .= '<option value="' . $row['id'] . '">' . $row['email'] . '</option>';
    }
}

$stmt->close();

// Obtener los archivos subidos para el usuario autenticado
$sql_files = "SELECT id, archivo FROM registros_virustotal WHERE user_id = ?";
$stmt_files = $conn->prepare($sql_files);
$stmt_files->bind_param("i", $user_id);
$stmt_files->execute();
$result_files = $stmt_files->get_result();

$files = [];
if ($result_files->num_rows > 0) {
    while ($row = $result_files->fetch_assoc()) {
        $files[] = $row;
    }
}

$stmt_files->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
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

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            background-color: white;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        h1 {
            text-align: center;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>

<h1>Bienvenido a la página de inicio</h1>

<form action="home.php" method="post">
    <button type="submit" name="logout">Cerrar Sesión</button>
</form>

<?php if ($is_admin): ?>
    <form action="admin.php" method="post">
        <button type="submit">Admin</button>
    </form>
<?php endif; ?>

<?php if ($is_registered_user): ?>
    <form action="virustotal.php" method="post">
        <button type="submit">VirusTotal</button>
    </form>
    <button type="button" onclick="toggleFileUploadForm()">Subir Archivo</button>

    <div id="fileUploadForm" class="hidden">
        <form action="upload.php" method="post" enctype="multipart/form-data" class="file-upload-form">
            <label for="file">Seleccionar archivo:</label>
            <input type="file" name="file" id="file" required>
            <label for="user">Seleccionar usuario:</label>
            <select name="user" id="user" required>
                <?php echo $user_options; ?>
            </select>
            <button type="submit">Subir</button>
        </form>
    </div>

    <h2>Archivos Subidos</h2>
    <form action="archivos_subidos.php" method="post">
        <button type="submit">Archivos Subidos</button>
    </form>
<?php endif; ?>

<script>
function toggleFileUploadForm() {
    const form = document.getElementById('fileUploadForm');
    form.classList.toggle('hidden');
}
</script>

</body>
</html>
