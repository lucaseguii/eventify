<?php
session_start();
require_once __DIR__ . "/../config/conexion.php";

// Si ya está logueado, lo mandamos a la home
if (isset($_SESSION["usuario_id"])) {
    header("Location: ../src/index.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = trim($_POST["correo"] ?? "");
    $contrasena = $_POST["contrasena"] ?? "";

    if ($correo === "" || $contrasena === "") {
        $error = "Rellena el correo y la contraseña.";
    } else {
        // Buscar usuario por correo
        $stmt = $conexion->prepare("SELECT id, nombre, correo, contrasena, rol FROM usuarios WHERE correo = ? LIMIT 1");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();
        $stmt->close();

        if (!$usuario) {
            $error = "El correo no existe.";
        } else {
            // Verificar contraseña (guardada con password_hash)
            if (password_verify($contrasena, $usuario["contrasena"])) {
                // Login OK -> guardar sesión
                $_SESSION["usuario_id"] = $usuario["id"];
                $_SESSION["usuario_nombre"] = $usuario["nombre"];
                $_SESSION["usuario_correo"] = $usuario["correo"];
                $_SESSION["usuario_rol"] = $usuario["rol"];

                header("Location: ../src/index.php");
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eventify | Inicia sessió</title>

    <link rel="stylesheet" href="../css/base.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/footer.css" />
    <link rel="stylesheet" href="../css/login.css" />
</head>

<body>

    <?php include_once __DIR__ . "/../src/header.php"; ?>

    <main class="section">
        <div class="container">
            <div class="login-wrap card">
                <h2>Inicia sessió</h2>
                <p class="section-subtitle">Accedeix al teu compte d’Eventify.</p>

                <?php if ($error !== ""): ?>
                    <div class="alert-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="login-form" autocomplete="off">
                    <label>
                        Correu
                        <input type="email" name="correo" placeholder="tu@exemple.com" required />
                    </label>

                    <label>
                        Contrasenya
                        <input type="password" name="contrasena" placeholder="********" required />
                    </label>

                    <button type="submit" class="btn btn-primary btn-full">Entrar</button>
                </form>

                <p class="login-extra">
                    Encara no tens compte?
                    <a href="register.php">Registra’t</a>
                </p>
            </div>
        </div>
    </main>

    <?php include_once __DIR__ . "/../src/footer.php"; ?>

</body>
</html>
