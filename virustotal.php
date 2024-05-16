<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

function showError($message) {
    echo "<div style=\"color: red;\">Error: $message</div>";
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['file'])) {
    $temp_file = $_FILES['file']['tmp_name'];

    if (!file_exists($temp_file)) {
        showError("El archivo no se ha subido correctamente.");
        exit;
    }

    // Verificar si el usuario está autenticado
    if (!isset($_SESSION['user_id'])) {
        showError("Usuario no autenticado.");
        exit;
    }

    // Conexión a la base de datos
    define('DB_HOST', 'localhost');
    define('DB_USER', 'a');
    define('DB_PASS', 'a');
    define('DB_NAME', 'a');

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        showError("Error de conexión a la base de datos: " . $conn->connect_error);
        exit;
    }

    // Obtener el user_id del usuario que ha iniciado sesión
    $user_id = $_SESSION['user_id'];

    // Guardar el archivo en la tabla registros_virustotal
    $archivo = $_FILES['file']['name'];
    $python_script_path = __DIR__ . "/upload.py";
    $resultado = shell_exec("python3 $python_script_path --path $temp_file 2>&1");
    
    // Limitar la longitud del resultado a 255 caracteres
    $resultado_recortado = substr($resultado, 0, 255);

    // Convertir el resultado a un estado según los criterios establecidos
    if (strpos($resultado, 'malicious') !== false) {
        $estado = 'Cuarentena';
    } elseif (strpos($resultado, 'undetected') !== false) {
        $estado = 'Seguro';
    } elseif (strpos($resultado, 'undetected') === false && strpos($resultado, 'timeout') === false) {
        $num_virus = preg_match_all('/[0-9]+ malicious/', $resultado, $matches);
        if ($num_virus > 10) {
            $estado = 'No seguro';
        } else {
            $estado = 'Cuarentena';
        }
    } else {
        $estado = 'No se pudo analizar';
    }

    // Insertar el resultado en la base de datos
    $stmt = $conn->prepare("INSERT INTO registros_virustotal (user_id, archivo, resultado) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $archivo, $resultado_recortado);
    $stmt->execute();

    // Cerrar la conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VirusTotal</title>
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

        .container {
            text-align: center;
        }

        #file-input {
            display: none; /* Ocultar el input de tipo file */
        }

        #file-path {
            width: 300px;
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        #virustotal-output {
            margin-top: 20px;
            width: 100%;
            height: 200px;
            overflow: auto;
            border: 1px solid #ccc;
            padding: 10px;
            box-sizing: border-box;
        }

        #virustotal-output pre {
            font-family: Consolas, monospace;
            font-size: 12px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Archivo que vas a subir</h2>
    <form id="upload-form" action="" method="post" enctype="multipart/form-data">
        <input type="file" id="file-input" name="file">
        <a href="home.php" style="text-decoration: none;"><button type="button" style="background-color: #4caf50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin-top: 10px;">Regresar</button></a>
        <label for="file-input" style="background-color: #4caf50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin-top: 10px;">Seleccionar Archivo</label>
        <input type="text" id="file-path" name="file_path" readonly>
        <button type="submit" style="background-color: #4caf50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin-top: 10px;">Subir Archivo</button>
        <!-- Agregar botón de descarga -->
        <a href="#" style="display: inline-block; background-color: #4caf50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin-top: 10px; text-decoration: none;">Descargar</a>
    </form>

    <div id="virustotal-output">
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['file'])) {
            echo "<pre>$resultado</pre>"; // Placeholder para el resultado de VirusTotal
        }
        ?>
    </div>
    
    <h2>VirusTotal</h2>
</div>

<script>
    function updateFilePath() {
        const filePath = document.getElementById('file-input').value;
        document.getElementById('file-path').value = filePath;
        // Actualizar el atributo "href" del enlace de descarga con la ruta completa del archivo seleccionado
        document.getElementById('download-link').setAttribute('href', filePath);
    }

    document.getElementById('file-input').addEventListener('change', updateFilePath);
</script>

</body>
</html>
