<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Archivo</title>
</head>
<body>
    <h1>Subir Archivo</h1>
    <?php
    $target_dir = "/home/proyecto/dj-sample/uploads";  // Carpeta donde se guardarán los archivos
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // ... (código de verificación existente)

    // Verificar si $uploadOk es 0 por un error
    if ($uploadOk == 0) {
        echo "Lo siento, tu archivo no fue cargado.";
    } else {
        // Si todo está bien, intenta cargar el archivo
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            echo "El archivo " . basename($_FILES["file"]["name"]) . " ha sido cargado.";

            // Llamar al script de Python para el análisis
            $python_script = "/var/www/servidor/virustotal.py";  // Reemplaza con la ruta correcta
            $output = shell_exec("python3 $python_script");

            // Mostrar el resultado del análisis
            echo "<br>Resultado del análisis: $output";
        } else {
            echo "Lo siento, hubo un error al cargar tu archivo.";
        }
    }
    ?>
    <form action="{% url 'registro' %}" method="post" enctype="multipart/form-data">
        {% csrf_token %}
        <label for="file">Seleccionar Archivo:</label>
        <input type="file" name="file" id="file" required>
        <br>
        <input type="submit" value="Subir Archivo">
    </form>
</body>
</html>
