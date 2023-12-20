<?php
{% csrf_token %}
$target_dir = "/home/proyecto/dj-sample/uploads";  // Carpeta donde se guardarán los archivos
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Verificaciones previas
if ($_FILES["file"]["error"] > 0) {
    echo "Error al puixar l´arxiu: " . $_FILES["file"]["error"];
    $uploadOk = 0; // Establecer $uploadOk en 0 indica un problema
}

// Verificar si el archivo ya existe
if (file_exists($target_file)) {
    echo "Ja existeix";
    $uploadOk = 0; // Establecer $uploadOk en 0 indica un problema
}

// Verificar el tamaño del archivo (por ejemplo, 2 MB)
if ($_FILES["file"]["size"] > 2000000) {
    echo "Es massa gran.";
    $uploadOk = 0; // Establecer $uploadOk en 0 indica un problema
}

// Permitir solo ciertos formatos de archivo (ejemplo: solo imágenes)
$allowed_formats = array("jpg", "jpeg", "png", "gif");
if (!in_array($imageFileType, $allowed_formats)) {
    echo "Sol arxius d'imatge (JPG, JPEG, PNG, GIF).";
    $uploadOk = 0; // Establecer $uploadOk en 0 indica un problema
}

// Verificar si $uploadOk es 0 por un error
if ($uploadOk == 0) {
    echo "No ha sigut cargat";
} else {
    // Si todo está bien, intenta cargar el archivo
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "L'arxiu " . basename($_FILES["file"]["name"]) . " ha sigut cargat.";

        // Llamar al script de Python para el análisis
        $python_script = "VirustotalPJ(GRUP5).py";  // Reemplaza con la ruta correcta
        $output = shell_exec("python3 $python_script");

        // Mostrar el resultado del análisis
        echo "<br>Resultat del análisis: $output";
    } else {
        echo "Hi ha hagut un error a l'hora de puixar-lo";
    }
}
?>