<?php
require_once __DIR__ . "/../config/seguridad.php";
require_once __DIR__ . "/../config/conexion.php";

require_roles(["organizador", "admin"]);

$error = "";
$ok = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = trim($_POST["titulo"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $lugar = trim($_POST["lugar"] ?? "");
    $fecha_evento = trim($_POST["fecha_evento"] ?? "");
    $aforo = (int)($_POST["aforo"] ?? 0);

    if ($titulo === "" || $fecha_evento === "" || $aforo <= 0) {
        $error = "Títol, data i aforament són obligatoris (aforament > 0).";
    } else {
        $id_organizador = (int)$_SESSION["usuario_id"];

        $stmt = $conexion->prepare(
            "INSERT INTO eventos (id_organizador, titulo, descripcion, lugar, fecha_evento, aforo)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("issssi", $id_organizador, $titulo, $descripcion, $lugar, $fecha_evento, $aforo);

        if ($stmt->execute()) {
            $ok = "Esdeveniment creat correctament";
        } else {
            $error = "Error en crear l’esdeveniment.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eventify | Crear esdeveniment</title>

    <link rel="stylesheet" href="../css/base.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/footer.css" />
    <link rel="stylesheet" href="../css/organizador.css" />
</head>

<body>
    <?php include_once __DIR__ . "/../src/header.php"; ?>

    <main class="section">
        <div class="container">
            <div class="card form-card">
                <h2>Crear esdeveniment</h2>
                <p class="section-subtitle">Omple les dades bàsiques del teu esdeveniment.</p>

                <?php if ($error !== ""): ?>
                    <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if ($ok !== ""): ?>
                    <div class="alert-ok"><?php echo htmlspecialchars($ok); ?></div>
                <?php endif; ?>

                <form method="POST" class="form-grid">
                    <label>
                        Títol *
                        <input type="text" name="titulo" required />
                    </label>

                    <label>
                        Lloc *
                        <input type="text" name="lugar" required />
                    </label>

                    <label>
                        Data i hora *
                        <input type="datetime-local" name="fecha_evento" required />
                    </label>

                    <label>
                        Aforament *
                        <input type="number" name="aforo" min="1" required />
                    </label>

                    <label class="full">
                        Descripció
                        <textarea name="descripcion" rows="4"></textarea>
                    </label>

                    <div class="full actions">
                        <button class="btn btn-primary" type="submit">Guardar</button>
                        <a class="btn btn-outline" href="panel_organizador.php">Tornar</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php include_once __DIR__ . "/../src/footer.php"; ?>
</body>
</html>
