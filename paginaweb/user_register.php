<?php 

session_start();
$error = "";

// Incluir la conexión a la base de datos
require("./src/requirements/db.php");

if (!isset($_SESSION["user_data"]) || $_SESSION["user_data"]["group"] != "admin") {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);


        $sql = "INSERT INTO `users`(`username`, `password`, `group`) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $group = "cliente";
        
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conexion->error);
        }

        $stmt->bind_param("sss", $username, $password_hashed, $group);
        $resultado = $stmt->execute();

        if ($resultado) {
            $error = "Cliente registrado correctamente.";
        } else {
            $error = "Error al registrar el cliente: " . $stmt->error;
        }
    } else {
        $error = "Por favor completa los campos";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Admin Panel</title>
</head>
<body>
    <?php include "./src/components/side-bar.php"; ?>
    <div class="container">
        <div class="content">
            <div class="content-2">
                <div class="recent-payments">
                    <div class="title">
                        <h2>Registrar clientes</h2>
                    </div>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="element">
                            <span>Nombre de usuario</span>
                            <br>
                            <input type="text" name="username" required>
                        </div>
                        <div class="element">
                            <span>Contraseña</span>
                            <br>
                            <input type="password" name="password" required>
                        </div>                      
                        <span><?php echo $error; ?></span>
                        <br>
                        <input type="submit" value="Registrar Cliente" class="btn">
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
