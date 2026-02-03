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
    $nombre = trim($_POST["nombre"] ?? "");
    $correo = trim($_POST["correo"] ?? "");
    $contrasena = $_POST["contrasena"] ?? "";
    $contrasena2 = $_POST["contrasena2"] ?? "";
    $rol = $_POST["rol"] ?? "asistente";

    // Validaciones básicas
    if ($nombre === "" || $correo === "" || $contrasena === "" || $contrasena2 === "") {
        $error = "Rellena todos los campos.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo no es válido.";
    } elseif (strlen($contrasena) < 4) {
        $error = "La contraseña debe tener al menos 4 caracteres.";
    } elseif ($contrasena !== $contrasena2) {
        $error = "Las contraseñas no coinciden.";
    } elseif (!in_array($rol, ["asistente", "organizador"], true)) {
        // Por seguridad: solo permitimos elegir asistente u organizador
        $error = "Rol no válido.";
    } else {
        // ¿Existe ya el correo?
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ? LIMIT 1");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $res = $stmt->get_result();
        $existe = $res->fetch_assoc();
        $stmt->close();

        if ($existe) {
            $error = "Este correo ya está registrado.";
        } else {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);

            $stmt = $conexion->prepare(
                "INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("ssss", $nombre, $correo, $hash, $rol);

            if ($stmt->execute()) {
                $stmt->close();

                // Auto-login después de registrarse (opcional, pero cómodo)
                $nuevo_id = $conexion->insert_id;
                $_SESSION["usuario_id"] = $nuevo_id;
                $_SESSION["usuario_nombre"] = $nombre;
                $_SESSION["usuario_correo"] = $correo;
                $_SESSION["usuario_rol"] = $rol;

                header("Location: ../src/index.php");
                exit;
            } else {
                $error = "Error al registrar. Inténtalo de nuevo.";
                $stmt->close();
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
    <title>Eventify | Registra’t</title>

    <link rel="stylesheet" href="../css/base.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/footer.css" />
    <link rel="stylesheet" href="../css/register.css" />
</head>

<body>

    <?php include_once __DIR__ . "/../src/header.php"; ?>

    <main class="section">
        <div class="container">
            <div class="register-wrap card">
                <h2>Registra’t</h2>
                <p class="section-subtitle">Crea el teu compte d’Eventify.</p>

                <?php if ($error !== ""): ?>
                    <div class="alert-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="register-form" autocomplete="off">
                    <label>
                        Nom
                        <input type="text" name="nombre" placeholder="El teu nom" required />
                    </label>

                    <label>
                        Correu
                        <input type="email" name="correo" placeholder="tu@exemple.com" required />
                    </label>

                    <label>
                        Contrasenya
                        <input type="password" name="contrasena" placeholder="********" required />
                    </label>

                    <label>
                        Repetir contrasenya
                        <input type="password" name="contrasena2" placeholder="********" required />
                    </label>

                    <label>
                        Tipus de compte
                        <select name="rol" required>
                            <option value="asistente">Assistent</option>
                            <option value="organizador">Organitzador</option>
                        </select>
                    </label>

                    <button type="submit" class="btn btn-primary btn-full">Crear compte</button>
                </form>

                <p class="register-extra">
                    Ja tens compte?
                    <a href="login.php">Inicia sessió</a>
                </p>
            </div>
        </div>
    </main>

    <?php include_once __DIR__ . "/../src/footer.php"; ?>

</body>
</html>
