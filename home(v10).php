<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home.php</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 100;
            background-color: #3b3f5c;
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
        }

        .nombre_logo2 {
            font-size: 25px;
            text-decoration: none;
            color: #fff;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info i {
            margin-right: 10px;
            font-size: 24px;
        }

        .user-info span {
            font-size: 18px;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding-top: 100px; /* Ajustar según la altura del header */
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .hidden {
            display: none;
        }

        #sidebar {
            position: fixed;
            right: 0;
            top: 0;
            height: 100%;
            width: 60px;
            background-color: #3b3f5c;
            color: #fff;
            padding: 20px 10px;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }

        #sidebar .icon {
            font-size: 24px;
            margin-bottom: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #sidebar .icon:hover {
            color: #4caf50;
        }

        #sidebar .toggle-button {
            font-size: 24px;
            background-color: #4caf50;
            position: absolute;
            left: -50px;
            top: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.5);
        }

        #sidebar .toggle-button:hover {
            background-color: #45a049;
        }

        .file-upload-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 90%;
            z-index: 200;
        }

        .file-upload-container .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .file-upload-container .header button {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            margin-left: 10px;
        }

        .file-upload-container .header button:hover {
            color: #4caf50;
        }

        .file-upload-form label {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .file-upload-form input,
        .file-upload-form select,
        .file-upload-form button {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .file-upload-form button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .file-upload-form button:hover {
            background-color: #45a049;
        }

        .minimized {
            width: 200px;
            height: 40px;
            padding: 10px;
            text-align: center;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- LOGO NOMBRE -->
    <header>
        <a href="#" class="nombre_logo2">SecureMend</a>
        <div class="user-info">
            <i class="uil uil-user"></i>
            <span><?php echo htmlspecialchars($username); ?></span>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main>
        <h1>Bienvenido a la página de inicio</h1>
    </main>

    <!-- SIDEBAR MENU -->
    <div id="sidebar">
        <div class="toggle-button" onclick="toggleSidebar()">
            <i class="uil uil-angle-left-b"></i>
        </div>
        <i class="uil uil-home icon" title="Inicio" onclick="window.location.href='home.php'"></i>
        <?php if ($is_registered_user): ?>
            <i class="uil uil-shield-check icon" title="VirusTotal" onclick="window.location.href='virustotal.php'"></i>
            <i class="uil uil-upload icon" title="Subir Archivo" onclick="toggleFileUploadForm()"></i>
            <i class="uil uil-file-alt icon" title="Archivos Subidos" onclick="window.location.href='archivos_subidos.php'"></i>
        <?php endif; ?>
        <?php if ($is_admin): ?>
            <i class="uil uil-user-shield icon" title="Admin" onclick="window.location.href='admin.php'"></i>
        <?php endif; ?>
        <i class="uil uil-signout icon" title="Cerrar Sesión" onclick="document.querySelector('form[action=\'home.php\'] button[name=\'logout\']').click()"></i>
    </div>

    <!-- FILE UPLOAD FORM -->
    <div id="fileUploadForm" class="hidden file-upload-container">
        <div class="header">
            <button onclick="minimizeFileUploadForm()">&#x2212;</button>
            <button onclick="toggleFileUploadForm()">&#x2715;</button>
        </div>
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

    <script>
        function toggleFileUploadForm() {
            const form = document.getElementById('fileUploadForm');
            form.classList.toggle('hidden');
        }

        function minimizeFileUploadForm() {
            const form = document.getElementById('fileUploadForm');
            form.classList.toggle('minimized');
            if (form.classList.contains('minimized')) {
                form.innerHTML = '<div class="header"><button onclick="minimizeFileUploadForm()">&#x25A4;</button><button onclick="toggleFileUploadForm()">&#x2715;</button></div>';
            } else {
                form.innerHTML = `
                    <div class="header">
                        <button onclick="minimizeFileUploadForm()">&#x2212;</button>
                        <button onclick="toggleFileUploadForm()">&#x2715;</button>
                    </div>
                    <form action="upload.php" method="post" enctype="multipart/form-data" class="file-upload-form">
                        <label for="file">Seleccionar archivo:</label>
                        <input type="file" name="file" id="file" required>
                        <label for="user">Seleccionar usuario:</label>
                        <select name="user" id="user" required>
                            <?php echo $user_options; ?>
                        </select>
                        <button type="submit">Subir</button>
                    </form>
                `;
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hidden');
        }
    </script>
</body>
</html>

