<?php
require_once __DIR__ . "/../config/seguridad.php";
require_once __DIR__ . "/../config/conexion.php";

require_roles(["organizador", "admin"]);

$id_usuario = (int)$_SESSION["usuario_id"];

if (($_SESSION["usuario_rol"] ?? "") === "admin") {
    $sql = "SELECT e.id, e.titulo, e.lugar, e.fecha_evento, e.aforo, u.nombre AS organizador
            FROM eventos e
            JOIN usuarios u ON u.id = e.id_organizador
            ORDER BY e.fecha_evento DESC";
    $stmt = $conexion->prepare($sql);
} else {
    $sql = "SELECT id, titulo, lugar, fecha_evento, aforo
            FROM eventos
            WHERE id_organizador = ?
            ORDER BY fecha_evento DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
}

$stmt->execute();
$eventos = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eventify | Panel Organitzador</title>

    <link rel="stylesheet" href="../css/base.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/footer.css" />
    <link rel="stylesheet" href="../css/organizador.css" />
</head>

<body>
    <?php include_once __DIR__ . "/../src/header.php"; ?>

    <main class="section">
        <div class="container">
            <div class="top-bar">
                <div>
                    <h2>Panel d’organitzador</h2>
                    <p class="section-subtitle">
                        Gestiona els teus esdeveniments.
                    </p>
                </div>

                <a href="crear_evento.php" class="btn btn-primary">Crear esdeveniment</a>
            </div>

            <div class="card">
                <h3>Els meus esdeveniments</h3>

                <?php if ($eventos->num_rows === 0): ?>
                    <p class="muted">Encara no tens esdeveniments creats.</p>
                <?php else: ?>
                    <div class="tabla">
                        <div class="tabla-head">
                            <div>Títol</div>
                            <div>Lloc</div>
                            <div>Data</div>
                            <div>Aforament</div>
                            <?php if (($_SESSION["usuario_rol"] ?? "") === "admin"): ?>
                                <div>Organitzador</div>
                            <?php endif; ?>
                        </div>

                        <?php while ($e = $eventos->fetch_assoc()): ?>
                            <div class="tabla-row">
                                <div><?php echo htmlspecialchars($e["titulo"]); ?></div>
                                <div><?php echo htmlspecialchars($e["lugar"] ?? "-"); ?></div>
                                <div><?php echo htmlspecialchars($e["fecha_evento"]); ?></div>
                                <div><?php echo (int)$e["aforo"]; ?></div>
                                <?php if (($_SESSION["usuario_rol"] ?? "") === "admin"): ?>
                                    <div><?php echo htmlspecialchars($e["organizador"]); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <?php include_once __DIR__ . "/../src/footer.php"; ?>
</body>
</html>
